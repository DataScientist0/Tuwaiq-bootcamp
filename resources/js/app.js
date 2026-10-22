import { startLightRain } from './light-rain';

const lightRainCanvas = document.getElementById('light-rain');
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (lightRainCanvas && ! prefersReducedMotion) {
    startLightRain(lightRainCanvas);
}
