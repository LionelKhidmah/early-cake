self.addEventListener('push', function (event) {
    let data = {
        title: 'Early Cake',
        body: 'Ada pembaruan pada pesanan custom cake kamu.',
        url: '/'
    };

    if (event.data) {
        try {
            data = {
                ...data,
                ...event.data.json()
            };
        } catch (error) {
            data.body = event.data.text();
        }
    }

    event.waitUntil(
        self.registration.showNotification(data.title, {
            body: data.body,
            icon: '/early-cake/public/images/logo/logo.jpg',
            badge: '/early-cake/public/images/logo/logo.jpg',
            data: {
                url: data.url || '/'
            },
            vibrate: [200, 100, 200]
        })
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const targetUrl = new URL(
        event.notification.data?.url || '/',
        self.location.origin
    ).href;

    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        }).then(function (windowClients) {
            for (const client of windowClients) {
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }

            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
