import React from 'react';

export interface StampProps {
  label: string;
  tone?: 'blossom' | 'moss' | 'bark' | 'ink';
  rotateDeg?: number;
  className?: string;
}

const STAMP_TONE: Record<NonNullable<StampProps['tone']>, string> = {
  blossom: 'border-[var(--pn-blossom-700)] text-[var(--pn-blossom-700)] bg-[var(--pn-blossom-50)]/65',
  moss: 'border-[var(--pn-moss-700)] text-[var(--pn-moss-700)] bg-[var(--pn-moss-100)]/70',
  bark: 'border-[var(--pn-bark)] text-[var(--pn-bark)] bg-[var(--pn-bark-100)]/65',
  ink: 'border-[var(--pn-ink)] text-[var(--pn-ink)] bg-[var(--pn-paper-shade)]/65',
};

export const Stamp: React.FC<StampProps> = ({
  label,
  tone = 'blossom',
  rotateDeg = -4,
  className = '',
}) => {
  return (
    <span
      style={{ transform: `rotate(${rotateDeg}deg)` }}
      className={`inline-flex items-center justify-center rounded-[5px] border-2 border-dashed px-2.5 py-0.5 font-pixel text-xs font-bold tracking-wider uppercase select-none whitespace-nowrap ${STAMP_TONE[tone]} ${className}`}
    >
      {label}
    </span>
  );
};
