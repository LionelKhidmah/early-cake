import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.js-cart-update-form').forEach((form) => {
        const input = form.querySelector('[name="quantity"]');
	
	const minusButton = form.querySelector('[data-quantity-minus]');
const plusButton = form.querySelector('[data-quantity-plus]');

const updateButtonStates = () => {
    const min = Number(input.min) || 1;
    const max = Number(input.max) || 20;
    const value = Number(input.value);

    if (minusButton) minusButton.disabled = value <= min;
    if (plusButton) plusButton.disabled = value >= max;
};

minusButton?.addEventListener('click', () => {
    const min = Number(input.min) || 1;
    const value = Number(input.value);

    if (value > min) {
        input.value = value - 1;
        input.dispatchEvent(new Event('change', { bubbles: true }));
    }

    updateButtonStates();
});

plusButton?.addEventListener('click', () => {
    const max = Number(input.max) || 20;
    const value = Number(input.value);

    if (value < max) {
        input.value = value + 1;
        input.dispatchEvent(new Event('change', { bubbles: true }));
    }

    updateButtonStates();
});

input.addEventListener('change', updateButtonStates);
updateButtonStates();

        if (!input) return;

        input.addEventListener('change', async () => {
            if (!input.checkValidity()) {
                input.reportValidity();
                return;
            }

            const key = form.querySelector('[name="key"]')?.value;
            const token = form.querySelector('[name="_token"]')?.value;
            const previousValue = input.dataset.savedValue ?? input.defaultValue;

            if (!key || !token) {
                Swal.fire('Terjadi kesalahan', 'Form keranjang belum lengkap.', 'error');
                return;
            }

            input.disabled = true;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: new URLSearchParams({
                        _token: token,
                        key: key,
                        quantity: input.value,
                    }),
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(
                        data.message ||
                        data.errors?.quantity?.[0] ||
                        'Jumlah produk gagal diperbarui.'
                    );
                }

                const subtotal = document.getElementById(`cart-subtotal-${key}`);
                const total = document.getElementById('cart-total');

                if (subtotal) subtotal.textContent = `Rp${data.subtotal}`;
                if (total) total.textContent = `Rp${data.total}`;

                ['cart-count', 'cart-count-mobile'].forEach((id) => {
                    const element = document.getElementById(id);
                    if (element) element.textContent = data.cart_count;
                });

                input.dataset.savedValue = String(data.quantity);
                input.defaultValue = String(data.quantity);

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Keranjang diperbarui',
                    showConfirmButton: false,
                    timer: 1200,
                });
            } catch (error) {
                input.value = previousValue;

                Swal.fire({
                    icon: 'warning',
                    title: 'Belum berhasil',
                    text: error.message || 'Silakan coba lagi.',
                    confirmButtonText: 'Oke',
                });
            } finally {
                input.disabled = false;
            }
        });
    });

    document.querySelectorAll('.js-cart-remove-form').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const result = await Swal.fire({
                icon: 'question',
                title: 'Hapus produk?',
                text: 'Produk ini akan dihapus dari keranjang.',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#B94D69',
                reverseButtons: true,
            });

            if (result.isConfirmed) form.submit();
        });
    });
});