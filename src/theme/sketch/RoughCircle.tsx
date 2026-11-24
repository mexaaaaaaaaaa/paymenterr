import React, { useEffect, useRef } from 'react';
import rough from 'roughjs';
import { hashSeed } from '../utils/sketchGeometry';

export interface RoughCircleProps {
  children: React.ReactNode;
  color?: string;
  strokeWidth?: number;
  seedKey?: string | number;
  className?: string;
}

export const RoughCircle: React.FC<RoughCircleProps> = ({
  children,
  color = '#E2557B',
  strokeWidth = 2,
  seedKey = 'rough-circle',
  className = '',
}) => {
  const wrapRef = useRef<HTMLSpanElement>(null);
  const svgRef = useRef<SVGSVGElement>(null);

  useEffect(() => {
    const wrap = wrapRef.current;
    const svg = svgRef.current;
    if (!wrap || !svg) return;

    const draw = () => {
      const rect = wrap.getBoundingClientRect();
      const w = Math.max(44, Math.round(rect.width + 16));
      const h = Math.max(32, Math.round(rect.height + 12));
      svg.setAttribute('viewBox', `0 0 ${w} ${h}`);
      while (svg.firstChild) svg.removeChild(svg.firstChild);

      const rc = rough.svg(svg);
      const ellipse = rc.ellipse(w / 2, h / 2, w - 6, h - 6, {
        stroke: color,
        strokeWidth,
        roughness: 1.5,
        bowing: 1.3,
        seed: hashSeed(seedKey),
      });
      svg.appendChild(ellipse);
    };

    draw();
    const ro = new ResizeObserver(draw);
    ro.observe(wrap);
    return () => ro.disconnect();
  }, [color, strokeWidth, seedKey]);

  return (
    <span ref={wrapRef} className={`relative inline-flex items-center justify-center px-2 py-0.5 ${className}`}>
      <svg
        ref={svgRef}
        aria-hidden="true"
        className="pointer-events-none absolute -inset-1.5 h-[calc(100%+12px)] w-[calc(100%+12px)] overflow-visible"
      />
      <span className="relative z-10">{children}</span>
    </span>
  );
};
