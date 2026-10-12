import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById('site-header');
    const toggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('mobile-menu');
    const iconOpen = document.getElementById('icon-open');
    const iconClose = document.getElementById('icon-close');

    // Buka / tutup menu mobile
    const setMenu = (open) => {
        if (!toggle || !menu) return;
        menu.classList.toggle('hidden', !open);
        iconOpen.classList.toggle('hidden', open);
        iconClose.classList.toggle('hidden', !open);
        toggle.setAttribute('aria-expanded', String(open));
        toggle.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
    };

    toggle?.addEventListener('click', () => {
        setMenu(toggle.getAttribute('aria-expanded') !== 'true');
    });

    // Tutup menu dengan tombol Escape atau saat layar membesar
    document.addEventListener('keydown', (e) => e.key === 'Escape' && setMenu(false));
    window.matchMedia('(min-width: 768px)').addEventListener('change', (e) => e.matches && setMenu(false));

    // Bayangan navbar saat halaman di-scroll
    const onScroll = () => header?.classList.toggle('shadow-md', window.scrollY > 8);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    // Jumlah keranjang (disimpan di localStorage)
    const cartCount = document.getElementById('cart-count');
    const updateCart = () => {
        try {
            const items = JSON.parse(localStorage.getItem('early_cake_cart') || '[]');
            if (cartCount) cartCount.textContent = items.length;
        } catch {
            if (cartCount) cartCount.textContent = '0';
        }
    };
    updateCart();
    window.addEventListener('storage', updateCart);
    window.addEventListener('cart:updated', updateCart);
});