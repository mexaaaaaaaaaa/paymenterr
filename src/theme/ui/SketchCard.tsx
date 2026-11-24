import React from 'react';
import { useCardTilt } from '../animations/hooks';

export interface SketchCardProps {
  children: React.ReactNode;
  surface?: 'paper' | 'shade' | 'blossom' | 'moss';
  tilt?: boolean;
  className?: string;
}

const SURFACE_CLASSES: Record<NonNullable<SketchCardProps['surface']>, string> = {
  paper: 'bg-[#FFFDF9] border-[var(--pn-paper-line)]',
  shade: 'bg-[var(--pn-paper-shade)]/65 border-[var(--pn-paper-line)]',
  blossom: 'bg-[var(--pn-blossom-50)] border-[var(--pn-blossom-200)]',
  moss: 'bg-[var(--pn-moss-100)] border-[var(--pn-moss)]/35',
};

export const SketchCard: React.FC<SketchCardProps> = ({
  children,
  surface = 'paper',
  tilt = false,
  className = '',
}) => {
  const tiltRef = useCardTilt<HTMLDivElement>(tilt ? 2.5 : 0);

  return (
    <div
      ref={tilt ? tiltRef : undefined}
      className={`rounded-[var(--pn-radius-lg)] border p-6 shadow-[var(--pn-shadow-paper-sm)] transition-shadow duration-200 hover:shadow-[var(--pn-shadow-paper-md)] ${SURFACE_CLASSES[surface]} ${className}`}
    >
      {children}
    </div>
  );
};
