import { useEffect, useRef, useState } from 'react';
import gsap from 'gsap';
import {
  animateNumericCounter,
  createScrollRevealContext,
  ensureGsapRegistered,
  prefersReducedMotion,
} from './motionPresets';

export function useReducedMotion(): boolean {
  const [reduced, setReduced] = useState<boolean>(() => prefersReducedMotion());

  useEffect(() => {
    if (typeof window === 'undefined' || typeof window.matchMedia !== 'function') return;
    const mq = window.matchMedia('(prefers-reduced-motion: reduce)');
    const onChange = () => setReduced(mq.matches);
    mq.addEventListener('change', onChange);
    return () => mq.removeEventListener('change', onChange);
  }, []);

  return reduced;
}

export function useSketchReveal(selector = '[data-pn-reveal]') {
  const containerRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const el = containerRef.current;
    if (!el) return;
    const ctx = createScrollRevealContext(el, selector);
    return () => ctx.revert();
  }, [selector]);

  return containerRef;
}

export function useCardTilt<T extends HTMLElement = HTMLDivElement>(maxDegrees = 3) {
  const ref = useRef<T>(null);
  const reduced = useReducedMotion();

  useEffect(() => {
    const el = ref.current;
    if (!el || reduced) return;

    const handleMove = (e: PointerEvent) => {
      if (e.pointerType === 'touch') return;
      const rect = el.getBoundingClientRect();
      if (!rect.width || !rect.height) return;
      const px = (e.clientX - rect.left) / rect.width - 0.5;
      const py = (e.clientY - rect.top) / rect.height - 0.5;
      gsap.to(el, {
        rotateY: px * maxDegrees * 2,
        rotateX: -py * maxDegrees * 2,
        transformPerspective: 800,
        duration: 0.2,
        ease: 'power2.out',
      });
    };

    const handleLeave = () => {
      gsap.to(el, {
        rotateX: 0,
        rotateY: 0,
        duration: 0.35,
        ease: 'power3.out',
      });
    };

    el.addEventListener('pointermove', handleMove);
    el.addEventListener('pointerleave', handleLeave);
    return () => {
      el.removeEventListener('pointermove', handleMove);
      el.removeEventListener('pointerleave', handleLeave);
      gsap.killTweensOf(el);
    };
  }, [maxDegrees, reduced]);

  return ref;
}

export function useCountUp(
  target: number,
  options?: { duration?: number; decimals?: number; prefix?: string; suffix?: string }
) {
  const ref = useRef<HTMLSpanElement>(null);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    const tween = animateNumericCounter(el, target, options);
    return () => {
      tween?.kill();
    };
  }, [target, options?.duration, options?.decimals, options?.prefix, options?.suffix]);

  return ref;
}

export function useScrollProgress(): number {
  const [progress, setProgress] = useState(0);

  useEffect(() => {
    ensureGsapRegistered();
    let rafId = 0;

    const update = () => {
      rafId = 0;
      const doc = document.documentElement;
      const maxScroll = doc.scrollHeight - window.innerHeight;
      if (maxScroll <= 0) {
        setProgress(0);
        return;
      }
      setProgress(Math.min(1, Math.max(0, window.scrollY / maxScroll)));
    };

    const onScroll = () => {
      if (!rafId) {
        rafId = window.requestAnimationFrame(update);
      }
    };

    update();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });

    return () => {
      window.removeEventListener('scroll', onScroll);
      window.removeEventListener('resize', onScroll);
      if (rafId) window.cancelAnimationFrame(rafId);
    };
  }, []);

  return progress;
}
