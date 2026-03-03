// Minimal, robust FCM setup for staff dashboards
(function () {
    const allowedRoles = ['waiter'];
    const userRole = document.body.dataset.userRole || '';
    if (!allowedRoles.includes(userRole)) return;
    if (!('serviceWorker' in navigator) || !('Notification' in window)) return;

    const config = window.FIREBASE_CONFIG || {};
    const requiredKeys = ['apiKey', 'projectId', 'messagingSenderId', 'appId'];
    const missing = requiredKeys.filter((key) => !config[key]);
    if (missing.length > 0) {
        console.warn('[FCM] Missing Firebase config keys:', missing.join(', '));
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    function loadScript(src) {
        return new Promise((resolve, reject) => {
            const tag = document.createElement('script');
            tag.src = src;
            tag.async = false;
            tag.onload = resolve;
            tag.onerror = reject;
            document.head.appendChild(tag);
        });
    }

    async function loadFirebaseCompat() {
        // Keep strict load order: app first, then messaging.
        await loadScript('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
        await loadScript('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');
    }

    function registerToken(token) {
        if (!token || !csrfToken) return Promise.resolve();

        return fetch('/api/fcm/register', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ fcm_token: token }),
        }).catch((error) => {
            console.error('[FCM] Token register failed:', error);
        });
    }

    function unregisterToken() {
        if (!csrfToken) return Promise.resolve();

        return fetch('/api/fcm/unregister', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({}),
            keepalive: true,
        }).catch((error) => {
            console.error('[FCM] Token unregister failed:', error);
        });
    }

    function showForegroundNotification(payload) {
        if (Notification.permission !== 'granted') return;

        const title = payload?.notification?.title || 'Notification';
        const options = {
            body: payload?.notification?.body || '',
            icon: '/favicon.svg',
            badge: '/favicon.svg',
            data: payload?.data || {},
            tag: payload?.data?.type || 'fcm-foreground',
        };

        new Notification(title, options);
    }

    async function setup() {
        try {
            await loadFirebaseCompat();

            if (!firebase.apps.length) {
                firebase.initializeApp(config);
            }

            const swRegistration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
            const permission = Notification.permission === 'default'
                ? await Notification.requestPermission()
                : Notification.permission;

            if (permission !== 'granted') return;

            const messaging = firebase.messaging();
            let token = null;
            try {
                token = await messaging.getToken({ serviceWorkerRegistration: swRegistration });
            } catch (error) {
                console.warn('[FCM] getToken with SW registration failed, retrying:', error);
                token = await messaging.getToken();
            }

            if (token) {
                await registerToken(token);
            }

            // Ensure logout removes token from DB + browser push registration.
            const logoutForms = document.querySelectorAll('form[action$="/logout"]');
            logoutForms.forEach((form) => {
                form.addEventListener('submit', async () => {
                    try {
                        await unregisterToken();
                        if (token) {
                            await messaging.deleteToken(token).catch(() => {});
                        }
                        const activeNotifications = await swRegistration.getNotifications();
                        activeNotifications.forEach((notification) => notification.close());
                    } catch (e) {
                        console.warn('[FCM] Logout cleanup warning:', e);
                    }
                });
            });

            messaging.onMessage((payload) => {
                showForegroundNotification(payload);
            });
        } catch (error) {
            console.error('[FCM] Setup failed:', error);
        }
    }

    window.addEventListener('load', () => {
        setTimeout(setup, 800);
    });
})();
