import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

let registered = false;
let activeContexts = [];
let scrollCleanup = null;

export function ensureGsapRegistered() {
    if (typeof window === 'undefined' || registered) return;
    gsap.registerPlugin(ScrollTrigger);
    registered = true;
}

export function prefersReducedMotion() {
    if (typeof window === 'undefined' || typeof window.matchMedia !== 'function') {
        return false;
    }
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

export function animateSketchPaths(container, options = {}) {
    ensureGsapRegistered();
    return gsap.context(() => {
        if (prefersReducedMotion()) return;
        const paths = container.querySelectorAll('path, rect, circle, ellipse, polyline');
        const measurable = [];
        paths.forEach((el) => {
            if (typeof el.getTotalLength === 'function') {
                const len = el.getTotalLength();
                if (len > 0 && Number.isFinite(len)) {
                    el.style.strokeDasharray = `${len}`;
                    el.style.strokeDashoffset = `${len}`;
                    measurable.push(el);
                }
            }
        });
        if (measurable.length > 0) {
            gsap.to(measurable, {
                strokeDashoffset: 0,
                duration: options.duration ?? 0.6,
                stagger: options.stagger ?? 0.04,
                delay: options.delay ?? 0,
                ease: 'power2.out',
                clearProps: 'strokeDasharray,strokeDashoffset',
            });
        }
    }, container);
}

export function initScrollReveals(root = document) {
    ensureGsapRegistered();
    const ctx = gsap.context(() => {
        if (prefersReducedMotion()) return;
        const targets = gsap.utils.toArray('[data-pn-reveal]:not([data-pn-revealed])', root);
        targets.forEach((el, idx) => {
            el.dataset.pnRevealed = '1';
            gsap.from(el, {
                y: 16,
                opacity: 0,
                duration: 0.45,
                delay: Math.min(idx * 0.035, 0.18),
                ease: 'power3.out',
                clearProps: 'transform,opacity',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 92%',
                    once: true,
                },
            });
        });
    }, root === document ? document.body : root);
    activeContexts.push(ctx);
}

export function initCardTilt(root = document) {
    if (prefersReducedMotion()) return;
    if (typeof window.matchMedia !== 'function' || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
        return;
    }
    const cards = root.querySelectorAll('[data-pn-tilt]:not([data-pn-tilt-bound])');
    cards.forEach((card) => {
        card.dataset.pnTiltBound = '1';
        const maxDeg = parseFloat(card.dataset.pnTiltMax || '2.2');
        card.addEventListener('pointermove', (e) => {
            const r = card.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width - 0.5;
            const py = (e.clientY - r.top) / r.height - 0.5;
            card.style.transform = `perspective(720px) rotateX(${(-py * maxDeg).toFixed(2)}deg) rotateY(${(px * maxDeg).toFixed(2)}deg) translateY(-2px)`;
        });
        card.addEventListener('pointerleave', () => {
            card.style.transform = '';
        });
    });
}

export function initNumericCounters(root = document) {
    ensureGsapRegistered();
    const counters = root.querySelectorAll('[data-pn-count]:not([data-pn-counted])');
    if (!counters.length) return;

    counters.forEach((el) => {
        el.dataset.pnCounted = '1';
        const target = parseFloat(el.dataset.pnCount || '0');
        const prefix = el.dataset.pnPrefix || '';
        const suffix = el.dataset.pnSuffix || '';
        const decimals = parseInt(el.dataset.pnDecimals || '0', 10);
        const format = (val) => `${prefix}${val.toFixed(decimals)}${suffix}`;

        if (prefersReducedMotion()) {
            el.textContent = format(target);
            return;
        }

        const state = { val: 0 };
        const ctx = gsap.context(() => {
            gsap.to(state, {
                val: target,
                duration: 0.75,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 92%',
                    once: true,
                },
                onUpdate: () => {
                    el.textContent = format(state.val);
                },
                onComplete: () => {
                    el.textContent = format(target);
                },
            });
        }, el);
        activeContexts.push(ctx);
    });
}

export function initScrollProgress() {
    if (scrollCleanup) {
        scrollCleanup();
        scrollCleanup = null;
    }
    const bar = document.querySelector('.pn-scroll-progress');
    if (!bar) return;
    if (prefersReducedMotion()) {
        bar.style.display = 'none';
        return;
    }
    let ticking = false;
    const update = () => {
        ticking = false;
        const max = document.documentElement.scrollHeight - window.innerHeight;
        const k = max > 40 ? Math.min(1, window.scrollY / max) : 0;
        bar.style.setProperty('--pn-scroll', String(k));
    };
    const onScroll = () => {
        if (!ticking) {
            ticking = true;
            window.requestAnimationFrame(update);
        }
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', update, { passive: true });
    update();
    scrollCleanup = () => {
        window.removeEventListener('scroll', onScroll);
        window.removeEventListener('resize', update);
    };
}

export function teardownMotion() {
    while (activeContexts.length > 0) {
        const ctx = activeContexts.pop();
        ctx?.revert();
    }
}

export function initPetalnodeMotion(root = document) {
    initScrollProgress();
    initScrollReveals(root);
    initCardTilt(root);
    initNumericCounters(root);
}
