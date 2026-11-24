import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

let registered = false;

export function ensureGsapRegistered(): void {
  if (typeof window === 'undefined' || registered) return;
  gsap.registerPlugin(ScrollTrigger);
  registered = true;
}

export function prefersReducedMotion(): boolean {
  if (typeof window === 'undefined' || typeof window.matchMedia !== 'function') {
    return false;
  }
  return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/**
 * DrawSVG-style path animation using standard SVG getTotalLength() + GSAP strokeDashoffset.
 * Requires no proprietary plugins and cleans up cleanly via gsap.context().
 */
export function animateSketchPaths(
  container: HTMLElement | SVGElement,
  options?: { duration?: number; stagger?: number; delay?: number }
): gsap.Context {
  ensureGsapRegistered();
  return gsap.context(() => {
    if (prefersReducedMotion()) return;
    const paths = container.querySelectorAll<SVGGeometryElement>('path, rect, circle, ellipse, polyline');
    const measurable: SVGGeometryElement[] = [];

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
        duration: options?.duration ?? 0.65,
        stagger: options?.stagger ?? 0.04,
        delay: options?.delay ?? 0,
        ease: 'power2.out',
        clearProps: 'strokeDasharray,strokeDashoffset',
      });
    }
  }, container);
}

/**
 * Scroll-triggered staggered entrance for sketchbook cards and editorial sections.
 */
export function createScrollRevealContext(
  root: HTMLElement,
  selector = '[data-pn-reveal]'
): gsap.Context {
  ensureGsapRegistered();
  return gsap.context(() => {
    if (prefersReducedMotion()) return;
    const targets = gsap.utils.toArray<HTMLElement>(selector, root);
    targets.forEach((el, idx) => {
      gsap.from(el, {
        y: 16,
        opacity: 0,
        duration: 0.45,
        delay: Math.min(idx * 0.04, 0.2),
        ease: 'power3.out',
        clearProps: 'transform,opacity',
        scrollTrigger: {
          trigger: el,
          start: 'top 90%',
          once: true,
        },
      });
    });
  }, root);
}

/**
 * Animate numeric counters with tabular-nums precision once scrolled into view.
 */
export function animateNumericCounter(
  el: HTMLElement,
  targetValue: number,
  options?: { duration?: number; decimals?: number; prefix?: string; suffix?: string }
): gsap.core.Tween | null {
  ensureGsapRegistered();
  const format = (val: number) =>
    `${options?.prefix ?? ''}${val.toFixed(options?.decimals ?? 0)}${options?.suffix ?? ''}`;

  if (prefersReducedMotion()) {
    el.textContent = format(targetValue);
    return null;
  }

  const state = { val: 0 };
  return gsap.to(state, {
    val: targetValue,
    duration: options?.duration ?? 0.75,
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
      el.textContent = format(targetValue);
    },
  });
}
