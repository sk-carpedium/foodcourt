// Minimal, robust FCM setup for staff dashboards
(function () {
    const allowedRoles = ['waiter', 'kitchen'];
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
    const permissionPromptKey = 'fcm_permission_prompted_at';
    const permissionPromptCooldownMs = 24 * 60 * 60 * 1000;

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

    function canPromptForPermission() {
        if (Notification.permission !== 'default') return false;
        try {
            const lastPromptAt = Number(localStorage.getItem(permissionPromptKey) || 0);
            return !lastPromptAt || (Date.now() - lastPromptAt > permissionPromptCooldownMs);
        } catch (_) {
            return true;
        }
    }

    function markPermissionPrompted() {
        try {
            localStorage.setItem(permissionPromptKey, String(Date.now()));
        } catch (_) {
            // Ignore storage errors and continue normal flow.
        }
    }

    function waitForFirstUserGesture() {
        return new Promise((resolve) => {
            const events = ['click', 'keydown', 'touchstart'];
            const onGesture = () => {
                events.forEach((eventName) => {
                    window.removeEventListener(eventName, onGesture, true);
                });
                resolve();
            };

            events.forEach((eventName) => {
                window.addEventListener(eventName, onGesture, { once: true, capture: true, passive: true });
            });
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

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistration().then((registration) => {
                if (registration) {
                    registration.showNotification(title, options);
                    return;
                }

                new Notification(title, options);
            }).catch(() => {
                new Notification(title, options);
            });
            return;
        }

        new Notification(title, options);
    }

    async function setup() {
        try {
            await loadFirebaseCompat();

            if (!firebase.apps.length) {
                firebase.initializeApp(config);
            }

            const swRegistration = await navigator.serviceWorker.register('/firebase-messaging-sw.js', {
                scope: '/',
            });
            // Wait for an active service worker before requesting a push subscription.
            await navigator.serviceWorker.ready;

            let permission = Notification.permission;
            if (permission === 'denied') return;

            if (permission === 'default') {
                if (!canPromptForPermission()) return;

                // Ask only after an intentional user gesture to avoid abusive prompt heuristics.
                await waitForFirstUserGesture();
                if (Notification.permission !== 'default') {
                    permission = Notification.permission;
                } else {
                    markPermissionPrompted();
                    permission = await Notification.requestPermission();
                }
            }

            if (permission !== 'granted') return;

            const messaging = firebase.messaging();
            const tokenOptions = {
                serviceWorkerRegistration: swRegistration,
            };
            if (config.vapidKey) {
                tokenOptions.vapidKey = config.vapidKey;
            }

            let token = await messaging.getToken(tokenOptions);

            if (token) {
                await registerToken(token);
            }

            const syncToken = async () => {
                if (Notification.permission !== 'granted') return;
                try {
                    const freshToken = await messaging.getToken(tokenOptions);
                    if (freshToken && freshToken !== token) {
                        token = freshToken;
                        await registerToken(freshToken);
                    }
                } catch (error) {
                    console.warn('[FCM] Token refresh failed:', error);
                }
            };

            window.addEventListener('focus', syncToken);
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    syncToken();
                }
            });

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
