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
        const notificationTitle = payload?.notification?.title || 'Notification';
        const notificationOptions = {
            body: payload?.notification?.body || '',
            icon: '/favicon.svg',
            badge: '/favicon.svg',
            data: payload?.data || {},
            requireInteraction: true,
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

        self.registration.showNotification(notificationTitle, notificationOptions);
    });
}

// Handle notification click
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const userRole = event.notification?.data?.user_role || 'waiter';
    let url = '/';
    if (userRole === 'waiter') {
        url = '/waiter/orders';
    } else if (userRole === 'kitchen') {
        url = '/kitchen/orders';
    } else if (userRole === 'admin' || userRole === 'super-admin') {
        url = '/admin/orders';
    }
    
    event.waitUntil(clients.openWindow(url));
});
