import React from 'react';
import { Loader2 } from 'lucide-react';

export interface SketchButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: 'primary' | 'secondary' | 'moss' | 'bark' | 'ghost';
  size?: 'sm' | 'md' | 'lg';
  loading?: boolean;
  iconLeft?: React.ReactNode;
  iconRight?: React.ReactNode;
}

const VARIANT_CLASSES: Record<NonNullable<SketchButtonProps['variant']>, string> = {
  primary:
    'bg-[var(--pn-blossom-700)] text-white border-[var(--pn-ink)] shadow-[var(--pn-shadow-ink-hard)] hover:bg-[#9E2B4C] active:translate-y-[1px]',
  secondary:
    'bg-[#FFFDF9] text-[var(--pn-ink)] border-[var(--pn-ink)] shadow-[var(--pn-shadow-paper-sm)] hover:bg-[var(--pn-paper-shade)] active:translate-y-[1px]',
  moss:
    'bg-[var(--pn-moss-700)] text-white border-[var(--pn-ink)] shadow-[var(--pn-shadow-ink-hard)] hover:bg-[#316123] active:translate-y-[1px]',
  bark:
    'bg-[var(--pn-bark)] text-[var(--pn-paper)] border-[var(--pn-ink)] shadow-[var(--pn-shadow-ink-hard)] hover:bg-[#573B2D] active:translate-y-[1px]',
  ghost:
    'bg-transparent text-[var(--pn-ink)] border-transparent hover:bg-[var(--pn-paper-shade)]/80 hover:border-[var(--pn-paper-line)]',
};

const SIZE_CLASSES: Record<NonNullable<SketchButtonProps['size']>, string> = {
  sm: 'min-h-[40px] px-4 py-2 text-sm',
  md: 'min-h-[44px] px-5 py-2.5 text-sm',
  lg: 'min-h-[48px] px-6 py-3 text-base',
};

export const SketchButton: React.FC<SketchButtonProps> = ({
  variant = 'primary',
  size = 'md',
  loading = false,
  disabled,
  iconLeft,
  iconRight,
  className = '',
  children,
  type = 'button',
  ...rest
}) => {
  const isDisabled = disabled || loading;

  return (
    <button
      type={type}
      disabled={isDisabled}
      aria-busy={loading || undefined}
      className={`inline-flex items-center justify-center gap-2 rounded-[var(--pn-radius-md)] border-[1.5px] font-display font-semibold tracking-tight whitespace-nowrap shrink-0 transition-[transform,background-color,box-shadow] duration-[var(--pn-duration-fast)] ease-[var(--pn-ease-settle)] cursor-pointer disabled:cursor-not-allowed disabled:opacity-[var(--pn-state-disabled-opacity)] ${VARIANT_CLASSES[variant]} ${SIZE_CLASSES[size]} ${className}`}
      {...rest}
    >
      {loading ? (
        <Loader2 size={16} className="animate-spin shrink-0" aria-hidden="true" />
      ) : (
        iconLeft && <span className="shrink-0">{iconLeft}</span>
      )}
      <span>{children}</span>
      {!loading && iconRight && <span className="shrink-0">{iconRight}</span>}
    </button>
  );
};
