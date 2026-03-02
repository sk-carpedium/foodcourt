// Firebase Messaging Service Worker
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

// Initialize Firebase
firebase.initializeApp({
    apiKey: "AIzaSyB_y-HBjvwVN1T3spTCzqzksZrdhuzaFCw",
    authDomain: "foodcourt-16cae.firebaseapp.com",
    projectId: "foodcourt-16cae",
    storageBucket: "foodcourt-16cae.firebasestorage.app",
    messagingSenderId: "934263242172",
    appId: "1:934263242172:web:26d5fe22562e980e8bffb9",
    measurementId: "G-06WMNFNF2L"
});

const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/favicon.svg',
        badge: '/favicon.svg',
        data: payload.data,
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

// Handle notification click
self.addEventListener('notificationclick', (event) => {
    console.log('[Service Worker] Notification click received.', event);
    
    event.notification.close();

    if (event.action === 'view') {
        const orderId = event.notification.data.order_id;
        const userRole = event.notification.data.user_role || 'waiter';
        
        let url = '/';
        if (userRole === 'waiter') {
            url = '/waiter/orders';
        } else if (userRole === 'kitchen') {
            url = '/kitchen/orders';
        } else if (userRole === 'admin') {
            url = '/admin/orders';
        }
        
        event.waitUntil(
            clients.openWindow(url)
        );
    }
});
