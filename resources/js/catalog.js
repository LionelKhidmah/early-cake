document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('#search');
    const categorySelect = document.querySelector('#category');
    const sortSelect = document.querySelector('#sort');

    // Terapkan pencarian, kategori, dan pengurutan
    // melalui parameter URL yang diproses oleh Laravel.
    const form = searchInput?.closest('form');

    if (!form) return;

    categorySelect?.addEventListener('change', () => {
        form.requestSubmit();
    });

    sortSelect?.addEventListener('change', () => {
        form.requestSubmit();
    });
});