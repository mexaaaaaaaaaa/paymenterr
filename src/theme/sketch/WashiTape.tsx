import React from 'react';

export interface WashiTapeProps {
  tone?: 'blossom' | 'moss' | 'lantern' | 'bark';
  rotation?: number;
  className?: string;
  label?: string;
}

const TONE_CLASSES: Record<NonNullable<WashiTapeProps['tone']>, string> = {
  blossom: 'bg-[var(--pn-blossom-200)]/85 border-[var(--pn-blossom-400)]/40 text-[var(--pn-ink)]',
  moss: 'bg-[var(--pn-moss)]/30 border-[var(--pn-moss)]/45 text-[var(--pn-ink)]',
  lantern: 'bg-[var(--pn-lantern)]/55 border-[var(--pn-bark)]/25 text-[var(--pn-ink)]',
  bark: 'bg-[var(--pn-paper-shade)]/90 border-[var(--pn-bark)]/30 text-[var(--pn-bark)]',
};

export const WashiTape: React.FC<WashiTapeProps> = ({
  tone = 'blossom',
  rotation = -3,
  className = '',
  label,
}) => {
  return (
    <span
      aria-hidden={label ? undefined : true}
      style={{ transform: `rotate(${rotation}deg)` }}
      className={`inline-flex h-6 min-w-[72px] items-center justify-center border-x border-dashed px-3 font-hand text-xs font-semibold tracking-wide shadow-[0_1px_2px_rgba(43,34,48,0.12)] backdrop-blur-[1px] select-none ${TONE_CLASSES[tone]} ${className}`}
    >
      {label ?? ''}
    </span>
  );
};
