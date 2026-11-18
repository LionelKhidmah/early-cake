import {
    DEFAULT_TOP_COLOR,
    DEFAULT_SIDE_COLOR,
    DEFAULT_MESSAGE_COLOR,
    FONT_NAME,
    FONT_MIN,
    FONT_MAX,
    clamp,
    fx,
    escapeSvg,
    isValidHex,
    normalizeHex,
    createCakeRenderer,
    ensureCakeFont,
    initStaticCakePreviews,
} from './cake-renderer.js';

document.addEventListener('DOMContentLoaded', () => {
    // Halaman admin: gambar preview statis dari data-config lalu selesai.
    initStaticCakePreviews();

    const form = document.getElementById('custom-cake-form');
    if (!form) return;

    const preview = document.getElementById('cake-preview');

    const sizeInput = document.getElementById('cake-size');
    const flavorInput = document.getElementById('cake-flavor');

    const cakeColorInput = document.getElementById('cake-color');
    const cakeColorHexInput = document.getElementById('cake-color-hex');

    const sideColorInput = document.getElementById('cake-side-color');
    const sideColorHexInput = document.getElementById('cake-side-color-hex');

    const messageInput = document.getElementById('cake-message');
    const messageColorInput = document.getElementById('cake-message-color');
    const messageColorHexInput = document.getElementById('cake-message-color-hex');

    const messageCounter = document.getElementById('cake-message-counter');
    const messageHint = document.getElementById('cake-message-hint');

    const configInput = document.getElementById('design-config');

    const referenceInput = document.getElementById('reference-image');
    const referencePreview = document.getElementById('reference-preview');

    // --------------------------------------------------
    // STATE
    // --------------------------------------------------

    let activeView = 'front';

    // Pengaturan ukuran tulisan (selisih dari ukuran default), bukan zoom preview.
    let fontSizeAdjustment = 0;

    let topTextPosition = { x: 0, y: 0 };

    // Renderer bersama (juga dipakai halaman admin). Seluruh geometri dan
    // gambar SVG ada di cake-renderer.js; di sini hanya data dari form.
    const {
        getTextLayout,
        renderFront,
        renderTop,
        isTopPositionValid,
        constrainTopPosition,
    } = createCakeRenderer({
        shape: () => getSelectedShape(),
        topColor: () => getTopColor(),
        sideColor: () => getSideColor(),
        messageColor: () => getMessageColor(),
        decorations: () => getSelectedDecorations(),
        message: () => getSelectedMessage(),
        requestedFontSize: () => getRequestedFontSize(),
        topTextPosition: () => topTextPosition,
    });

    // --------------------------------------------------
    // HELPER UMUM
    // --------------------------------------------------







    function getTopColor() {
        return normalizeHex(cakeColorInput?.value, DEFAULT_TOP_COLOR);
    }

    function getSideColor() {
        return normalizeHex(sideColorInput?.value, DEFAULT_SIDE_COLOR);
    }

    function getMessageColor() {
        return normalizeHex(messageColorInput?.value, DEFAULT_MESSAGE_COLOR);
    }

    function getSelectedShape() {
        const value = form.querySelector('[name="shape"]:checked')?.value;

        return value === 'Persegi' || value === 'Hati' ? value : 'Bulat';
    }

    function getSelectedDecorations() {
        return Array.from(
            form.querySelectorAll('[name="decorations[]"]:checked')
        ).map(input => input.value);
    }

    function getSelectedSize() {
        return sizeInput?.value || '';
    }

    function getSelectedFlavor() {
        return flavorInput?.value || '';
    }

    function getSelectedMessage() {
        return messageInput?.value || '';
    }

    // --------------------------------------------------
    // PENGATURAN TULISAN
    // --------------------------------------------------

    function getMessageSettings() {
        const sizeText = getSelectedSize().toLowerCase();
        const match = sizeText.match(/(\d+(?:[.,]\d+)?)\s*cm/);

        const sizeNumber = match
            ? Number(match[1].replace(',', '.'))
            : null;

        if (sizeNumber && sizeNumber <= 10) {
            return { maxChars: 28, fontSize: 14 };
        }

        if (sizeNumber && sizeNumber <= 15) {
            return { maxChars: 42, fontSize: 16 };
        }

        if (sizeNumber && sizeNumber <= 18) {
            return { maxChars: 54, fontSize: 17 };
        }

        if (sizeNumber && sizeNumber <= 22) {
            return { maxChars: 66, fontSize: 18 };
        }

        return { maxChars: 70, fontSize: 18 };
    }

    // Ukuran font yang diminta pengguna (belum dibatasi area kue).
    function getRequestedFontSize() {
        return clamp(
            getMessageSettings().fontSize + fontSizeAdjustment,
            FONT_MIN,
            FONT_MAX
        );
    }

    function updateMessageLimit() {
        if (!messageInput) return;

        const settings = getMessageSettings();

        messageInput.maxLength = settings.maxChars;

        if (messageInput.value.length > settings.maxChars) {
            messageInput.value = messageInput.value.slice(0, settings.maxChars);
        }

        if (messageCounter) {
            messageCounter.textContent =
                `${messageInput.value.length}/${settings.maxChars} karakter`;
        }

        if (messageHint) {
            messageHint.textContent =
                `Maksimal ${settings.maxChars} karakter. Tulisan akan menyesuaikan bidang kue.`;
        }
    }

    // --------------------------------------------------
    // FONT
    // --------------------------------------------------


    // --------------------------------------------------
    // KONTROL UKURAN TULISAN A- / A+
    // --------------------------------------------------

    function createFontControls() {
        document.getElementById('cake-message-zoom-controls')?.remove();

        // Jika Blade sudah menyediakan markup kontrol, cukup dihubungkan.
        if (!document.getElementById('cake-message-font-controls')) {
            injectFontControls();
        }

        bindFontControls();
    }

    // Cadangan: dipakai hanya jika Blade tidak memuat kontrol ukuran tulisan.
    function injectFontControls() {
        const tab = document.querySelector('[data-preview-tab]');
        if (!tab || !preview) return;

        const tabContainer = tab.parentElement;
        if (!tabContainer) return;

        const controls = document.createElement('div');

        controls.id = 'cake-message-font-controls';
        controls.className =
            'mt-3 flex flex-col items-center gap-1 text-sm text-[#765D55]';

        controls.innerHTML = `
            <div class="flex flex-wrap items-center justify-center gap-3">
                <span class="text-xs font-medium">Ukuran tulisan</span>

                <button
                    type="button"
                    id="cake-message-font-minus"
                    class="flex h-9 min-w-9 items-center justify-center rounded-full border border-rose-200 bg-white px-2 text-sm font-semibold transition hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-40"
                    aria-label="Perkecil tulisan"
                >A−</button>

                <span
                    id="cake-message-font-value"
                    class="min-w-12 text-center text-xs font-semibold"
                >${getRequestedFontSize()} px</span>

                <button
                    type="button"
                    id="cake-message-font-plus"
                    class="flex h-9 min-w-9 items-center justify-center rounded-full border border-rose-200 bg-white px-2 text-sm font-semibold transition hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-40"
                    aria-label="Perbesar tulisan"
                >A+</button>

                <button
                    type="button"
                    id="cake-message-font-reset"
                    class="rounded-full px-2 py-1 text-xs font-medium text-rose-500 transition hover:bg-rose-50"
                >Default</button>
            </div>

            <p
                id="cake-message-font-note"
                class="hidden text-center text-[11px] text-[#A58B80]"
            ></p>
        `;

        tabContainer.insertAdjacentElement('afterend', controls);
    }

    function bindFontControls() {
        const root = document.getElementById('cake-message-font-controls');

        if (!root || root.dataset.bound === '1') return;

        root.dataset.bound = '1';

        document.getElementById('cake-message-font-minus')
            ?.addEventListener('click', () => {
                const layout = getTextLayout(activeView);
                const next = clamp(
                    Math.floor(layout.fontSize) - 1,
                    FONT_MIN,
                    FONT_MAX
                );

                fontSizeAdjustment = next - getMessageSettings().fontSize;
                renderPreview();
            });

        document.getElementById('cake-message-font-plus')
            ?.addEventListener('click', () => {
                const layout = getTextLayout(activeView);
                const next = Math.floor(layout.fontSize) + 1;

                if (next > FONT_MAX) return;

                // Hanya naik jika ukuran baru masih muat pada kue.
                if (getTextLayout(activeView, next).fontSize < next) return;

                fontSizeAdjustment = next - getMessageSettings().fontSize;
                renderPreview();
            });

        document.getElementById('cake-message-font-reset')
            ?.addEventListener('click', () => {
                fontSizeAdjustment = 0;
                renderPreview();
            });
    }

    function updateFontControls(layout) {
        const label = document.getElementById('cake-message-font-value');
        const minus = document.getElementById('cake-message-font-minus');
        const plus = document.getElementById('cake-message-font-plus');
        const note = document.getElementById('cake-message-font-note');

        const current = layout.fontSize;
        const hasText = layout.lines.length > 0;

        if (label) label.textContent = `${current} px`;

        if (minus) minus.disabled = current <= FONT_MIN;

        if (plus) {
            plus.disabled =
                current >= FONT_MAX ||
                getTextLayout(activeView, current + 1).fontSize < current + 1;
        }

        if (note) {
            const reduced = hasText && layout.fontSize < layout.requested;

            note.classList.toggle('hidden', !reduced);
            note.textContent = reduced
                ? `Ukuran disesuaikan agar tulisan tetap muat pada permukaan atas kue.`
                : '';
        }
    }

    // --------------------------------------------------
    // DRAG TULISAN TAMPAK ATAS
    // --------------------------------------------------



    function attachTopMessageDrag() {
        const svg = preview?.querySelector('svg');
        const draggable = preview?.querySelector('#draggable-cake-message');

        if (!svg || !draggable) return;

        let dragging = false;
        let pointerId = null;
        let startPointer = null;
        let startPosition = null;

        function pointerToSvg(event) {
            const point = svg.createSVGPoint();

            point.x = event.clientX;
            point.y = event.clientY;

            const matrix = svg.getScreenCTM();

            if (!matrix) return null;

            return point.matrixTransform(matrix.inverse());
        }

        function getValidPosition(targetX, targetY, fromX, fromY) {
            if (isTopPositionValid(targetX, targetY)) {
                return { x: targetX, y: targetY };
            }

            let low = 0;
            let high = 1;

            for (let i = 0; i < 24; i++) {
                const middle = (low + high) / 2;

                const testX = fromX + (targetX - fromX) * middle;
                const testY = fromY + (targetY - fromY) * middle;

                if (isTopPositionValid(testX, testY)) {
                    low = middle;
                } else {
                    high = middle;
                }
            }

            return {
                x: fromX + (targetX - fromX) * low,
                y: fromY + (targetY - fromY) * low,
            };
        }

        draggable.addEventListener('pointerdown', event => {
            if (event.button !== undefined && event.button !== 0) return;

            const pointer = pointerToSvg(event);
            if (!pointer) return;

            dragging = true;
            pointerId = event.pointerId;

            startPointer = pointer;
            startPosition = { ...topTextPosition };

            draggable.setPointerCapture?.(pointerId);
            draggable.style.cursor = 'grabbing';

            event.preventDefault();
        });

        draggable.addEventListener('pointermove', event => {
            if (!dragging || event.pointerId !== pointerId) return;

            const pointer = pointerToSvg(event);
            if (!pointer) return;

            const targetX = startPosition.x + (pointer.x - startPointer.x);
            const targetY = startPosition.y + (pointer.y - startPointer.y);

            const valid = getValidPosition(
                targetX,
                targetY,
                topTextPosition.x,
                topTextPosition.y
            );

            topTextPosition = valid;

            draggable.setAttribute(
                'transform',
                `translate(${fx(valid.x)} ${fx(valid.y)})`
            );

            event.preventDefault();
        });

        function finishDrag(event) {
            if (!dragging) return;
            if (event && event.pointerId !== pointerId) return;

            dragging = false;
            draggable.style.cursor = 'grab';

            try {
                if (pointerId !== null) {
                    draggable.releasePointerCapture?.(pointerId);
                }
            } catch (error) {
                // Pointer capture mungkin sudah dilepas browser.
            }

            pointerId = null;
            startPointer = null;
            startPosition = null;

            updateDesignConfig();
        }

        draggable.addEventListener('pointerup', finishDrag);
        draggable.addEventListener('pointercancel', finishDrag);

        draggable.addEventListener('lostpointercapture', () => {
            if (dragging) finishDrag();
        });
    }

    // --------------------------------------------------
    // TAB PREVIEW
    // --------------------------------------------------

    function updateTabs() {
        document.querySelectorAll('[data-preview-tab]').forEach(button => {
            const isActive = button.dataset.previewTab === activeView;

            button.classList.toggle('bg-rose-400', isActive);
            button.classList.toggle('text-white', isActive);
            button.classList.toggle('shadow-sm', isActive);
            button.classList.toggle('text-choco', !isActive);

            button.setAttribute('aria-pressed', String(isActive));
        });
    }

    // --------------------------------------------------
    // SIMPAN DESIGN CONFIG
    // --------------------------------------------------

    function updateDesignConfig() {
        if (!configInput) return;

        const settings = getMessageSettings();

        const frontLayout = getTextLayout('front');
        const topLayout = getTextLayout('top');

        const config = {
            shape: getSelectedShape(),

            cake_color: getTopColor(),
            top_color: getTopColor(),
            side_color: getSideColor(),

            size: getSelectedSize(),
            flavor: getSelectedFlavor(),

            decorations: getSelectedDecorations(),

            message: getSelectedMessage(),
            message_color: getMessageColor(),

            // Ukuran font yang dipilih pengguna (px).
            message_font_size: getRequestedFontSize(),
            message_font_size_default: settings.fontSize,

            // Ukuran font yang benar-benar tampil pada masing-masing tampak.
            message_font_size_front: frontLayout.fontSize,
            message_font_size_top: topLayout.fontSize,
            message_font_family: FONT_NAME,

            message_settings: {
                max_chars: settings.maxChars,
                max_lines: 3,
            },

            message_position_top: {
                x: fx(topTextPosition.x),
                y: fx(topTextPosition.y),
            },

            preview_view: activeView,
        };

        configInput.value = JSON.stringify(config);
    }

    function renderPreview() {
        if (!preview) return;

        updateMessageLimit();

        const layout = getTextLayout(activeView);

        if (activeView === 'top') {
            // renderTop memperbarui topContext, jadi posisi dibatasi
            // setelah konteks tersedia lalu SVG dibangun ulang bila berubah.
            let markup = renderTop(layout);
            const constrained = constrainTopPosition(topTextPosition);

            if (
                constrained.x !== topTextPosition.x ||
                constrained.y !== topTextPosition.y
            ) {
                topTextPosition = constrained;
                markup = renderTop(layout);
            }

            preview.innerHTML = markup;
            attachTopMessageDrag();
        } else {
            let markup = renderFront(layout);
            const constrained = constrainTopPosition(topTextPosition);

            if (
                constrained.x !== topTextPosition.x ||
                constrained.y !== topTextPosition.y
            ) {
                topTextPosition = constrained;
                markup = renderFront(layout);
            }

            preview.innerHTML = markup;
        }

        updateTabs();
        updateFontControls(layout);
        updateDesignConfig();
    }

    // Menandai swatch warna yang sedang dipakai.
    function markSwatches(datasetKey, color) {
        document.querySelectorAll(`[data-${datasetKey}]`).forEach(button => {
            const key = datasetKey.replace(/-([a-z])/g, (_, c) => c.toUpperCase());
            const active = String(button.dataset[key]).toUpperCase() === color;

            ['ring-2', 'ring-rose-400', 'ring-offset-2'].forEach(name =>
                button.classList.toggle(name, active)
            );

            button.setAttribute('aria-pressed', String(active));
        });
    }

    // --------------------------------------------------
    // WARNA BAGIAN ATAS
    // --------------------------------------------------

    function setCakeColor(color) {
        const safeColor = normalizeHex(color, DEFAULT_TOP_COLOR);

        if (cakeColorInput) cakeColorInput.value = safeColor;
        if (cakeColorHexInput) cakeColorHexInput.value = safeColor;

        markSwatches('color-swatch', safeColor);
        renderPreview();
    }

    cakeColorInput?.addEventListener('input', () => {
        setCakeColor(cakeColorInput.value);
    });

    cakeColorHexInput?.addEventListener('input', () => {
        const value = cakeColorHexInput.value.trim();

        if (isValidHex(value)) setCakeColor(value);
    });

    document.querySelectorAll('[data-color-swatch]').forEach(button => {
        button.addEventListener('click', () => {
            setCakeColor(button.dataset.colorSwatch);
        });
    });

    // --------------------------------------------------
    // WARNA BAGIAN SAMPING
    // --------------------------------------------------

    function setSideColor(color) {
        const safeColor = normalizeHex(color, DEFAULT_SIDE_COLOR);

        if (sideColorInput) sideColorInput.value = safeColor;
        if (sideColorHexInput) sideColorHexInput.value = safeColor;

        markSwatches('side-color-swatch', safeColor);
        renderPreview();
    }

    sideColorInput?.addEventListener('input', () => {
        setSideColor(sideColorInput.value);
    });

    sideColorHexInput?.addEventListener('input', () => {
        const value = sideColorHexInput.value.trim();

        if (isValidHex(value)) setSideColor(value);
    });

    document.querySelectorAll('[data-side-color-swatch]').forEach(button => {
        button.addEventListener('click', () => {
            setSideColor(button.dataset.sideColorSwatch);
        });
    });

    // --------------------------------------------------
    // WARNA TULISAN
    // --------------------------------------------------

    function setMessageColor(color) {
        const safeColor = normalizeHex(color, DEFAULT_MESSAGE_COLOR);

        if (messageColorInput) messageColorInput.value = safeColor;
        if (messageColorHexInput) messageColorHexInput.value = safeColor;

        markSwatches('message-color-swatch', safeColor);
        renderPreview();
    }

    messageColorInput?.addEventListener('input', () => {
        setMessageColor(messageColorInput.value);
    });

    messageColorHexInput?.addEventListener('input', () => {
        const value = messageColorHexInput.value.trim();

        if (isValidHex(value)) setMessageColor(value);
    });

    document.querySelectorAll('[data-message-color-swatch]').forEach(button => {
        button.addEventListener('click', () => {
            setMessageColor(button.dataset.messageColorSwatch);
        });
    });

    // --------------------------------------------------
    // EVENT FORM
    // --------------------------------------------------

    sizeInput?.addEventListener('change', () => {
        fontSizeAdjustment = 0;
        renderPreview();
    });

    flavorInput?.addEventListener('change', renderPreview);

    form.querySelectorAll('[name="shape"]').forEach(input => {
        input.addEventListener('change', () => {
            topTextPosition = { x: 0, y: 0 };
            renderPreview();
        });
    });

    form.querySelectorAll('[name="decorations[]"]').forEach(input => {
        input.addEventListener('change', renderPreview);
    });

    messageInput?.addEventListener('input', renderPreview);

    document.querySelectorAll('[data-preview-tab]').forEach(button => {
        button.addEventListener('click', () => {
            activeView = button.dataset.previewTab;
            renderPreview();
        });
    });

    // --------------------------------------------------
    // FOTO REFERENSI
    // --------------------------------------------------

    referenceInput?.addEventListener('change', () => {
        const file = referenceInput.files?.[0];

        if (!file) {
            referencePreview?.classList.add('hidden');
            return;
        }

        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];

        if (!allowedTypes.includes(file.type)) {
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format tidak sesuai',
                    text: 'Gunakan file JPG, PNG, atau WEBP.',
                    confirmButtonColor: '#F08FA5',
                });
            }

            referenceInput.value = '';
            referencePreview?.classList.add('hidden');
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ukuran file terlalu besar',
                    text: 'Ukuran foto maksimal 2 MB.',
                    confirmButtonColor: '#F08FA5',
                });
            }

            referenceInput.value = '';
            referencePreview?.classList.add('hidden');
            return;
        }

        const image = referencePreview?.querySelector('img');

        if (image) {
            if (image.dataset.objectUrl) {
                URL.revokeObjectURL(image.dataset.objectUrl);
            }

            const objectUrl = URL.createObjectURL(file);

            image.src = objectUrl;
            image.dataset.objectUrl = objectUrl;

            referencePreview.classList.remove('hidden');
        }
    });

    // --------------------------------------------------
    // SUBMIT
    // --------------------------------------------------

    form.addEventListener('submit', () => {
        updateMessageLimit();
        updateDesignConfig();
    });

    // --------------------------------------------------
    // LANGKAH-LANGKAH DESAIN (wizard)
    // --------------------------------------------------
    //
    // Jika Blade tidak memuat elemen [data-step-panel], bagian ini tidak
    // melakukan apa pun dan formulir tetap tampil sebagai satu halaman.

    const stepPanels = Array.from(form.querySelectorAll('[data-step-panel]'));
    const stepTabs = Array.from(form.querySelectorAll('[data-step-tab]'));

    const stepPrev = form.querySelector('[data-step-prev]');
    const stepNext = form.querySelector('[data-step-next]');
    const stepSubmit = form.querySelector('[data-step-submit]');
    const stepCounter = form.querySelector('[data-step-counter]');
    const wizard = document.getElementById('design-wizard');

    let currentStep = 1;

    function toggleClasses(element, classes, on) {
        classes.forEach(name => element?.classList.toggle(name, on));
    }

    function updateOrderSummary() {
        const summary = document.getElementById('order-summary');

        if (!summary) return;

        const decorations = getSelectedDecorations();
        const message = getSelectedMessage().trim().replace(/\s*\n\s*/g, ' / ');

        const rows = [
            ['Ukuran', getSelectedSize() || '-'],
            ['Rasa', getSelectedFlavor() || '-'],
            ['Bentuk', getSelectedShape()],
            ['Dekorasi', decorations.length ? decorations.join(', ') : 'Tanpa dekorasi'],
            ['Tulisan', message || 'Tanpa tulisan'],
        ];

        summary.innerHTML = rows.map(([label, value]) => `
            <div class="flex justify-between gap-4 py-2 text-sm">
                <dt class="text-choco-light">${escapeSvg(label)}</dt>
                <dd class="text-right font-semibold text-choco">${escapeSvg(value)}</dd>
            </div>
        `).join('');
    }

    function showStep(step, options = {}) {
        const total = stepPanels.length;

        if (!total) return;

        currentStep = clamp(step, 1, total);

        stepPanels.forEach((panel, index) => {
            panel.classList.toggle('hidden', index + 1 !== currentStep);
        });

        stepTabs.forEach((tab, index) => {
            const active = index + 1 === currentStep;

            tab.setAttribute('aria-selected', String(active));

            toggleClasses(tab, ['bg-white', 'shadow-sm'], active);

            toggleClasses(
                tab.querySelector('[data-step-num]'),
                ['bg-rose-400', 'text-white'],
                active
            );
            toggleClasses(
                tab.querySelector('[data-step-num]'),
                ['bg-white', 'text-rose-400', 'ring-1', 'ring-rose-200'],
                !active
            );
            toggleClasses(
                tab.querySelector('[data-step-label]'),
                ['text-rose-500'],
                active
            );
            toggleClasses(
                tab.querySelector('[data-step-label]'),
                ['text-choco-light'],
                !active
            );
        });

        const isLast = currentStep === total;

        stepPrev?.classList.toggle('invisible', currentStep === 1);
        stepNext?.classList.toggle('hidden', isLast);
        stepSubmit?.classList.toggle('hidden', !isLast);

        if (stepCounter) {
            stepCounter.textContent = `Langkah ${currentStep} dari ${total}`;
        }

        if (isLast) updateOrderSummary();

        if (options.scroll && wizard) {
            const top = wizard.getBoundingClientRect().top;

            if (top < 70) {
                wizard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    }

    function validateStep(step) {
        const panel = stepPanels[step - 1];

        if (!panel) return true;

        const fields = panel.querySelectorAll('input, select, textarea');

        for (const field of fields) {
            if (!field.checkValidity()) {
                field.reportValidity();

                return false;
            }
        }

        return true;
    }

    function initSteps() {
        if (!stepPanels.length) return;

        stepTabs.forEach((tab, index) => {
            tab.addEventListener('click', () => showStep(index + 1, { scroll: true }));
        });

        stepPrev?.addEventListener('click', () => {
            showStep(currentStep - 1, { scroll: true });
        });

        stepNext?.addEventListener('click', () => {
            if (validateStep(currentStep)) {
                showStep(currentStep + 1, { scroll: true });
            }
        });

        // Enter pada kolom isian pindah ke langkah berikutnya,
        // bukan mengirim formulir sebelum waktunya.
        form.addEventListener('keydown', event => {
            if (event.key !== 'Enter') return;

            const target = event.target;

            const isField = target.matches?.(
                'input:not([type="file"]):not([type="color"]):not([type="submit"]), select'
            );

            if (isField && currentStep < stepPanels.length) {
                event.preventDefault();
                stepNext?.click();
            }
        });

        // Jika ada isian wajib yang belum valid di langkah yang sedang
        // tersembunyi, buka langkah itu agar pesan validasi dapat tampil.
        form.addEventListener('invalid', event => {
            const panel = event.target.closest?.('[data-step-panel]');

            if (panel && panel.classList.contains('hidden')) {
                showStep(stepPanels.indexOf(panel) + 1);
            }
        }, true);

        // Tandai langkah yang memiliki error dari server dan buka yang pertama.
        let firstErrorStep = null;

        stepPanels.forEach((panel, index) => {
            if (!panel.querySelector('[data-field-error]')) return;

            stepTabs[index]
                ?.querySelector('[data-step-error-dot]')
                ?.classList.remove('hidden');

            if (firstErrorStep === null) firstErrorStep = index + 1;
        });

        showStep(firstErrorStep ?? 1);
    }

    // --------------------------------------------------
    // INITIALIZATION
    // --------------------------------------------------

    createFontControls();
    initSteps();

    setCakeColor(cakeColorInput?.value || DEFAULT_TOP_COLOR);
    setSideColor(sideColorInput?.value || DEFAULT_SIDE_COLOR);
    setMessageColor(messageColorInput?.value || DEFAULT_MESSAGE_COLOR);

    updateMessageLimit();
    renderPreview();

    ensureCakeFont(renderPreview);
});