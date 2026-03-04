// Firebase Messaging Service Worker
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');
importScripts('/firebase-config-runtime.js');

const firebaseConfig = self.FIREBASE_CONFIG || {};
const requiredKeys = ['apiKey', 'projectId', 'messagingSenderId', 'appId'];
const missingKeys = requiredKeys.filter((k) => !firebaseConfig[k]);

if (missingKeys.length === 0) {
    try {
        firebase.initializeApp(firebaseConfig);
    } catch (e) {
        console.error('[firebase-messaging-sw.js] Firebase init failed:', e);
    }
} else {
    console.warn('[firebase-messaging-sw.js] Missing Firebase config keys:', missingKeys.join(', '));
}

let messaging = null;
if (firebase.apps.length) {
    try {
        messaging = firebase.messaging();
    } catch (e) {
        console.error('[firebase-messaging-sw.js] Messaging init failed:', e);
    }
}

// Handle background messages
if (messaging) {
    messaging.onBackgroundMessage((payload) => {
        const notificationTitle = payload?.notification?.title || payload?.data?.title || 'Notification';
        const notificationOptions = {
            body: payload?.notification?.body || payload?.data?.body || '',
            icon: '/favicon.svg',
            badge: '/favicon.svg',
            data: payload?.data || {},
            requireInteraction: true,
            tag: payload?.data?.type || 'fcm-background',
            actions: [
                {
                    action: 'view',
                    title: 'View Order'
                },
                {
                    action: 'close',
                    title: 'Close'
                }
            ]
        };

        // Always show explicitly from SW to support background/locked states consistently.
        self.registration.showNotification(notificationTitle, notificationOptions);
    });
}

// Fallback for browsers/devices where Firebase background handler is unreliable.
self.addEventListener('push', (event) => {
    try {
        const payload = event.data ? event.data.json() : {};
        const title = payload?.notification?.title || payload?.data?.title || 'Notification';
        const options = {
            body: payload?.notification?.body || payload?.data?.body || '',
            icon: '/favicon.svg',
            badge: '/favicon.svg',
            data: payload?.data || {},
            requireInteraction: true,
            tag: payload?.data?.type || 'fcm-push-fallback',
        };

        event.waitUntil(self.registration.showNotification(title, options));
    } catch (e) {
        // Ignore malformed payloads to avoid crashing SW push handler.
    }
});

// Handle notification click
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const data = event.notification?.data || {};
    const userRole = data.user_role || 'waiter';
    let targetUrl = data.link || '/';

    if (!data.link) {
        if (userRole === 'waiter') {
            targetUrl = '/waiter/orders';
        } else if (userRole === 'kitchen') {
            targetUrl = '/kitchen/orders';
        } else if (userRole === 'admin' || userRole === 'super-admin') {
            targetUrl = '/admin/orders';
        }
    }

    event.waitUntil(clients.openWindow(targetUrl));
});
