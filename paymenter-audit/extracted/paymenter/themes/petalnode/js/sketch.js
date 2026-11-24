import rough from 'roughjs';
import { animateSketchPaths } from './motion.js';

const observers = new WeakMap();

export function hashSeed(input) {
    const str = String(input ?? 'petalnode');
    let hash = 2166136261;
    for (let i = 0; i < str.length; i += 1) {
        hash ^= str.charCodeAt(i);
        hash = Math.imul(hash, 16777619);
    }
    return Math.abs(hash >>> 0) % 2147483647 || 1337;
}

export function normalizeSketchBounds(width, height, inset = 3) {
    const w = Math.max(24, Math.round(width - inset * 2));
    const h = Math.max(24, Math.round(height - inset * 2));
    return { x: inset, y: inset, w, h };
}

function drawRoughTarget(container) {
    const type = container.dataset.pnRough;
    const svg = container.querySelector(':scope > svg[data-pn-rough-svg]');
    if (!svg) return;

    const strokeColor = container.dataset.pnStroke || '#2B2230';
    const fillColor = container.dataset.pnFill || undefined;
    const fillStyle = container.dataset.pnFillStyle || 'hachure';
    const strokeWidth = parseFloat(container.dataset.pnStrokeWidth || (type === 'underline' ? '3' : type === 'circle' ? '2' : '1.6'));
    const roughness = parseFloat(container.dataset.pnRoughness || '1.4');
    const seed = hashSeed(container.dataset.pnSeed || container.id || type);
    const animateDraw = container.dataset.pnAnimate === 'true';

    const rect = container.getBoundingClientRect();
    if (rect.width <= 0 || rect.height <= 0) return;

    const rc = rough.svg(svg);
    while (svg.firstChild) svg.removeChild(svg.firstChild);

    if (type === 'box') {
        const width = Math.max(40, Math.round(rect.width));
        const height = Math.max(40, Math.round(rect.height));
        svg.setAttribute('viewBox', `0 0 ${width} ${height}`);
        svg.setAttribute('width', `${width}`);
        svg.setAttribute('height', `${height}`);
        const { x, y, w, h } = normalizeSketchBounds(width, height, 3);
        const node = rc.rectangle(x, y, w, h, {
            stroke: strokeColor,
            strokeWidth,
            roughness,
            bowing: 1.2,
            seed,
            fill: fillColor,
            fillStyle: fillColor ? fillStyle : undefined,
            fillWeight: 1,
            hachureGap: 8,
        });
        svg.appendChild(node);
    } else if (type === 'circle') {
        const w = Math.max(44, Math.round(rect.width + 14));
        const h = Math.max(32, Math.round(rect.height + 10));
        svg.setAttribute('viewBox', `0 0 ${w} ${h}`);
        const ellipse = rc.ellipse(w / 2, h / 2, w - 6, h - 6, {
            stroke: strokeColor,
            strokeWidth,
            roughness,
            bowing: 1.3,
            seed,
        });
        svg.appendChild(ellipse);
    } else if (type === 'underline') {
        const width = Math.max(36, Math.round(rect.width));
        const height = 14;
        svg.setAttribute('viewBox', `0 0 ${width} ${height}`);
        const line1 = rc.line(2, 7, width - 2, 5, {
            stroke: strokeColor,
            strokeWidth,
            roughness: 1.6,
            bowing: 1.5,
            seed,
        });
        const line2 = rc.line(6, 10, width - 8, 9, {
            stroke: strokeColor,
            strokeWidth: Math.max(1.5, strokeWidth - 1),
            roughness: 1.4,
            bowing: 1.2,
            seed: seed + 7,
        });
        svg.appendChild(line1);
        svg.appendChild(line2);
    }

    if (animateDraw && !container.dataset.pnDrawn) {
        container.dataset.pnDrawn = '1';
        animateSketchPaths(svg, { duration: 0.55, stagger: 0.05 });
    }
}

export function hydrateRoughElements(root = document) {
    const targets = root.querySelectorAll('[data-pn-rough]');
    targets.forEach((el) => {
        drawRoughTarget(el);
        if (!observers.has(el) && typeof ResizeObserver !== 'undefined') {
            let rafId = 0;
            const ro = new ResizeObserver(() => {
                if (rafId) window.cancelAnimationFrame(rafId);
                rafId = window.requestAnimationFrame(() => drawRoughTarget(el));
            });
            ro.observe(el);
            observers.set(el, ro);
        }
    });
}
