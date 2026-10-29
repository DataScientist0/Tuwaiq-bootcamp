/**
 * Draws glowing streaks of light that fall slowly down the page,
 * matching the purple "light rain" background image.
 */
const COLORS = ['#a78bfa', '#c084fc', '#e9d5ff', '#f2a54a', '#f472b6'];

export function startLightRain(canvas) {
    const context = canvas.getContext('2d');
    const pixelRatio = Math.min(window.devicePixelRatio || 1, 3);
    let width = 0;
    let height = 0;
    let drops = [];

    const makeDrop = (anywhereOnScreen) => ({
        x: Math.random() * width,
        y: anywhereOnScreen ? Math.random() * height : -Math.random() * height * 0.3,
        length: 40 + Math.random() * 140,
        speed: 1.5 + Math.random() * 3,
        size: 0.8 + Math.random() * 1.6,
        color: COLORS[Math.floor(Math.random() * COLORS.length)],
        opacity: 0.3 + Math.random() * 0.6,
    });

    const resize = () => {
        // Phones change the height while scrolling (address bar), so only restart on width changes
        if (window.innerWidth === width) {
            return;
        }
        width = window.innerWidth;
        height = Math.max(window.innerHeight, window.screen.height);
        canvas.width = width * pixelRatio;
        canvas.height = height * pixelRatio;
        canvas.style.height = `${height}px`;
        context.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);

        const dropCount = Math.round((width * height) / 14000);
        drops = Array.from({ length: dropCount }, () => makeDrop(true));
    };

    let lastTime = performance.now();

    const drawFrame = (now) => {
        // Speeds are tuned for 60 fps; scale them so slower screens move at the same pace
        const frames = Math.min((now - lastTime) / (1000 / 60), 4);
        lastTime = now;
        context.clearRect(0, 0, width, height);

        for (const drop of drops) {
            drop.y += drop.speed * frames;
            if (drop.y - drop.length > height) {
                Object.assign(drop, makeDrop(false));
            }

            const trail = context.createLinearGradient(drop.x, drop.y - drop.length, drop.x, drop.y);
            trail.addColorStop(0, 'transparent');
            trail.addColorStop(1, drop.color);

            context.globalAlpha = drop.opacity;
            context.strokeStyle = trail;
            context.lineWidth = drop.size * 0.5;
            context.beginPath();
            context.moveTo(drop.x, drop.y - drop.length);
            context.lineTo(drop.x, drop.y);
            context.stroke();

            context.shadowBlur = 12;
            context.shadowColor = drop.color;
            context.fillStyle = drop.color;
            context.beginPath();
            context.arc(drop.x, drop.y, drop.size, 0, Math.PI * 2);
            context.fill();
            context.shadowBlur = 0;
        }

        context.globalAlpha = 1;
        requestAnimationFrame(drawFrame);
    };

    resize();
    window.addEventListener('resize', resize);
    requestAnimationFrame(drawFrame);
}
