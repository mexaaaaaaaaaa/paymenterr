import React from 'react';
import { InkArrow } from './InkArrow';

export interface AnnotationProps {
  note: string;
  arrowDirection?: 'right' | 'left' | 'down' | 'curved-down-right';
  tone?: 'ink' | 'blossom' | 'bark' | 'moss';
  className?: string;
}

const TONE_TEXT: Record<NonNullable<AnnotationProps['tone']>, string> = {
  ink: 'text-[var(--pn-ink)]',
  blossom: 'text-[var(--pn-blossom-700)]',
  bark: 'text-[var(--pn-bark)]',
  moss: 'text-[var(--pn-moss-700)]',
};

export const Annotation: React.FC<AnnotationProps> = ({
  note,
  arrowDirection = 'curved-down-right',
  tone = 'bark',
  className = '',
}) => {
  return (
    <div className={`inline-flex items-center gap-2 font-hand text-sm leading-snug ${TONE_TEXT[tone]} ${className}`}>
      <span>{note}</span>
      <InkArrow direction={arrowDirection} className="shrink-0" />
    </div>
  );
};
