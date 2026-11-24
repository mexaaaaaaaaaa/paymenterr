import React from 'react';
import { Loader2, RefreshCw } from 'lucide-react';
import { NodeyMascot } from '../sketch/NodeyMascot';
import { SproutMascot } from '../sketch/SproutMascot';
import { SketchButton } from './SketchButton';

export const SketchSpinner: React.FC<{ size?: number; label?: string }> = ({
  size = 20,
  label = 'Loading',
}) => (
  <span role="status" aria-label={label} className="inline-flex items-center gap-2 text-sm text-[var(--pn-ink-soft)]">
    <Loader2 size={size} className="animate-spin text-[var(--pn-blossom-600)]" aria-hidden="true" />
    <span>{label}</span>
  </span>
);

export const LoadingState: React.FC<{ message?: string }> = ({
  message = 'Sketching your workspace...',
}) => (
  <div className="flex flex-col items-center justify-center rounded-[var(--pn-radius-lg)] border border-[var(--pn-paper-line)] bg-[#FFFDF9] px-6 py-12 text-center">
    <NodeyMascot mood="working" size={88} />
    <p className="mt-3 font-display text-base font-semibold text-[var(--pn-ink)]">{message}</p>
    <div className="mt-2">
      <SketchSpinner label="Loading data" />
    </div>
  </div>
);

export const EmptyState: React.FC<{
  title: string;
  description: string;
  actionLabel?: string;
  onAction?: () => void;
}> = ({ title, description, actionLabel, onAction }) => (
  <div className="flex flex-col items-center justify-center rounded-[var(--pn-radius-lg)] border border-dashed border-[var(--pn-paper-line)] bg-[var(--pn-paper-shade)]/35 px-6 py-12 text-center">
    <SproutMascot stage="seedling" size={84} />
    <h3 className="mt-3 font-display text-lg font-bold text-[var(--pn-ink)]">{title}</h3>
    <p className="mt-1 max-w-md text-sm text-[var(--pn-ink-soft)]">{description}</p>
    {actionLabel && onAction && (
      <div className="mt-4">
        <SketchButton variant="primary" size="sm" onClick={onAction}>
          {actionLabel}
        </SketchButton>
      </div>
    )}
  </div>
);

export const ErrorState: React.FC<{
  title?: string;
  description: string;
  onRetry?: () => void;
}> = ({ title = 'Something smudged the page', description, onRetry }) => (
  <div
    role="alert"
    className="flex flex-col items-center justify-center rounded-[var(--pn-radius-lg)] border border-[var(--pn-blossom-200)] bg-[var(--pn-blossom-50)] px-6 py-10 text-center"
  >
    <NodeyMascot mood="alert" size={84} />
    <h3 className="mt-3 font-display text-lg font-bold text-[var(--pn-ink)]">{title}</h3>
    <p className="mt-1 max-w-md text-sm text-[var(--pn-ink-soft)]">{description}</p>
    {onRetry && (
      <div className="mt-4">
        <SketchButton variant="secondary" size="sm" iconLeft={<RefreshCw size={14} />} onClick={onRetry}>
          Try again
        </SketchButton>
      </div>
    )}
  </div>
);

export const Skeleton: React.FC<{ className?: string }> = ({ className = 'h-4 w-full' }) => (
  <div
    aria-hidden="true"
    className={`animate-pulse rounded-[var(--pn-radius-sm)] bg-[var(--pn-paper-shade)] ${className}`}
  />
);
