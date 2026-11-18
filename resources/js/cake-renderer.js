/**
 * Renderer preview kue 2.5D (SVG).
 *
 * Dipakai bersama oleh:
 *  - resources/js/cake-builder.js  (halaman customer, interaktif)
 *  - halaman admin                 (hanya tampil, lewat initStaticCakePreviews)
 *
 * Semua geometri, proyeksi, frosting, dekorasi, dan penempatan tulisan berada
 * di sini sehingga tampilan admin selalu sama persis dengan yang dilihat
 * pelanggan.
 */

export const DEFAULT_TOP_COLOR = '#F4A6B7';
export const DEFAULT_SIDE_COLOR = '#D88FA1';
export const DEFAULT_MESSAGE_COLOR = '#4A2C27';

const VIEW_W = 400;
const VIEW_Y0 = 30;     // bagian atas kanvas yang kosong dipangkas
const VIEW_BOX_H = 274; // rasio 400:274, dipakai juga oleh Blade

// Tipografi tulisan kue. Playfair Display dimuat oleh ensureCakeFont()
// jika halaman belum memuatnya; Georgia menjadi cadangan.
export const FONT_NAME = 'Playfair Display';
export const FONT_STACK = '"Playfair Display", Georgia, "Times New Roman", serif';
export const FONT_WEIGHT = 500;

// Batas ukuran font yang dapat diatur pengguna (px).
export const FONT_MIN = 8;
export const FONT_MAX = 28;
const FONT_HARD_MIN = 6;
const LINE_HEIGHT = 1.22;

// Kamera tampak depan: pandangan ortografis dari atas dengan sudut elevasi.
// Satu sistem proyeksi yang sama dipakai untuk badan, frosting, dekorasi,
// dan tulisan, sehingga semuanya selalu konsisten.
const ELEVATION = (34 * Math.PI) / 180;
const SIN_E = Math.sin(ELEVATION);
const COS_E = Math.cos(ELEVATION);

const CAKE_HEIGHT = 88;      // tinggi badan kue (satuan kue)
const FRONT_CENTER_Y = 146;  // titik tengah vertikal kue pada kanvas
const TOP_CENTER_Y = 148;

// --------------------------------------------------
// HELPER UMUM
// --------------------------------------------------

export function escapeSvg(value) {
    return String(value ?? '').replace(/[&<>"']/g, character => {
        const entities = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&apos;',
        };

        return entities[character];
    });
}

export function clamp(value, min, max) {
    return Math.min(Math.max(value, min), max);
}

function lerp(a, b, t) {
    return a + (b - a) * t;
}

export function fx(value) {
    return Number(value.toFixed(2));
}

export function isValidHex(value) {
    return /^#[0-9A-Fa-f]{6}$/.test(value);
}

export function normalizeHex(value, fallback) {
    const color = String(value || '').trim();

    return isValidHex(color)
        ? color.toUpperCase()
        : fallback;
}

// --------------------------------------------------
// --------------------------------------------------
// HELPER WARNA
// --------------------------------------------------

function hexToRgb(hex) {
    const value = hex.replace('#', '');

    return [
        parseInt(value.slice(0, 2), 16),
        parseInt(value.slice(2, 4), 16),
        parseInt(value.slice(4, 6), 16),
    ];
}

function rgbToHex(r, g, b) {
    return '#' + [r, g, b]
        .map(v => Math.round(clamp(v, 0, 255)).toString(16).padStart(2, '0'))
        .join('')
        .toUpperCase();
}

function mixColor(colorA, colorB, amount) {
    const a = hexToRgb(colorA);
    const b = hexToRgb(colorB);

    return rgbToHex(
        lerp(a[0], b[0], amount),
        lerp(a[1], b[1], amount),
        lerp(a[2], b[2], amount)
    );
}

// amount > 0 mencerahkan, amount < 0 menggelapkan.
function shade(hex, amount) {
    return amount >= 0
        ? mixColor(hex, '#FFFFFF', amount)
        : mixColor(hex, '#000000', -amount);
}

function luminance(hex) {
    const [r, g, b] = hexToRgb(hex);

    return (0.2126 * r + 0.7152 * g + 0.0722 * b) / 255;
}


// --------------------------------------------------
// PENGUKURAN & PEMBUNGKUSAN TEKS
// --------------------------------------------------

const textCanvas = document.createElement('canvas');
const textContext = textCanvas.getContext('2d');

function measureTextWidth(text, fontSize) {
    if (!text) return 0;

    if (!textContext) return text.length * fontSize * 0.55;

    textContext.font = `${FONT_WEIGHT} ${fontSize}px ${FONT_STACK}`;

    return textContext.measureText(text).width;
}

function splitLongWord(word, maxWidth, fontSize) {
    const parts = [];
    let current = '';

    for (const character of Array.from(word)) {
        const candidate = current + character;

        if (
            current &&
            measureTextWidth(candidate, fontSize) > maxWidth
        ) {
            parts.push(current);
            current = character;
        } else {
            current = candidate;
        }
    }

    if (current) parts.push(current);

    return parts;
}

function wrapTextByWidth(message, maxWidth, fontSize) {
    const paragraphs = String(message || '').split('\n');
    const lines = [];

    paragraphs.forEach(paragraph => {
        const words = paragraph.trim().split(/\s+/).filter(Boolean);

        if (!words.length) {
            lines.push('');
            return;
        }

        let currentLine = '';

        words.forEach(word => {
            splitLongWord(word, maxWidth, fontSize).forEach(piece => {
                const candidate = currentLine
                    ? `${currentLine} ${piece}`
                    : piece;

                if (
                    !currentLine ||
                    measureTextWidth(candidate, fontSize) <= maxWidth
                ) {
                    currentLine = candidate;
                } else {
                    lines.push(currentLine);
                    currentLine = piece;
                }
            });
        });

        if (currentLine) lines.push(currentLine);
    });

    // Buang baris kosong di awal dan akhir.
    while (lines.length && !lines[0].trim()) lines.shift();
    while (lines.length && !lines[lines.length - 1].trim()) lines.pop();

    return lines;
}

/**
 * Mencari ukuran font terbesar (<= requested) sehingga seluruh tulisan
 * muat pada area yang tersedia. Lebar dihitung dari lebar teks nyata,
 * bukan jumlah karakter, dan huruf tidak pernah diregangkan.
 */
