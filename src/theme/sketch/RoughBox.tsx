import React, { useEffect, useRef } from 'react';
import rough from 'roughjs';
import { hashSeed, normalizeSketchBounds } from '../utils/sketchGeometry';
import { animateSketchPaths } from '../animations/motionPresets';

export interface RoughBoxProps {
  children: React.ReactNode;
  className?: string;
  strokeColor?: string;
  fillColor?: string;
  fillStyle?: 'solid' | 'hachure' | 'zigzag' | 'cross-hatch' | 'dots';
  strokeWidth?: number;
  roughness?: number;
  seedKey?: string | number;
  animateDraw?: boolean;
  paddingClass?: string;
}

export const RoughBox: React.FC<RoughBoxProps> = ({
  children,
  className = '',
  strokeColor = '#2B2230',
  fillColor,
  fillStyle = 'hachure',
  strokeWidth = 1.6,
  roughness = 1.35,
  seedKey = 'rough-box',
  animateDraw = false,
  paddingClass = 'p-5',
}) => {
  const boxRef = useRef<HTMLDivElement>(null);
  const svgRef = useRef<SVGSVGElement>(null);

  useEffect(() => {
    const box = boxRef.current;
    const svg = svgRef.current;
    if (!box || !svg) return;

    let ctx: { revert: () => void } | null = null;
    let rafId = 0;

    const draw = () => {
      const rect = box.getBoundingClientRect();
      const width = Math.max(40, Math.round(rect.width));
      const height = Math.max(40, Math.round(rect.height));

      svg.setAttribute('viewBox', `0 0 ${width} ${height}`);
      svg.setAttribute('width', `${width}`);
      svg.setAttribute('height', `${height}`);
      while (svg.firstChild) {
        svg.removeChild(svg.firstChild);
      }

      const rc = rough.svg(svg);
      const { x, y, w, h } = normalizeSketchBounds(width, height, 3);
      const node = rc.rectangle(x, y, w, h, {
        stroke: strokeColor,
        strokeWidth,
        roughness,
        bowing: 1.2,
        seed: hashSeed(seedKey),
        fill: fillColor,
        fillStyle: fillColor ? fillStyle : undefined,
        fillWeight: 1,
        hachureGap: 8,
      });
      svg.appendChild(node);

      if (animateDraw) {
        ctx?.revert();
        ctx = animateSketchPaths(svg, { duration: 0.6, stagger: 0.03 });
      }
    };

    draw();

    const ro = new ResizeObserver(() => {
      if (rafId) window.cancelAnimationFrame(rafId);
      rafId = window.requestAnimationFrame(draw);
    });
    ro.observe(box);

    return () => {
      ro.disconnect();
      if (rafId) window.cancelAnimationFrame(rafId);
      ctx?.revert();
    };
  }, [strokeColor, fillColor, fillStyle, strokeWidth, roughness, seedKey, animateDraw]);

  return (
    <div ref={boxRef} className={`relative ${paddingClass} ${className}`}>
      <svg
        ref={svgRef}
        aria-hidden="true"
        className="pointer-events-none absolute inset-0 h-full w-full overflow-visible"
      />
      <div className="relative z-10">{children}</div>
    </div>
  );
};
