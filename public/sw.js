self.addEventListener('push', function (event) {
    let data = {};

    try {
        data = event.data ? event.data.json() : {};
    } catch (e) {
        data = {
            title: 'Foodcourt',
            body: 'You have a new notification.',
            url: '/dashboard',
            tag: 'foodcourt-notification',
        };
    }

    const title = data.title || 'Foodcourt';
    const options = {
        body: data.body || 'You have a new notification.',
        icon: '/favicon.ico',
        badge: '/favicon.ico',
        tag: data.tag || 'foodcourt-notification',
        data: {
            url: data.url || '/dashboard',
        },
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const targetUrl = (event.notification.data && event.notification.data.url) || '/dashboard';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            for (const client of clientList) {
                if ('focus' in client) {
                    client.navigate(targetUrl);
                    return client.focus();
                }
            }

            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }

            return null;
        })
    );
});
