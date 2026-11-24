import React from 'react';
import { WashiTape } from './WashiTape';

export interface PolaroidFrameProps {
  caption: string;
  subcaption?: string;
  tapeTone?: 'blossom' | 'moss' | 'lantern' | 'bark';
  tiltDeg?: number;
  className?: string;
  children: React.ReactNode;
}

export const PolaroidFrame: React.FC<PolaroidFrameProps> = ({
  caption,
  subcaption,
  tapeTone = 'blossom',
  tiltDeg = -1.5,
  className = '',
  children,
}) => {
  return (
    <figure
      style={{ transform: `rotate(${tiltDeg}deg)` }}
      className={`group relative rounded-[4px] border border-[var(--pn-paper-line)] bg-[#FFFDF9] p-3 pb-4 shadow-[var(--pn-shadow-paper-md)] transition-transform duration-200 hover:rotate-0 ${className}`}
    >
      <div className="pointer-events-none absolute -top-3 left-1/2 -translate-x-1/2 transition-transform duration-200 ease-[var(--pn-ease-spring)] group-hover:-translate-y-0.5 group-hover:scale-105">
        <WashiTape tone={tapeTone} rotation={tiltDeg * -1.4} />
      </div>
      <div className="overflow-hidden rounded-[2px] border border-[var(--pn-paper-line)] bg-[var(--pn-paper-shade)]/45">
        {children}
      </div>
      <figcaption className="mt-3 px-1">
        <div className="font-hand text-sm font-semibold text-[var(--pn-ink)]">{caption}</div>
        {subcaption && (
          <div className="mt-0.5 text-xs text-[var(--pn-ink-soft)]">{subcaption}</div>
        )}
      </figcaption>
    </figure>
  );
};