function fitTextBlock(message, options) {
    const {
        maxWidth,
        maxHeight,
        maxLines,
        requested,
        check,
    } = options;

    if (!String(message || '').trim()) {
        return {
            lines: [],
            widths: [],
            fontSize: requested,
            lineHeight: requested * LINE_HEIGHT,
            requested,
        };
    }

    let fontSize = Math.round(requested);

    while (fontSize >= FONT_HARD_MIN) {
        const lines = wrapTextByWidth(message, maxWidth, fontSize);
        const widths = lines.map(line => measureTextWidth(line, fontSize));
        const lineHeight = fontSize * LINE_HEIGHT;

        const fits =
            lines.length <= maxLines &&
            lines.length * lineHeight <= maxHeight &&
            widths.every(width => width <= maxWidth + 0.01) &&
            (!check || check(lines, widths, fontSize, lineHeight));

        if (fits) {
            return { lines, widths, fontSize, lineHeight, requested };
        }

        fontSize -= 1;
    }

    fontSize = FONT_HARD_MIN;

    const lines = wrapTextByWidth(message, maxWidth, fontSize)
        .slice(0, maxLines);

    return {
        lines,
        widths: lines.map(line => measureTextWidth(line, fontSize)),
        fontSize,
        lineHeight: fontSize * LINE_HEIGHT,
        requested,
    };
}

// --------------------------------------------------
// GEOMETRI DASAR
// --------------------------------------------------

function sampleCubic(segment, count) {
    const [p0, p1, p2, p3] = segment;
    const points = [];

    for (let i = 0; i <= count; i++) {
        const t = i / count;
        const mt = 1 - t;

        points.push({
            x: mt * mt * mt * p0[0] + 3 * mt * mt * t * p1[0] +
                3 * mt * t * t * p2[0] + t * t * t * p3[0],
            z: mt * mt * mt * p0[1] + 3 * mt * mt * t * p1[1] +
                3 * mt * t * t * p2[1] + t * t * t * p3[1],
        });
    }

    return points;
}

function arcPoints(cx, cz, radius, startAngle, endAngle, count) {
    const points = [];

    for (let i = 0; i <= count; i++) {
        const angle = lerp(startAngle, endAngle, i / count);

        points.push({
            x: cx + radius * Math.cos(angle),
            z: cz + radius * Math.sin(angle),
        });
    }

    return points;
}

function linePoints(x1, z1, x2, z2, step) {
    const length = Math.hypot(x2 - x1, z2 - z1);
    const count = Math.max(1, Math.round(length / step));
    const points = [];

    for (let i = 0; i <= count; i++) {
        points.push({
            x: lerp(x1, x2, i / count),
            z: lerp(z1, z2, i / count),
        });
    }

    return points;
}

function polyLength(points) {
    let total = 0;

    for (let i = 1; i < points.length; i++) {
        total += Math.hypot(
            points[i].x - points[i - 1].x,
            points[i].z - points[i - 1].z
        );
    }

    return total;
}

// Membagi polyline menjadi titik berjarak sama, lengkap dengan posisi s.
function resamplePolyline(points, step) {
    const cumulative = [0];

    for (let i = 1; i < points.length; i++) {
        cumulative.push(
            cumulative[i - 1] +
            Math.hypot(
                points[i].x - points[i - 1].x,
                points[i].z - points[i - 1].z
            )
        );
    }

    const total = cumulative[cumulative.length - 1];
    const count = Math.max(2, Math.round(total / step));
    const result = [];

    let j = 0;

    for (let k = 0; k <= count; k++) {
        const target = (total * k) / count;

        while (j < cumulative.length - 2 && cumulative[j + 1] < target) {
            j++;
        }

        const span = cumulative[j + 1] - cumulative[j] || 1;
        const t = clamp((target - cumulative[j]) / span, 0, 1);

        result.push({
            x: lerp(points[j].x, points[j + 1].x, t),
            z: lerp(points[j].z, points[j + 1].z, t),
            s: target,
        });
    }

    return { pts: result, total };
}

function resampleClosed(points, spacing) {
    const closed = [...points, points[0]];
    const { pts, total } = resamplePolyline(closed, spacing);

    // Titik terakhir sama dengan titik pertama.
    return { pts: pts.slice(0, -1), total };
}

function scalePolygon(points, factor) {
    return points.map(point => ({
        x: point.x * factor,
        z: point.z * factor,
    }));
}

