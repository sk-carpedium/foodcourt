// FCM Handler for Web Push Notifications
import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js';
import { getMessaging, getToken, onMessage } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js';

// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyB_y-HBjvwVN1T3spTCzqzksZrdhuzaFCw",
    authDomain: "foodcourt-16cae.firebaseapp.com",
    projectId: "foodcourt-16cae",
    storageBucket: "foodcourt-16cae.firebasestorage.app",
    messagingSenderId: "934263242172",
    appId: "1:934263242172:web:26d5fe22562e980e8bffb9",
    measurementId: "G-06WMNFNF2L"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

// Request notification permission and get token
export async function requestNotificationPermission() {
    try {
        const permission = await Notification.requestPermission();
        
        if (permission === 'granted') {
            console.log('Notification permission granted.');
            
            // Get FCM token (without VAPID - using legacy API)
            const token = await getToken(messaging);
            
            if (token) {
                console.log('FCM Token:', token);
                
                // Send token to backend
                await registerFCMToken(token);
                
                return token;
            } else {
                console.log('No registration token available.');
            }
        } else {
            console.log('Notification permission denied.');
        }
    } catch (error) {
        console.error('Error getting notification permission:', error);
    }
}

// Register FCM token with backend
async function registerFCMToken(token) {
    try {
        const response = await fetch('/api/fcm/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${getAuthToken()}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
            body: JSON.stringify({ fcm_token: token })
        });
        
        const data = await response.json();
        console.log('FCM token registered:', data);
    } catch (error) {
        console.error('Error registering FCM token:', error);
    }
}

// Get auth token from localStorage or cookie
function getAuthToken() {
    // Adjust this based on your authentication method
    return localStorage.getItem('auth_token') || '';
}

// Handle foreground messages
onMessage(messaging, (payload) => {
    console.log('Message received in foreground:', payload);
    
    // Show notification
    showNotification(payload);
    
    // Play sound
    playNotificationSound();
    
    // Update UI if needed
    updateOrderCount();
});

// Show browser notification
function showNotification(payload) {
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/favicon.svg',
        badge: '/favicon.svg',
        tag: payload.data.order_id,
        requireInteraction: true,
        data: payload.data
    };
    
    if (Notification.permission === 'granted') {
        new Notification(notificationTitle, notificationOptions);
    }
}

// Play notification sound
function playNotificationSound() {
    const audio = new Audio('/sounds/notification.mp3');
    audio.play().catch(e => console.log('Could not play sound:', e));
}

// Update order count badge
function updateOrderCount() {
    // Trigger Livewire refresh or update badge
    if (window.Livewire) {
        window.Livewire.dispatch('refresh-orders');
    }
}

// Initialize on page load
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/firebase-messaging-sw.js')
        .then((registration) => {
            console.log('Service Worker registered:', registration);
        })
        .catch((error) => {
            console.error('Service Worker registration failed:', error);
        });
}

// Auto-request permission for authenticated users
window.addEventListener('load', () => {
    // Check if user is authenticated and has waiter/kitchen role
    const userRole = document.body.dataset.userRole;
    
    if (userRole === 'waiter' || userRole === 'kitchen' || userRole === 'admin') {
        // Request permission after 2 seconds
        setTimeout(() => {
            requestNotificationPermission();
        }, 2000);
    }
});
