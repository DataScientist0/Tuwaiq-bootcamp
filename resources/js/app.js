import { startLightRain } from './light-rain';
import { drawLightScene } from './light-scene';

const lightSceneCanvas = document.getElementById('light-scene');
const lightRainCanvas = document.getElementById('light-rain');
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (lightSceneCanvas) {
    drawLightScene(lightSceneCanvas);
}

if (lightRainCanvas && ! prefersReducedMotion) {
    startLightRain(lightRainCanvas);
}
