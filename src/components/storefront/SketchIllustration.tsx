import React, { useState } from 'react';
import { ChevronDown } from 'lucide-react';
import { HandIcon, NodeyMascot, SproutMascot } from '../../theme/sketch';

export const THEME_ILLUSTRATION_PATHS = {
  island: '/src/assets/images/petalnode_hero_island_1795530389306.jpg',
  craft: '/src/assets/images/petalnode_server_craft_1795530389652.jpg',
  globe: '/src/assets/images/petalnode_network_globe_1795530390513.jpg',
} as const;

export const SketchIllustration: React.FC<{
  kind: 'island' | 'craft' | 'globe';
  src?: string;
  alt: string;
  className?: string;
}> = ({ kind, src, alt, className = '' }) => {
  const [failed, setFailed] = useState(false);

  if (failed) {
    return (
      <div
        className={`flex h-full min-h-[200px] w-full flex-col items-center justify-center gap-3 bg-[var(--pn-paper-shade)]/60 p-6 text-center ${className}`}
      >
        <div className="flex items-center gap-3">
          <NodeyMascot mood="happy" size={72} />
          <SproutMascot stage="blooming" size={64} />
        </div>
        <div className="flex items-center gap-1.5 text-xs font-semibold text-[var(--pn-ink-soft)]">
          <HandIcon name="blossom" size={14} className="text-[var(--pn-blossom-600)]" />
          <span>{alt}</span>
        </div>
      </div>
    );
  }

  return (
    <img
      src={src || THEME_ILLUSTRATION_PATHS[kind]}
      alt={alt}
      referrerPolicy="no-referrer"
      onError={() => setFailed(true)}
      className={`block h-full w-full object-cover ${className}`}
    />
  );
};

export const FloatingPetalsOverlay: React.FC = () => (
  <svg
    aria-hidden="true"
    viewBox="0 0 420 280"
    fill="none"
    className="pointer-events-none absolute inset-0 h-full w-full opacity-80"
  >
    <g className="pn-animate-float">
      <path d="M44 48C50 40 58 43 56 52C52 57 44 55 44 48Z" fill="#FFC9D6" stroke="#2B2230" strokeWidth="1.2" />
      <path d="M360 38C366 30 374 34 371 42C367 47 360 44 360 38Z" fill="#FF8FAB" stroke="#2B2230" strokeWidth="1.2" />
      <path d="M342 214C348 206 356 210 353 218C349 223 342 220 342 214Z" fill="#FFC9D6" stroke="#2B2230" strokeWidth="1.2" />
    </g>
    <g className="pn-animate-sway" style={{ transformOrigin: '210px 140px' }}>
      <path d="M28 192C34 184 42 188 39 196C35 201 28 198 28 192Z" fill="#FF8FAB" stroke="#2B2230" strokeWidth="1.2" />
      <path d="M214 24C220 17 228 21 225 28C221 33 214 30 214 24Z" fill="#FFC9D6" stroke="#2B2230" strokeWidth="1.2" />
    </g>
  </svg>
);

export const SketchAccordion: React.FC<{
  items: Array<{ id?: string; question: string; answer: string }>;
}> = ({ items }) => {
  const [openIdx, setOpenIdx] = useState<number | null>(0);

  return (
    <div className="divide-y divide-[var(--pn-paper-line)] rounded-[var(--pn-radius-lg)] border border-[var(--pn-paper-line)] bg-[#FFFDF9] shadow-[var(--pn-shadow-paper-sm)]">
      {items.map((item, idx) => {
        const isOpen = openIdx === idx;
        return (
          <div key={item.id ?? item.question} className="px-5 py-4">
            <button
              type="button"
              aria-expanded={isOpen}
              onClick={() => setOpenIdx(isOpen ? null : idx)}
              className="flex w-full items-center justify-between gap-4 text-left font-display text-base font-bold text-[var(--pn-ink)] cursor-pointer"
            >
              <span>{item.question}</span>
              <ChevronDown
                size={18}
                aria-hidden="true"
                className={`shrink-0 text-[var(--pn-ink-soft)] transition-transform duration-200 ${
                  isOpen ? 'rotate-180 text-[var(--pn-blossom-700)]' : ''
                }`}
              />
            </button>
            <div
              className={`grid transition-[grid-template-rows,opacity] duration-200 ease-[var(--pn-ease-settle)] ${
                isOpen ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'
              }`}
            >
              <div className="overflow-hidden">
                <p className="pt-2.5 text-sm leading-relaxed text-[var(--pn-ink-soft)]">
                  {item.answer}
                </p>
              </div>
            </div>
          </div>
        );
      })}
    </div>
  );
};
