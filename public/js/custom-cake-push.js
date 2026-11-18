document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('enable-push-notification');

    if (!button) return;

    const publicKey = button.dataset.publicKey;
    const subscribeUrl = button.dataset.subscribeUrl;
    const csrfToken = button.dataset.csrfToken;
    const serviceWorkerUrl = button.dataset.swUrl;
    const serviceWorkerScope = button.dataset.swScope;

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);

        return Uint8Array.from(
            rawData,
            char => char.charCodeAt(0)
        );
    }

    button.addEventListener('click', async () => {
        try {
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                alert('Browser ini belum mendukung push notification.');
                return;
            }

            if (!publicKey) {
                alert('VAPID Public Key belum dikonfigurasi.');
                return;
            }

            if (!serviceWorkerUrl || !serviceWorkerScope) {
                throw new Error('URL Service Worker belum dikonfigurasi.');
            }

            button.disabled = true;
            button.textContent = 'Mengaktifkan...';

            const permission = await Notification.requestPermission();

            if (permission !== 'granted') {
                alert('Izin notifikasi belum diberikan.');
                return;
            }

            const registration = await navigator.serviceWorker.register(
                serviceWorkerUrl,
                {
                    scope: serviceWorkerScope
                }
            );

            await navigator.serviceWorker.ready;

            let subscription = await registration.pushManager.getSubscription();

            if (!subscription) {
                subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array(publicKey)
                });
            }

            const response = await fetch(subscribeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(subscription.toJSON())
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(
                    result.message || 'Gagal mendaftarkan perangkat.'
                );
            }

            button.textContent = 'Notifikasi Aktif';
            button.disabled = true;

            alert('Notifikasi Early Cake berhasil diaktifkan!');

        } catch (error) {
            console.error('Push notification error:', error);

            alert(
                error.message || 'Gagal mengaktifkan notifikasi.'
            );

        } finally {
            if (button.textContent === 'Mengaktifkan...') {
                button.disabled = false;
                button.textContent = 'Aktifkan Notifikasi';
            }
        }
    });
});