function pointInPolygon(x, z, polygon) {
    let inside = false;

    for (let i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {
        const xi = polygon[i].x;
        const zi = polygon[i].z;
        const xj = polygon[j].x;
        const zj = polygon[j].z;

        const intersects =
            (zi > z) !== (zj > z) &&
            x < ((xj - xi) * (z - zi)) / (zj - zi || 1e-9) + xi;

        if (intersects) inside = !inside;
    }

    return inside;
}

// Memeriksa apakah seluruh kotak tulisan berada di dalam poligon.
function blockInsidePolygon(polygon, centerX, centerZ, width, height) {
    const hw = width / 2;
    const hh = height / 2;

    const samples = [
        [-hw, -hh], [0, -hh], [hw, -hh],
        [-hw, 0], [hw, 0],
        [-hw, hh], [0, hh], [hw, hh],
    ];

    return samples.every(([dx, dz]) =>
        pointInPolygon(centerX + dx, centerZ + dz, polygon)
    );
}

function polygonBounds(points) {
    let minX = Infinity;
    let maxX = -Infinity;
    let minZ = Infinity;
    let maxZ = -Infinity;

    points.forEach(point => {
        minX = Math.min(minX, point.x);
        maxX = Math.max(maxX, point.x);
        minZ = Math.min(minZ, point.z);
        maxZ = Math.max(maxZ, point.z);
    });

    return { minX, maxX, minZ, maxZ };
}

function pathFromPoints(points, close = true) {
    if (!points.length) return '';

    return `M${points
        .map(point => `${fx(point[0])} ${fx(point[1])}`)
        .join(' L')}${close ? ' Z' : ''}`;
}

// --------------------------------------------------
// MODEL GEOMETRI KUE (denah / plan view)
// --------------------------------------------------
//
// Setiap bentuk didefinisikan satu kali sebagai denah (x ke kanan,
// z ke arah penonton). Dari denah inilah dihasilkan:
//   - permukaan atas,
//   - dinding depan yang terlihat (wall),
//   - sumbu tulisan pada dinding,
//   - bayangan, frosting, dan dekorasi.
// Dengan begitu bentuk dan tulisan tidak mungkin saling bertabrakan.

function makeWall(rawPoints, step) {
    const { pts, total } = resamplePolyline(rawPoints, step);

    return { pts, total, s0: total / 2, halfWidth: 80 };
}

function recenter(plan, wallPoints) {
    const bounds = polygonBounds(plan);
    const dx = (bounds.minX + bounds.maxX) / 2;
    const dz = (bounds.minZ + bounds.maxZ) / 2;

    const shift = point => ({ x: point.x - dx, z: point.z - dz });

    return {
        plan: plan.map(shift),
        wall: wallPoints.map(shift),
    };
}

function buildRoundModel() {
    const R = 104;
    const plan = [];

    for (let i = 0; i < 144; i++) {
        const angle = (i / 144) * Math.PI * 2;

        plan.push({ x: R * Math.cos(angle), z: R * Math.sin(angle) });
    }

    const rawWall = [];

    for (let i = 0; i <= 96; i++) {
        const angle = -Math.PI / 2 + (Math.PI * i) / 96;

        rawWall.push({ x: R * Math.sin(angle), z: R * Math.cos(angle) });
    }

    const wall = makeWall(rawWall, 1.5);

    wall.s0 = wall.total / 2;
    wall.halfWidth = R * 0.8;

    return {
        key: 'Bulat',
        psi: 0,
        plan,
        wall,
        edgeS: null,
        top: {
            center: { x: 0, z: 0 },
            fitScale: 0.78,
            dragScale: 0.9,
            maxWidth: 132,
            maxHeight: 84,
            maxLines: 3,
        },
    };
}

function buildSquareModel() {
    const W = 164;
    const D = 164;
    const r = 12;

    // Sudut putar kamera terhadap kue. Dinding yang terlihat berakhir
    // tepat di siluet, yaitu titik ketika arah pandang menyinggung
    // sudut kue (sudut putar yang sama pada kiri dan kanan).
    const psi = (-20 * Math.PI) / 180;

    // Denah persegi bersudut membulat.
    const plan = [
        ...arcPoints(-W / 2 + r, -D / 2 + r, r, Math.PI, Math.PI * 1.5, 8),
        ...arcPoints(W / 2 - r, -D / 2 + r, r, Math.PI * 1.5, Math.PI * 2, 8),
        ...arcPoints(W / 2 - r, D / 2 - r, r, 0, Math.PI / 2, 8),
        ...arcPoints(-W / 2 + r, D / 2 - r, r, Math.PI / 2, Math.PI, 8),
    ];

    // Dinding yang terlihat: sudut kiri depan, sisi depan, sudut kanan
    // depan, lalu sisi kanan. Sisi kiri dan belakang tidak terlihat.
    const pieces = [
        arcPoints(-W / 2 + r, D / 2 - r, r, Math.PI + psi, Math.PI / 2, 8),
        linePoints(-W / 2 + r, D / 2, W / 2 - r, D / 2, 2),
        arcPoints(W / 2 - r, D / 2 - r, r, Math.PI / 2, 0, 8),
        linePoints(W / 2, D / 2 - r, W / 2, -D / 2 + r, 2),
        arcPoints(W / 2 - r, -D / 2 + r, r, 0, psi, 5),
    ];

    const rawWall = pieces.flat();

    const sA = polyLength(pieces[0]);
    const sB = sA + polyLength(pieces[1]);

    const wall = makeWall(rawWall, 1.5);

    wall.s0 = (sA + sB) / 2;
    wall.halfWidth = (sB - sA) / 2 - 2;

    return {
        key: 'Persegi',
        psi,
        plan,
        wall,
        edgeS: sB + polyLength(pieces[2]) / 2,
        top: {
            center: { x: 0, z: 0 },
            fitScale: 0.78,
            dragScale: 0.92,
            maxWidth: 124,
            maxHeight: 100,
            maxLines: 4,
        },
    };
}

function buildHeartModel() {
    // Dua lobus, dua sisi miring, dan ujung bawah yang sedikit membulat.
    const segments = [
        [[0, -48], [6, -62], [16, -76], [32, -86]],
        [[32, -86], [60, -104], [100, -92], [100, -58]],
        [[100, -58], [100, -14], [38, 36], [12, 58]],
        [[12, 58], [6, 64], [-6, 64], [-12, 58]],
        [[-12, 58], [-38, 36], [-100, -14], [-100, -58]],
        [[-100, -58], [-100, -92], [-60, -104], [-32, -86]],
        [[-32, -86], [-16, -76], [-6, -62], [0, -48]],
    ];

    const sampled = segments.map(segment => sampleCubic(segment, 28));

    const rawPlan = sampled.flatMap(points => points.slice(0, -1));

    // Sisi depan: dari kanan ke kiri, lalu dibalik agar kiri ke kanan.
    const rawWall = [
        ...sampled[2],
        ...sampled[3].slice(1),
        ...sampled[4].slice(1),
    ].reverse();

    const { plan, wall: wallPoints } = recenter(rawPlan, rawWall);

    const wall = makeWall(wallPoints, 1.5);

    let closest = wall.pts[0];

    wall.pts.forEach(point => {
        if (Math.abs(point.x) < Math.abs(closest.x)) closest = point;
    });

    wall.s0 = closest.s;
    wall.halfWidth = 76;

    return {
        key: 'Hati',
        psi: 0,
        plan,
        wall,
        edgeS: null,
        top: {
            center: { x: 0, z: -8 },
            fitScale: 0.8,
            dragScale: 0.9,
            maxWidth: 116,
            maxHeight: 70,
            maxLines: 3,
        },
    };
}

const modelCache = {};

function getModel(shape) {
    if (!modelCache[shape]) {
        modelCache[shape] = shape === 'Persegi'
            ? buildSquareModel()
            : shape === 'Hati'
                ? buildHeartModel()
                : buildRoundModel();
    }

    return modelCache[shape];
}

// --------------------------------------------------
// PROYEKSI
// --------------------------------------------------

// Proyeksi tampak depan: (x, tinggi, z) -> (sx, sy).
function makeFrontProjector(model) {
    const cosP = Math.cos(model.psi);
    const sinP = Math.sin(model.psi);

    const raw = (x, height, z) => [
        x * cosP + z * sinP,
        (-x * sinP + z * cosP) * SIN_E - height * COS_E,
    ];

    let minX = Infinity;
    let maxX = -Infinity;
    let minY = Infinity;
    let maxY = -Infinity;

    model.plan.forEach(point => {
        [0, CAKE_HEIGHT].forEach(height => {
            const [px, py] = raw(point.x, height, point.z);

            minX = Math.min(minX, px);
            maxX = Math.max(maxX, px);
            minY = Math.min(minY, py);
            maxY = Math.max(maxY, py);
        });
    });

    const offsetX = VIEW_W / 2 - (minX + maxX) / 2;
    const offsetY = FRONT_CENTER_Y - (minY + maxY) / 2;

    return (x, height, z) => {
        const [px, py] = raw(x, height, z);

        return [px + offsetX, py + offsetY];
    };
}

// Proyeksi tampak atas: langsung dari denah.
function projectTop(x, z) {
    return [VIEW_W / 2 + x, TOP_CENTER_Y + z];
}

function getWallFrame(wall, s) {
    const pts = wall.pts;
    const count = pts.length - 1;
    const target = clamp(s, 0, wall.total);

    const index = clamp(
        Math.floor((target / wall.total) * count),
        0,
        count - 1
    );

    const p0 = pts[index];
    const p1 = pts[index + 1];
    const t = clamp((target - p0.s) / ((p1.s - p0.s) || 1), 0, 1);

    const before = pts[Math.max(0, index - 1)];
    const after = pts[Math.min(count, index + 2)];

    const tx = after.x - before.x;
    const tz = after.z - before.z;
    const length = Math.hypot(tx, tz) || 1;

    return {
        x: lerp(p0.x, p1.x, t),
        z: lerp(p0.z, p1.z, t),
        tx: tx / length,
        tz: tz / length,
    };
}


// --------------------------------------------------
// RENDERER
// --------------------------------------------------

/**
 * Membuat renderer dari sebuah "source" (sumber data desain).
 *
 * source harus menyediakan fungsi: shape(), topColor(), sideColor(),
 * messageColor(), decorations(), message(), requestedFontSize(), dan
 * topTextPosition() yang mengembalikan objek { x, y }.
 *
 * options.interactive = false menonaktifkan kursor drag (dipakai admin).
 */
export function createCakeRenderer(source, options = {}) {
    const interactive = options.interactive !== false;

    const getSelectedShape = () => source.shape();
    const getTopColor = () => source.topColor();
    const getSideColor = () => source.sideColor();
    const getMessageColor = () => source.messageColor();
    const getSelectedDecorations = () => source.decorations();
    const getSelectedMessage = () => source.message();
    const getRequestedFontSize = () => source.requestedFontSize();

    // Konteks tulisan tampak atas, dipakai oleh drag dan pembatasan posisi.
    let topContext = null;

    // --------------------------------------------------
    // UKURAN TULISAN YANG DIPAKAI (hasil fitting)
    // --------------------------------------------------

    // Tulisan selalu berada di permukaan atas kue, sehingga ukuran dan
    // posisinya sama pada tampak depan maupun tampak atas.
    function getTextLayout(view, requested = getRequestedFontSize()) {
        const model = getModel(getSelectedShape());
        const zone = model.top;
        const polygon = scalePolygon(model.plan, zone.fitScale);

        return fitTextBlock(getSelectedMessage(), {
            maxWidth: zone.maxWidth,
            maxHeight: zone.maxHeight,
            maxLines: zone.maxLines,
            requested,
            check: (lines, widths, fontSize, lineHeight) =>
                blockInsidePolygon(
                    polygon,
                    zone.center.x,
                    zone.center.z,
                    Math.max(0, ...widths),
                    lines.length * lineHeight
                ),
        });
    }

    // --------------------------------------------------
    // DEFINISI SVG
    // --------------------------------------------------

    function getCakeDefinitions() {
        return `
            <defs>
                <filter id="cake-soft-shadow" x="-30%" y="-40%" width="160%" height="190%">
                    <feGaussianBlur stdDeviation="6" />
                </filter>

                <radialGradient id="cake-top-shine" cx="0.32" cy="0.26" r="0.9">
                    <stop offset="0%" stop-color="#FFFFFF" stop-opacity=".5" />
                    <stop offset="55%" stop-color="#FFFFFF" stop-opacity=".08" />
                    <stop offset="100%" stop-color="#000000" stop-opacity=".07" />
                </radialGradient>
            </defs>
        `;
    }

    function getWallShading(model, xMin, xMax, cornerX) {
        const base = `gradientUnits="userSpaceOnUse" x1="${fx(xMin)}" y1="0" x2="${fx(xMax)}" y2="0"`;

        if (model.key === 'Persegi' && cornerX !== null) {
            const f = clamp((cornerX - xMin) / ((xMax - xMin) || 1), 0.05, 0.95);

            return `
                <linearGradient id="cake-wall-shade" ${base}>
                    <stop offset="0" stop-color="#FFFFFF" stop-opacity=".22" />
                    <stop offset="${fx(f - 0.004)}" stop-color="#000000" stop-opacity=".03" />
                    <stop offset="${fx(f + 0.004)}" stop-color="#000000" stop-opacity=".2" />
                    <stop offset="1" stop-color="#000000" stop-opacity=".3" />
                </linearGradient>
            `;
        }

        return `
            <linearGradient id="cake-wall-shade" ${base}>
                <stop offset="0" stop-color="#FFFFFF" stop-opacity=".04" />
                <stop offset=".2" stop-color="#FFFFFF" stop-opacity=".26" />
                <stop offset=".38" stop-color="#FFFFFF" stop-opacity=".08" />
                <stop offset=".62" stop-color="#000000" stop-opacity=".05" />
                <stop offset="1" stop-color="#000000" stop-opacity=".34" />
            </linearGradient>
        `;
    }

    // --------------------------------------------------
    // FROSTING (dripping band mengikuti dinding)
    // --------------------------------------------------

    function bandAt(s, wall) {
        const LOBE = 17;
        const q = s - wall.s0 + LOBE / 2;
        const index = Math.floor(q / LOBE);
        const u = q / LOBE - index;
        const longer = ((index % 4) + 4) % 4 === 2 ? 4 : 0;

        // Gelombang dilemahkan di kedua ujung dinding agar frosting
        // menyatu rapi dengan siluet kue.
        const edge = Math.min(s, wall.total - s);
        const fade = clamp(edge / 10, 0, 1);
        const smooth = fade * fade * (3 - 2 * fade);

        return 6.5 + (4.5 + longer) * Math.pow(Math.sin(Math.PI * u), 0.85) * smooth;
    }

    // --------------------------------------------------
    // DEKORASI
    // --------------------------------------------------

    function decorationKind(name) {
        const value = String(name || '').toLowerCase();

        if (/(lilin angka|angka|number)/.test(value)) return 'numcandle';
        if (/(lilin|candle)/.test(value)) return 'candle';
        if (/(strawberr|stroberi|strawberi)/.test(value)) return 'strawberry';
        if (/(cherry|ceri)/.test(value)) return 'cherry';
        if (/(blueberr|bluberi|anggur|grape)/.test(value)) return 'blueberry';
        if (/(sprinkle|taburan|meses)/.test(value)) return 'sprinkle';
        if (/(bunga|flower|floral|rose|mawar)/.test(value)) return 'flower';
        if (/(coklat|cokelat|chocolate|choco|oreo)/.test(value)) return 'choco';
        if (/(emas|gold|glitter|bintang|star)/.test(value)) return 'gold';

        return 'pearl';
    }

    function decorationItem(kind, x, y, index, squashY) {
        const palette = ['#F8C8DC', '#FFE29A', '#BFE3F5', '#CFE8C5', '#E5CFF2'];
        let body = '';

        if (kind === 'strawberry') {
            body = `
                <path d="M0 -5 C4.4 -5.4 6.2 -1.2 3.8 2.8 C2.6 4.8 .9 6 0 6.2 C-.9 6 -2.6 4.8 -3.8 2.8 C-6.2 -1.2 -4.4 -5.4 0 -5Z"
                      fill="#E5484D" stroke="#B92B3A" stroke-width=".6" />
                <path d="M-3.2 -5 L0 -7.2 L3.2 -5 L0 -3.8Z" fill="#4C9A52" />
                <circle cx="-1.6" cy="-.8" r=".55" fill="#FFE29A" />
                <circle cx="1.6" cy="0" r=".55" fill="#FFE29A" />
                <circle cx="0" cy="2.4" r=".55" fill="#FFE29A" />
                <circle cx="-2" cy="-2.4" r="1.1" fill="#FFFFFF" opacity=".45" />
            `;
        } else if (kind === 'cherry') {
            body = `
                <path d="M0 -3 C1.5 -7 3.5 -8 5 -8" fill="none" stroke="#5E7F3A" stroke-width="1" stroke-linecap="round" />
                <circle cx="0" cy="1" r="5" fill="#B3202F" stroke="#8A1522" stroke-width=".6" />
                <circle cx="-1.8" cy="-.8" r="1.4" fill="#FFFFFF" opacity=".5" />
            `;
        } else if (kind === 'blueberry') {
            body = `
                <circle cx="0" cy="0" r="4.6" fill="#4A5BA8" stroke="#34417F" stroke-width=".6" />
                <circle cx="0" cy="-.2" r="1.1" fill="#34417F" />
                <circle cx="-1.6" cy="-1.8" r="1.2" fill="#FFFFFF" opacity=".4" />
            `;
        } else if (kind === 'sprinkle') {
            const color = palette[index % palette.length];
            const angle = (index * 47) % 180;

            body = `
                <rect x="-3.6" y="-1.1" width="7.2" height="2.2" rx="1.1"
                      fill="${color}" stroke="#D9A8B6" stroke-width=".4"
                      transform="rotate(${angle})" />
            `;
        } else if (kind === 'flower') {
            body = `
                <g fill="#FFFFFF" stroke="#E7BCC8" stroke-width=".5">
                    ${[0, 72, 144, 216, 288].map(angle =>
                        `<circle cx="0" cy="-3.3" r="2.5" transform="rotate(${angle})" />`
                    ).join('')}
                </g>
                <circle cx="0" cy="0" r="1.9" fill="#F4C95D" />
            `;
        } else if (kind === 'choco') {
            body = `
                <path d="M-4.4 3.8 C-4.4 -2 -2 -5.6 0 -5.8 C2 -5.6 4.4 -2 4.4 3.8Z"
                      fill="#6B3E2E" stroke="#4E2A1E" stroke-width=".6" />
                <path d="M-1.8 -1 C-.6 -2.4 .8 -2.4 1.8 -1" fill="none"
                      stroke="#FFFFFF" stroke-width=".8" opacity=".35" stroke-linecap="round" />
            `;
        } else if (kind === 'gold') {
            body = `
                <circle cx="0" cy="0" r="4.4" fill="#E9C46A" stroke="#C99A2E" stroke-width=".6" />
                <circle cx="-1.5" cy="-1.5" r="1.4" fill="#FFF3C4" opacity=".8" />
            `;
        } else {
            body = `
                <circle cx="0" cy="0" r="4.4" fill="#FFF7E8" stroke="#E8C5B8" stroke-width=".7" />
                <circle cx="-1.4" cy="-1.4" r="1.3" fill="#FFFFFF" opacity=".95" />
            `;
        }

        return `<g transform="translate(${fx(x)} ${fx(y)}) scale(1 ${squashY})">${body}</g>`;
    }

    // Lilin berdiri di atas kue, jadi digambar terpisah dari dekorasi
    // yang tersebar di tepi (buah, taburan, dst.). Dilihat dari atas,
    // lilin tampak sebagai lingkaran kecil dengan api di tengahnya.
    function candleItem(kind, x, y, index, isTop) {
        const colors = ['#F8A5C2', '#9ED8F2', '#FFE29A', '#B9E4B0', '#D9B8F0'];
        const color = colors[index % colors.length];
        const wide = kind === 'numcandle';

        let body;

        if (isTop) {
            const w = wide ? 5.2 : 2.8;
            const h = wide ? 3.2 : 2.8;

            body = `
                <rect x="${-w}" y="${-h}" width="${w * 2}" height="${h * 2}" rx="${h}"
                      fill="${color}" stroke="#FFFFFF" stroke-width=".6" />
                <circle cx="0" cy="0" r="1.5" fill="#FFB547" />
                <circle cx="0" cy="0" r=".7" fill="#FFF3C4" />
            `;
        } else {
            const w = wide ? 5.4 : 2.3;
            const h = wide ? 26 : 19;

            body = `
                <ellipse cx="0" cy="0" rx="${w + 1.2}" ry="1.6" fill="#000000" opacity=".12" />
                <rect x="${-w}" y="${-h}" width="${w * 2}" height="${h}" rx="${Math.min(w, 2.4)}"
                      fill="${color}" stroke="#FFFFFF" stroke-opacity=".7" stroke-width=".5" />
                <path d="M${-w} ${-h * 0.72} L${w} ${-h * 0.58} M${-w} ${-h * 0.42} L${w} ${-h * 0.28} M${-w} ${-h * 0.12} L${w} 0"
                      stroke="#FFFFFF" stroke-opacity=".55" stroke-width="1.1" />
                <line x1="0" y1="${-h}" x2="0" y2="${-h - 2.4}" stroke="#5B3A2E" stroke-width=".7" />
                <path d="M0 ${-h - 9} C3 ${-h - 5} 3 ${-h - 2.4} 0 ${-h - 2} C-3 ${-h - 2.4} -3 ${-h - 5} 0 ${-h - 9}Z"
                      fill="#FFB547" />
                <path d="M0 ${-h - 6} C1.3 ${-h - 4} 1.3 ${-h - 2.8} 0 ${-h - 2.6} C-1.3 ${-h - 2.8} -1.3 ${-h - 4} 0 ${-h - 6}Z"
                      fill="#FFF3C4" />
            `;
        }

        return `<g transform="translate(${fx(x)} ${fx(y)})">${body}</g>`;
    }

    // avoid: kotak tulisan (denah) yang tidak boleh tertimpa dekorasi.
    function getDecorations(model, project, squashY, avoid = null, isTop = false) {
        const selected = getSelectedDecorations();

        if (!selected.length) return '';

        const kinds = selected.map(name => ({ name, kind: decorationKind(name) }));
        const isCandle = kind => kind === 'candle' || kind === 'numcandle';

        const decorations = kinds.filter(item => !isCandle(item.kind)).map(item => item.name);
        const candleKinds = [...new Set(
            kinds.filter(item => isCandle(item.kind)).map(item => item.kind)
        )];

        const clearOfText = point => {
            if (!avoid) return true;

            const margin = 9;

            return !(
                Math.abs(point.x - avoid.x) < avoid.width / 2 + margin &&
                Math.abs(point.z - avoid.z) < avoid.height / 2 + margin
            );
        };

        let markup = '';

        // Dekorasi tepi (hanya jika ada yang bukan lilin) -> tidak lagi
        // menggambar mutiara pengganti ketika yang dipilih hanya lilin.
        if (decorations.length) {
            const ring = resampleClosed(scalePolygon(model.plan, 0.86), 34).pts;

            const items = ring.map((point, index) => ({
                point,
                kind: decorationKind(decorations[index % decorations.length]),
                index,
                position: project(point.x, point.z),
            })).filter(item => clearOfText(item.point));

            // Gambar dari belakang ke depan.
            items.sort((a, b) => a.position[1] - b.position[1]);

            markup += items
                .map(item => decorationItem(
                    item.kind,
                    item.position[0],
                    item.position[1],
                    item.index,
                    squashY
                ))
                .join('');
        }

        // Lilin: berdiri di bagian dalam permukaan atas.
        const candles = [];

        candleKinds.forEach(kind => {
            const isNumber = kind === 'numcandle';
            const ring = resampleClosed(
                scalePolygon(model.plan, isNumber ? 0.6 : 0.7),
                isNumber ? 1e6 : 62
            ).pts;

            // Lilin angka: dua batang di sisi berseberangan.
            const points = isNumber
                ? [ring[0], ring[Math.floor(ring.length / 2)]].filter(Boolean)
                : ring;

            points.forEach((point, index) => {
                if (!clearOfText(point)) return;

                candles.push({
                    kind,
                    index,
                    position: project(point.x, point.z),
                });
            });
        });

        candles.sort((a, b) => a.position[1] - b.position[1]);

        markup += candles
            .map(item => candleItem(
                item.kind,
                item.position[0],
                item.position[1],
                item.index,
                isTop
            ))
            .join('');

        return markup;
    }

    // --------------------------------------------------
    // WARNA TULISAN
    // --------------------------------------------------

    function getTextPaint() {
        const color = getMessageColor();

        const isLight = luminance(color) > 0.6;

        return {
            color,
            halo: isLight ? '#3B1F1A' : '#FFFFFF',
            haloOpacity: isLight ? 0.55 : 0.3,
        };
    }

    // --------------------------------------------------
    // TULISAN TAMPAK ATAS
    // --------------------------------------------------

    function getTopMessage(model, layout) {
        if (!layout.lines.length) {
            topContext = null;
            return '';
        }

        const { color, halo, haloOpacity } = getTextPaint();
        const { lines, widths, fontSize, lineHeight } = layout;

        const blockWidth = Math.max(0, ...widths);
        const blockHeight = lines.length * lineHeight;

        topContext = {
            model,
            width: blockWidth,
            height: blockHeight,
        };

        const [centerX, centerY] = projectTop(
            model.top.center.x,
            model.top.center.z
        );

        const startY = centerY - ((lines.length - 1) * lineHeight) / 2;

        const text = lines.map((line, index) => `
            <text
                x="${fx(centerX)}"
                y="${fx(startY + index * lineHeight + fontSize * 0.34)}"
            >${escapeSvg(line)}</text>
        `).join('');

        return `
            <g
                id="draggable-cake-message"
                transform="translate(${fx(source.topTextPosition().x)} ${fx(source.topTextPosition().y)})"
                style="${interactive ? 'cursor: grab; touch-action: none;' : ''}"
                pointer-events="all"
            >
                <rect
                    x="${fx(centerX - blockWidth / 2 - 8)}"
                    y="${fx(centerY - blockHeight / 2 - 6)}"
                    width="${fx(blockWidth + 16)}"
                    height="${fx(blockHeight + 12)}"
                    fill="transparent"
                    pointer-events="all"
                />

                <g
                    fill="${color}"
                    font-family='${FONT_STACK}'
                    font-size="${fontSize}"
                    font-weight="${FONT_WEIGHT}"
                    text-anchor="middle"
                    stroke="${halo}"
                    stroke-opacity="${haloOpacity}"
                    stroke-width=".5"
                    stroke-linejoin="round"
                    paint-order="stroke"
                    pointer-events="none"
                >
                    ${text}
                </g>
            </g>
        `;
    }

    // Tulisan di permukaan atas, dilihat dari tampak depan. Permukaan atas
    // adalah bidang datar, sehingga tulisan cukup diberi matriks afin dari
    // proyeksi kamera: huruf memendek dan miring persis seperti permukaannya.
    function getFrontSurfaceMessage(model, project, layout) {
        if (!layout.lines.length) {
            topContext = null;
            return '';
        }

        const { color, halo, haloOpacity } = getTextPaint();
        const { lines, widths, fontSize, lineHeight } = layout;

        topContext = {
            model,
            width: Math.max(0, ...widths),
            height: lines.length * lineHeight,
        };

        const cx = model.top.center.x + source.topTextPosition().x;
        const cz = model.top.center.z + source.topTextPosition().y;

        const [ox, oy] = project(cx, CAKE_HEIGHT, cz);
        const [ux, uy] = project(cx + 1, CAKE_HEIGHT, cz);
        const [vx, vy] = project(cx, CAKE_HEIGHT, cz + 1);

        const text = lines.map((line, index) => `
            <text
                x="0"
                y="${fx((index - (lines.length - 1) / 2) * lineHeight + fontSize * 0.34)}"
            >${escapeSvg(line)}</text>
        `).join('');

        return `
            <g
                transform="matrix(${fx(ux - ox)} ${fx(uy - oy)} ${fx(vx - ox)} ${fx(vy - oy)} ${fx(ox)} ${fx(oy)})"
                fill="${color}"
                font-family='${FONT_STACK}'
                font-size="${fontSize}"
                font-weight="${FONT_WEIGHT}"
                text-anchor="middle"
                stroke="${halo}"
                stroke-opacity="${haloOpacity}"
                stroke-width=".5"
                stroke-linejoin="round"
                paint-order="stroke"
            >
                ${text}
            </g>
        `;
    }

    // --------------------------------------------------
    // RENDER TAMPAK DEPAN
    // --------------------------------------------------

    function renderFront(layout) {
        const shape = getSelectedShape();
        const model = getModel(shape);
        const project = makeFrontProjector(model);

        const topColor = getTopColor();
        const sideColor = getSideColor();

        const sideEdge = shade(sideColor, -0.24);
        const frostColor = mixColor(topColor, '#FFFFFF', 0.1);
        const frostEdge = shade(topColor, -0.16);

        const wall = model.wall;

        const wallTop = wall.pts.map(p => project(p.x, CAKE_HEIGHT, p.z));
        const wallBottom = wall.pts.map(p => project(p.x, 0, p.z));

        const xs = wallTop.concat(wallBottom).map(p => p[0]);
        const xMin = Math.min(...xs);
        const xMax = Math.max(...xs);

        let cornerX = null;
        let cornerTop = null;
        let cornerBottom = null;

        if (model.edgeS !== null) {
            const frame = getWallFrame(wall, model.edgeS);

            cornerTop = project(frame.x, CAKE_HEIGHT, frame.z);
            cornerBottom = project(frame.x, 0, frame.z);
            cornerX = cornerTop[0];
        }

        const bodyPath = pathFromPoints(
            wallTop.concat([...wallBottom].reverse())
        );

        // Bayangan lantai mengikuti denah kue.
        const shadowPath = pathFromPoints(
            model.plan.map(p => project(p.x, 0, p.z))
        );

        // Frosting: pita bergelombang yang menempel di tepi atas dinding.
        const bandBottom = wall.pts.map(p =>
            project(p.x, CAKE_HEIGHT - bandAt(p.s, wall), p.z)
        );

        const bandPath = pathFromPoints(
            wallTop.concat([...bandBottom].reverse())
        );

        const bandEdge = pathFromPoints(bandBottom, false);
        const rimLine = pathFromPoints(wallTop, false);

        // Border mutiara di dasar kue.
        const pearlStep = Math.max(1, Math.round(10 / 1.5));

        const pearls = wall.pts
            .filter((p, index) =>
                index % pearlStep === 0 &&
                p.s > 6 &&
                p.s < wall.total - 6
            )
            .map(p => {
                const [x, y] = project(p.x, 3, p.z);

                return `
                    <circle cx="${fx(x)}" cy="${fx(y)}" r="2.7"
                            fill="#FFF8F1" stroke="${shade(sideColor, -0.2)}" stroke-width=".6" />
                    <circle cx="${fx(x - 0.8)}" cy="${fx(y - 0.8)}" r=".9"
                            fill="#FFFFFF" opacity=".9" />
                `;
            })
            .join('');

        // Permukaan atas.
        const topPoints = model.plan.map(p => project(p.x, CAKE_HEIGHT, p.z));
        const topPath = pathFromPoints(topPoints);

        const pearlRing = pathFromPoints(
            scalePolygon(model.plan, 0.95).map(p =>
                project(p.x, CAKE_HEIGHT, p.z)
            )
        );

        return `
            <svg
                viewBox="0 ${VIEW_Y0} ${VIEW_W} ${VIEW_BOX_H}"
                xmlns="http://www.w3.org/2000/svg"
                class="h-auto w-full"
                role="img"
                aria-label="Preview kue tampak depan"
            >
                ${getCakeDefinitions()}

                <defs>
                    ${getWallShading(model, xMin, xMax, cornerX)}

                    <linearGradient id="cake-wall-base-shade" gradientUnits="objectBoundingBox"
                                    x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0" stop-color="#000000" stop-opacity="0" />
                        <stop offset=".65" stop-color="#000000" stop-opacity="0" />
                        <stop offset="1" stop-color="#000000" stop-opacity=".14" />
                    </linearGradient>
                </defs>

                <!-- Bayangan -->
                <path
                    d="${shadowPath}"
                    transform="translate(5 7)"
                    fill="#6B4144"
                    opacity=".3"
                    filter="url(#cake-soft-shadow)"
                />

                <!-- Badan kue -->
                <path d="${bodyPath}" fill="${sideColor}" />
                <path d="${bodyPath}" fill="url(#cake-wall-shade)" />
                <path d="${bodyPath}" fill="url(#cake-wall-base-shade)" />
                <path d="${bodyPath}" fill="none" stroke="${sideEdge}"
                      stroke-width="1.1" stroke-linejoin="round" />

                ${cornerTop ? `
                    <path d="M${fx(cornerTop[0])} ${fx(cornerTop[1])} L${fx(cornerBottom[0])} ${fx(cornerBottom[1])}"
                          stroke="#FFFFFF" stroke-opacity=".38" stroke-width="1.3" fill="none" />
                ` : ''}

                <!-- Frosting yang mengalir dari tepi atas -->
                <path d="${bandPath}" fill="${frostColor}" />
                <path d="${bandPath}" fill="url(#cake-wall-shade)" opacity=".7" />
                <path d="${bandEdge}" fill="none" stroke="${frostEdge}"
                      stroke-width=".9" stroke-opacity=".55" stroke-linejoin="round" />

                <!-- Border mutiara -->
                ${pearls}

                <!-- Permukaan atas -->
                <path d="${topPath}" fill="${topColor}" />
                <path d="${topPath}" fill="url(#cake-top-shine)" />
                <path d="${topPath}" fill="none" stroke="${frostEdge}"
                      stroke-width="1.2" stroke-linejoin="round" />

                <path d="${rimLine}" fill="none" stroke="#FFFFFF"
                      stroke-opacity=".5" stroke-width="1.4" stroke-linecap="round" />

                <path d="${pearlRing}" fill="none" stroke="${shade(topColor, -0.2)}"
                      stroke-opacity=".3" stroke-width="4.4"
                      stroke-dasharray="0.01 6.6" stroke-linecap="round" />
                <path d="${pearlRing}" fill="none" stroke="#FFFBF6"
                      stroke-width="3.2"
                      stroke-dasharray="0.01 6.6" stroke-linecap="round" />

                <!-- Dekorasi -->
                ${getDecorations(model, (x, z) => project(x, CAKE_HEIGHT, z), 0.85, layout.lines.length ? {
                    x: model.top.center.x + source.topTextPosition().x,
                    z: model.top.center.z + source.topTextPosition().y,
                    width: Math.max(0, ...layout.widths),
                    height: layout.lines.length * layout.lineHeight,
                } : null, false)}

                <!-- Tulisan di permukaan atas -->
                ${getFrontSurfaceMessage(model, project, layout)}

                <text
                    x="200" y="298"
                    text-anchor="middle"
                    fill="#A58B80"
                    font-size="10"
                    font-family="sans-serif"
                    letter-spacing="2"
                >EARLY CAKE</text>
            </svg>
        `;
    }

    // --------------------------------------------------
    // RENDER TAMPAK ATAS
    // --------------------------------------------------

    function renderTop(layout) {
        const shape = getSelectedShape();
        const model = getModel(shape);

        const topColor = getTopColor();
        const sideColor = getSideColor();

        const frostEdge = shade(topColor, -0.16);

        const toPath = polygon =>
            pathFromPoints(polygon.map(p => projectTop(p.x, p.z)));

        const planPath = toPath(model.plan);
        const rimPath = toPath(scalePolygon(model.plan, 1.035));
        const pearlPath = toPath(scalePolygon(model.plan, 0.95));

        return `
            <svg
                viewBox="0 ${VIEW_Y0} ${VIEW_W} ${VIEW_BOX_H}"
                xmlns="http://www.w3.org/2000/svg"
                class="h-auto w-full"
                role="img"
                aria-label="Preview kue tampak atas"
            >
                ${getCakeDefinitions()}

                <!-- Bayangan -->
                <path
                    d="${rimPath}"
                    transform="translate(4 7)"
                    fill="#6B4144"
                    opacity=".28"
                    filter="url(#cake-soft-shadow)"
                />

                <!-- Tepi samping kue -->
                <path d="${rimPath}" fill="${sideColor}"
                      stroke="${shade(sideColor, -0.24)}" stroke-width="1"
                      stroke-linejoin="round" />

                <!-- Permukaan atas -->
                <path d="${planPath}" fill="${topColor}" />
                <path d="${planPath}" fill="url(#cake-top-shine)" />
                <path d="${planPath}" fill="none" stroke="${frostEdge}"
                      stroke-width="1.4" stroke-linejoin="round" />

                <path d="${pearlPath}" fill="none" stroke="${shade(topColor, -0.2)}"
                      stroke-opacity=".3" stroke-width="4.8"
                      stroke-dasharray="0.01 7" stroke-linecap="round" />
                <path d="${pearlPath}" fill="none" stroke="#FFFBF6"
                      stroke-width="3.4"
                      stroke-dasharray="0.01 7" stroke-linecap="round" />

                <!-- Dekorasi -->
                ${getDecorations(model, projectTop, 1, layout.lines.length ? {
                    x: model.top.center.x + source.topTextPosition().x,
                    z: model.top.center.z + source.topTextPosition().y,
                    width: Math.max(0, ...layout.widths),
                    height: layout.lines.length * layout.lineHeight,
                } : null, true)}

                <!-- Tulisan -->
                ${getTopMessage(model, layout)}

                <text
                    x="200" y="296"
                    text-anchor="middle"
                    fill="#A58B80"
                    font-size="10"
                    font-family="sans-serif"
                    letter-spacing="2"
                >EARLY CAKE</text>
            </svg>
        `;
    }


    // --------------------------------------------------
    // POSISI TULISAN TAMPAK ATAS
    // --------------------------------------------------

    function isTopPositionValid(x, y) {
        if (!topContext) return false;

        const { model, width, height } = topContext;

        return blockInsidePolygon(
            scalePolygon(model.plan, model.top.dragScale),
            model.top.center.x + x,
            model.top.center.z + y,
            width,
            height
        );
    }

    // Jika posisi tersimpan berada di luar kue (misalnya setelah tulisan
    // diperbesar), geser kembali ke arah tengah sampai masuk.
    function constrainTopPosition(position) {
        if (!topContext) return position;

        if (isTopPositionValid(position.x, position.y)) return position;

        if (!isTopPositionValid(0, 0)) return { x: 0, y: 0 };

        let low = 0;
        let high = 1;

        for (let i = 0; i < 24; i++) {
            const middle = (low + high) / 2;

            if (isTopPositionValid(position.x * middle, position.y * middle)) {
                low = middle;
            } else {
                high = middle;
            }
        }

        return { x: position.x * low, y: position.y * low };
    }

    return {
        getTextLayout,
        renderFront,
        renderTop,
        isTopPositionValid,
        constrainTopPosition,
    };
}

// --------------------------------------------------
// FONT
// --------------------------------------------------

export function ensureCakeFont(onReady = () => {}) {
    const alreadyLinked = Array.from(
        document.querySelectorAll('link[rel="stylesheet"]')
    ).some(link => /Playfair/i.test(link.href));

    const reload = () => {
        if (!document.fonts?.load) return;

        document.fonts
            .load(`${FONT_WEIGHT} 16px "${FONT_NAME}"`)
            .then(() => onReady())
            .catch(() => {});
    };

    if (!alreadyLinked) {
        const link = document.createElement('link');

        link.rel = 'stylesheet';
        link.href =
            'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&display=swap';
        link.dataset.cakeBuilderFont = '1';
        link.addEventListener('load', reload);

        document.head.appendChild(link);
    }

    reload();

    document.fonts?.ready?.then(() => onReady()).catch(() => {});
}

// --------------------------------------------------
// PREVIEW STATIS (admin)
// --------------------------------------------------

function createStaticRenderer(config) {
    const shape = ['Persegi', 'Hati'].includes(config.shape) ? config.shape : 'Bulat';

    const requested = clamp(
        Math.round(Number(config.message_font_size)) ||
            Math.round(Number(config.message_font_size_default)) ||
            16,
        FONT_MIN,
        FONT_MAX
    );

    const state = {
        position: {
            x: Number(config.message_position_top?.x) || 0,
            y: Number(config.message_position_top?.y) || 0,
        },
    };

    const renderer = createCakeRenderer(
        {
            shape: () => shape,
            topColor: () => normalizeHex(config.top_color ?? config.cake_color, DEFAULT_TOP_COLOR),
            sideColor: () => normalizeHex(config.side_color, DEFAULT_SIDE_COLOR),
            messageColor: () => normalizeHex(config.message_color, DEFAULT_MESSAGE_COLOR),
            decorations: () => Array.isArray(config.decorations)
                ? config.decorations.filter(item => typeof item === 'string')
                : [],
            message: () => String(config.message ?? ''),
            requestedFontSize: () => requested,
            topTextPosition: () => state.position,
        },
        { interactive: false }
    );

    return { renderer, state };
}

/**
 * Menggambar semua elemen [data-cake-preview="front|top"] dari atribut
 * data-config (JSON design_config). Mengembalikan true jika ada elemen.
 */
export function initStaticCakePreviews(root = document) {
    const nodes = Array.from(root.querySelectorAll('[data-cake-preview]'));

    if (!nodes.length) return false;

    const items = nodes.map(node => {
        let config = {};

        try {
            config = JSON.parse(node.dataset.config || '{}') || {};
        } catch (error) {
            config = {};
        }

        return {
            node,
            view: node.dataset.cakePreview === 'top' ? 'top' : 'front',
            ...createStaticRenderer(config),
        };
    });

    const draw = () => {
        items.forEach(item => {
            const { node, view, renderer, state } = item;

            const layout = renderer.getTextLayout(view);

            const paint = () => view === 'top'
                ? renderer.renderTop(layout)
                : renderer.renderFront(layout);

            let markup = paint();

            const constrained = renderer.constrainTopPosition(state.position);

            if (
                constrained.x !== state.position.x ||
                constrained.y !== state.position.y
            ) {
                state.position = constrained;
                markup = paint();
            }

            node.innerHTML = markup;
        });
    };

    draw();
    ensureCakeFont(draw);

    return true;
}