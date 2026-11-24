import React, { useEffect, useRef } from 'react';
import rough from 'roughjs';
import { hashSeed } from '../utils/sketchGeometry';
import { animateSketchPaths } from '../animations/motionPresets';

export interface RoughUnderlineProps {
  children: React.ReactNode;
  color?: string;
  strokeWidth?: number;
  seedKey?: string | number;
  animateDraw?: boolean;
  className?: string;
}

export const RoughUnderline: React.FC<RoughUnderlineProps> = ({
  children,
  color = '#FF8FAB',
  strokeWidth = 3,
  seedKey = 'rough-underline',
  animateDraw = true,
  className = '',
}) => {
  const wrapRef = useRef<HTMLSpanElement>(null);
  const svgRef = useRef<SVGSVGElement>(null);

  useEffect(() => {
    const wrap = wrapRef.current;
    const svg = svgRef.current;
    if (!wrap || !svg) return;

    let ctx: { revert: () => void } | null = null;
    const draw = () => {
      const width = Math.max(36, Math.round(wrap.getBoundingClientRect().width));
      const height = 14;
      svg.setAttribute('viewBox', `0 0 ${width} ${height}`);
      while (svg.firstChild) svg.removeChild(svg.firstChild);

      const rc = rough.svg(svg);
      const seed = hashSeed(seedKey);
      const line1 = rc.line(2, 7, width - 2, 5, {
        stroke: color,
        strokeWidth,
        roughness: 1.6,
        bowing: 1.5,
        seed,
      });
      const line2 = rc.line(6, 10, width - 8, 9, {
        stroke: color,
        strokeWidth: Math.max(1.5, strokeWidth - 1),
        roughness: 1.4,
        bowing: 1.2,
        seed: seed + 7,
      });
      svg.appendChild(line1);
      svg.appendChild(line2);

      if (animateDraw) {
        ctx?.revert();
        ctx = animateSketchPaths(svg, { duration: 0.5, stagger: 0.08 });
      }
    };

    draw();
    const ro = new ResizeObserver(draw);
    ro.observe(wrap);
    return () => {
      ro.disconnect();
      ctx?.revert();
    };
  }, [color, strokeWidth, seedKey, animateDraw]);

  return (
    <span ref={wrapRef} className={`relative inline-block pb-1 ${className}`}>
      <span className="relative z-10">{children}</span>
      <svg
        ref={svgRef}
        aria-hidden="true"
        className="pointer-events-none absolute bottom-0 left-0 h-3.5 w-full overflow-visible"
        preserveAspectRatio="none"
      />
    </span>
  );
};
