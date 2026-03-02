// Simple FCM initialization without modules
console.log('FCM Init script loaded');

// Check if service worker is supported
if ('serviceWorker' in navigator) {
    console.log('Service Worker supported');
    
    // Register service worker
    navigator.serviceWorker.register('/firebase-messaging-sw.js')
        .then((registration) => {
            console.log('Service Worker registered:', registration);
        })
        .catch((error) => {
            console.error('Service Worker registration failed:', error);
        });
} else {
    console.warn('Service Worker not supported in this browser');
}

// Request notification permission
function requestNotificationPermission() {
    console.log('Requesting notification permission...');
    
    if (!('Notification' in window)) {
        console.error('This browser does not support notifications');
        return;
    }

    if (Notification.permission === 'granted') {
        console.log('Notification permission already granted');
        initializeFirebase();
    } else if (Notification.permission !== 'denied') {
        Notification.requestPermission().then((permission) => {
            console.log('Notification permission:', permission);
            if (permission === 'granted') {
                initializeFirebase();
            }
        });
    } else {
        console.warn('Notification permission denied');
    }
}

// Initialize Firebase (will be loaded from CDN)
function initializeFirebase() {
    console.log('Initializing Firebase...');
    
    // Check if Firebase is loaded
    if (typeof firebase === 'undefined') {
        console.error('Firebase not loaded. Loading from CDN...');
        loadFirebaseScripts();
        return;
    }
    
    // Firebase is already loaded, initialize
    setupFirebaseMessaging();
}

// Load Firebase scripts from CDN
function loadFirebaseScripts() {
    const scripts = [
        'https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js',
        'https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js'
    ];
    
    let loaded = 0;
    scripts.forEach(src => {
        const script = document.createElement('script');
        script.src = src;
        script.onload = () => {
            loaded++;
            if (loaded === scripts.length) {
                setupFirebaseMessaging();
            }
        };
        document.head.appendChild(script);
    });
}

// Setup Firebase Messaging
function setupFirebaseMessaging() {
    console.log('Setting up Firebase Messaging...');
    
    try {
        // Initialize Firebase
        if (!firebase.apps.length) {
            firebase.initializeApp({
                apiKey: "AIzaSyB_y-HBjvwVN1T3spTCzqzksZrdhuzaFCw",
                authDomain: "foodcourt-16cae.firebaseapp.com",
                projectId: "foodcourt-16cae",
                storageBucket: "foodcourt-16cae.firebasestorage.app",
                messagingSenderId: "934263242172",
                appId: "1:934263242172:web:26d5fe22562e980e8bffb9",
                measurementId: "G-06WMNFNF2L"
            });
        }
        
        const messaging = firebase.messaging();
        
        // Get FCM token
        messaging.getToken()
            .then((token) => {
                if (token) {
                    console.log('FCM Token:', token);
                    registerTokenWithBackend(token);
                } else {
                    console.log('No FCM token available');
                }
            })
            .catch((error) => {
                console.error('Error getting FCM token:', error);
            });
        
        // Handle foreground messages
        messaging.onMessage((payload) => {
            console.log('🔔 Foreground message received:', payload);
            showNotification(payload);
            
            // Also play a sound
            try {
                const audio = new Audio('/notification.mp3');
                audio.play().catch(e => console.log('Could not play sound:', e));
            } catch (e) {
                console.log('Audio not available');
            }
        });
        
    } catch (error) {
        console.error('Error setting up Firebase:', error);
    }
}

// Register token with backend
function registerTokenWithBackend(token) {
    console.log('Registering token with backend...');
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }
    
    fetch('/api/fcm/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        credentials: 'same-origin',
        body: JSON.stringify({ fcm_token: token })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('✅ Token registered successfully:', data);
        // Show a subtle notification instead of alert
        if (Notification.permission === 'granted') {
            new Notification('Notifications Enabled', {
                body: 'You will receive order notifications',
                icon: '/favicon.svg',
                tag: 'fcm-setup'
            });
        }
    })
    .catch(error => {
        console.error('❌ Error registering token:', error);
    });
}

// Show notification
function showNotification(payload) {
    console.log('📢 Showing notification:', payload);
    
    const title = payload.notification?.title || 'New Notification';
    const options = {
        body: payload.notification?.body || '',
        icon: '/favicon.svg',
        badge: '/favicon.svg',
        data: payload.data,
        requireInteraction: true,
        tag: 'order-notification'
    };
    
    console.log('Notification permission:', Notification.permission);
    
    if (Notification.permission === 'granted') {
        const notification = new Notification(title, options);
        console.log('✅ Notification created:', notification);
        
        notification.onclick = function(event) {
            console.log('Notification clicked');
            event.preventDefault();
            window.focus();
            notification.close();
        };
    } else {
        console.warn('⚠️ Notification permission not granted:', Notification.permission);
    }
}

// Auto-request permission for staff users
window.addEventListener('load', () => {
    const userRole = document.body.dataset.userRole;
    console.log('User role:', userRole);
    
    if (userRole === 'waiter' || userRole === 'kitchen' || userRole === 'admin') {
        setTimeout(() => {
            requestNotificationPermission();
        }, 2000);
    }
});
