/**
 * Paints the static purple "light rain" backdrop at the screen's full resolution,
 * so it stays sharp on any display instead of stretching a small image.
 */
const STREAK_COLORS = ['#8b5cf6', '#a78bfa', '#c084fc', '#7c3aed', '#fb7185', '#f97316'];
const DROP_COLORS = ['#c4b5fd', '#d8b4fe', '#e9d5ff', '#a78bfa'];

/**
 * Small seeded random generator so the scene looks the same on every page load.
 */
function seededRandom(seed) {
    return () => {
        seed |= 0;
        seed = (seed + 0x6d2b79f5) | 0;
        let t = Math.imul(seed ^ (seed >>> 15), 1 | seed);
        t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;

        return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
    };
}

function pick(random, list) {
    return list[Math.floor(random() * list.length)];
}

function paintScene(context, width, height) {
    const random = seededRandom(20261029);

    const base = context.createLinearGradient(0, 0, 0, height);
    base.addColorStop(0, '#361a60');
    base.addColorStop(0.3, '#331957');
    base.addColorStop(0.6, '#311958');
    base.addColorStop(0.75, '#22113f');
    base.addColorStop(0.9, '#1d103d');
    base.addColorStop(1, '#110b2c');
    context.fillStyle = base;
    context.fillRect(0, 0, width, height);

    const glows = [
        [0.55, 0.05, 0.75, 'rgba(124, 58, 237, 0.12)'],
        [0.15, 0.55, 0.55, 'rgba(91, 33, 182, 0.08)'],
        [0.9, 0.5, 0.5, 'rgba(139, 92, 246, 0.06)'],
    ];
    for (const [x, y, radius, color] of glows) {
        const size = Math.max(width, height) * radius;
        const glow = context.createRadialGradient(x * width, y * height, 0, x * width, y * height, size);
        glow.addColorStop(0, color);
        glow.addColorStop(1, 'rgba(0, 0, 0, 0)');
        context.fillStyle = glow;
        context.fillRect(0, 0, width, height);
    }

    const streakCount = Math.round(width / 9);
    for (let i = 0; i < streakCount; i++) {
        const x = random() * width;
        const end = height * (0.15 + random() * 0.85);
        const color = pick(random, STREAK_COLORS);
        const line = context.createLinearGradient(x, 0, x, end);
        line.addColorStop(0, `${color}00`);
        line.addColorStop(0.35, `${color}${random() < 0.5 ? '33' : '55'}`);
        line.addColorStop(1, `${color}00`);
        context.strokeStyle = line;
        context.lineWidth = 0.4 + random() * 0.8;
        context.beginPath();
        context.moveTo(x, 0);
        context.lineTo(x, end);
        context.stroke();
    }

    const sparkCount = Math.round((width * height) / 2200);
    for (let i = 0; i < sparkCount; i++) {
        const y = height * Math.pow(random(), 1.6);
        const x = random() * width;
        context.globalAlpha = 0.35 + random() * 0.6;
        context.fillStyle = pick(random, [...STREAK_COLORS, '#ffffff']);
        context.beginPath();
        context.arc(x, y, 0.5 + random() * 1.1, 0, Math.PI * 2);
        context.fill();
    }
    context.globalAlpha = 1;

    const dropCount = Math.round(width / 70);
    for (let i = 0; i < dropCount; i++) {
        const x = random() * width;
        const y = height * (0.3 + random() * 0.65);
        const color = pick(random, DROP_COLORS);

        const tail = context.createLinearGradient(x, y - 120, x, y);
        tail.addColorStop(0, `${color}00`);
        tail.addColorStop(1, `${color}cc`);
        context.strokeStyle = tail;
        context.lineWidth = 1;
        context.beginPath();
        context.moveTo(x, y - 120);
        context.lineTo(x, y);
        context.stroke();

        const halo = context.createRadialGradient(x, y, 0, x, y, 16);
        halo.addColorStop(0, 'rgba(196, 181, 253, 0.55)');
        halo.addColorStop(1, 'rgba(124, 58, 237, 0)');
        context.fillStyle = halo;
        context.fillRect(x - 16, y - 16, 32, 32);

        context.fillStyle = '#f5f3ff';
        context.beginPath();
        context.ellipse(x, y, 1.6, 2.6, 0, 0, Math.PI * 2);
        context.fill();
    }
}

export function drawLightScene(canvas) {
    const context = canvas.getContext('2d');
    let paintedWidth = 0;

    const paint = () => {
        const width = window.innerWidth;
        // Phones change the height while scrolling (address bar), so only repaint on width changes
        if (width === paintedWidth) {
            return;
        }
        paintedWidth = width;

        const height = Math.max(window.innerHeight, window.screen.height);
        const pixelRatio = Math.min(window.devicePixelRatio || 1, 3);
        canvas.width = width * pixelRatio;
        canvas.height = height * pixelRatio;
        canvas.style.height = `${height}px`;
        context.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);
        paintScene(context, width, height);
    };

    paint();
    window.addEventListener('resize', paint);
}
