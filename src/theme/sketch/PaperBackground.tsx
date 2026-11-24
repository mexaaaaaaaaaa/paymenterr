import React from 'react';

export interface PaperBackgroundProps {
  variant?: 'plain' | 'grid' | 'notebook';
  showMarginRule?: boolean;
  className?: string;
  children: React.ReactNode;
}

export const PaperBackground: React.FC<PaperBackgroundProps> = ({
  variant = 'plain',
  showMarginRule = false,
  className = '',
  children,
}) => {
  const textureClass =
    variant === 'grid'
      ? 'pn-paper-grain pn-sketch-grid'
      : variant === 'notebook'
        ? 'pn-paper-grain pn-notebook-lines'
        : 'pn-paper-grain';

  return (
    <div className={`relative min-h-screen bg-[var(--pn-paper)] text-[var(--pn-ink)] ${textureClass} ${className}`}>
      {showMarginRule && (
        <div
          aria-hidden="true"
          className="pointer-events-none fixed inset-y-0 left-6 hidden w-px bg-[var(--pn-blossom-200)]/70 xl:block"
        />
      )}
      {children}
    </div>
  );
};
