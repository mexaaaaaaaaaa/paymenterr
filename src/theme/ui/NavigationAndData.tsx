import React from 'react';
import {
  ArrowUpDown,
  Check,
  ChevronLeft,
  ChevronRight,
  Clock,
  Mail,
  Pause,
  Search,
  Sparkles,
  X,
} from 'lucide-react';
import {
  getPaymenterStatusPresentation,
  type PaymenterEntityStatus,
  type SemanticTone,
} from '../utils/sketchGeometry';
import { EmptyState } from './FeedbackStates';

export const Breadcrumbs: React.FC<{
  items: Array<{ label: string; onClick?: () => void }>;
}> = ({ items }) => (
  <nav aria-label="Breadcrumb" className="flex flex-wrap items-center gap-1.5 text-xs font-medium text-[var(--pn-ink-soft)]">
    {items.map((item, idx) => {
      const isLast = idx === items.length - 1;
      return (
        <React.Fragment key={`${item.label}-${idx}`}>
          {idx > 0 && <ChevronRight size={13} aria-hidden="true" className="opacity-60" />}
          {item.onClick && !isLast ? (
            <button
              type="button"
              onClick={item.onClick}
              className="hover:text-[var(--pn-ink)] underline-offset-2 hover:underline cursor-pointer"
            >
              {item.label}
            </button>
          ) : (
            <span className={isLast ? 'font-semibold text-[var(--pn-ink)]' : ''}>
              {item.label}
            </span>
          )}
        </React.Fragment>
      );
    })}
  </nav>
);

export const Pagination: React.FC<{
  page: number;
  totalPages: number;
  onPageChange: (nextPage: number) => void;
}> = ({ page, totalPages, onPageChange }) => {
  if (totalPages <= 1) return null;

  return (
    <div className="flex items-center justify-between gap-3 pt-4">
      <p className="text-xs font-medium text-[var(--pn-ink-soft)] tabular-nums">
        Page {page} of {totalPages}
      </p>
      <div className="flex items-center gap-2">
        <button
          type="button"
          disabled={page <= 1}
          onClick={() => onPageChange(page - 1)}
          className="inline-flex h-10 items-center gap-1 rounded-[var(--pn-radius-sm)] border border-[var(--pn-paper-line)] bg-[#FFFDF9] px-3 text-xs font-semibold text-[var(--pn-ink)] hover:bg-[var(--pn-paper-shade)] disabled:opacity-45 cursor-pointer disabled:cursor-not-allowed"
        >
          <ChevronLeft size={14} />
          <span>Previous</span>
        </button>
        <button
          type="button"
          disabled={page >= totalPages}
          onClick={() => onPageChange(page + 1)}
          className="inline-flex h-10 items-center gap-1 rounded-[var(--pn-radius-sm)] border border-[var(--pn-paper-line)] bg-[#FFFDF9] px-3 text-xs font-semibold text-[var(--pn-ink)] hover:bg-[var(--pn-paper-shade)] disabled:opacity-45 cursor-pointer disabled:cursor-not-allowed"
        >
          <span>Next</span>
          <ChevronRight size={14} />
        </button>
      </div>
    </div>
  );
};

export const SearchInput: React.FC<{
  value: string;
  onChange: (value: string) => void;
  placeholder?: string;
  label?: string;
  visibleLabel?: boolean;
}> = ({ value, onChange, placeholder = 'Search...', label = 'Search', visibleLabel = false }) => (
  <div className="flex flex-1 flex-col gap-1.5">
    <label
      className={visibleLabel ? 'text-sm font-semibold text-[var(--pn-ink)]' : 'sr-only'}
      htmlFor="pn-search-input"
    >
      {label}
    </label>
    <div className="relative">
      <Search
        size={16}
        aria-hidden="true"
        className="pointer-events-none absolute top-1/2 left-3.5 -translate-y-1/2 text-[var(--pn-ink-soft)]"
      />
      <input
        id="pn-search-input"
        type="search"
        value={value}
        onChange={(e) => onChange(e.target.value)}
        placeholder={placeholder}
        className="h-11 w-full rounded-[var(--pn-radius-sm)] border-[1.5px] border-[var(--pn-paper-line)] bg-[#FFFDF9] pr-9 pl-10 text-sm text-[var(--pn-ink)] placeholder:text-[var(--pn-ink-soft)]/70 focus:border-[var(--pn-blossom-600)] focus:outline-none [&::-webkit-search-cancel-button]:hidden [&::-webkit-search-decoration]:hidden"
      />
      {value && (
        <button
          type="button"
          aria-label="Clear search"
          onClick={() => onChange('')}
          className="absolute top-1/2 right-2.5 inline-flex h-7 w-7 -translate-y-1/2 items-center justify-center rounded text-[var(--pn-ink-soft)] hover:bg-[var(--pn-paper-shade)] hover:text-[var(--pn-ink)] cursor-pointer"
        >
          <X size={14} />
        </button>
      )}
    </div>
  </div>
);

export const SegmentedFilter: React.FC<{
  options: Array<{ id: string; label: string; count?: number }>;
  activeId: string;
  onChange: (id: string) => void;
  ariaLabel?: string;
}> = ({ options, activeId, onChange, ariaLabel = 'Filter options' }) => (
  <div
    role="tablist"
    aria-label={ariaLabel}
    className="inline-flex flex-wrap items-center gap-1 rounded-[var(--pn-radius-md)] border border-[var(--pn-paper-line)] bg-[var(--pn-paper-shade)]/65 p-1"
  >
    {options.map((opt) => {
      const active = opt.id === activeId;
      return (
        <button
          key={opt.id}
          role="tab"
          aria-selected={active}
          type="button"
          onClick={() => onChange(opt.id)}
          className={`inline-flex min-h-[36px] items-center gap-1.5 rounded-[var(--pn-radius-sm)] px-3 py-1.5 text-xs font-semibold transition-colors whitespace-nowrap shrink-0 cursor-pointer ${
            active
              ? 'bg-[#FFFDF9] text-[var(--pn-ink)] shadow-[var(--pn-shadow-paper-sm)]'
              : 'text-[var(--pn-ink-soft)] hover:text-[var(--pn-ink)]'
          }`}
        >
          <span>{opt.label}</span>
          {typeof opt.count === 'number' && (
            <span className="tabular-nums text-[var(--pn-ink-soft)]">({opt.count})</span>
          )}
        </button>
      );
    })}
  </div>
);

const STATUS_TONE_TEXT: Record<SemanticTone, string> = {
  success: 'text-[var(--pn-moss-700)]',
  warning: 'text-[var(--pn-lantern-700)]',
  danger: 'text-[var(--pn-status-danger)]',
  info: 'text-[var(--pn-bark)]',
  neutral: 'text-[var(--pn-ink-soft)]',
};

export const StatusIndicator: React.FC<{ status: PaymenterEntityStatus }> = ({ status }) => {
  const pres = getPaymenterStatusPresentation(status);
  const Icon =
    pres.symbol === 'check'
      ? Check
      : pres.symbol === 'clock'
        ? Clock
        : pres.symbol === 'pause'
          ? Pause
          : pres.symbol === 'mail'
            ? Mail
            : pres.symbol === 'sparkles'
              ? Sparkles
              : X;

  return (
    <span className={`inline-flex items-center gap-1.5 text-xs font-bold whitespace-nowrap ${STATUS_TONE_TEXT[pres.tone]}`}>
      <Icon size={14} aria-hidden="true" />
      <span>{pres.label}</span>
    </span>
  );
};

export const InlineBadge: React.FC<{ label: string; emphasis?: 'normal' | 'accent' }> = ({
  label,
  emphasis = 'normal',
}) => (
  <span
    className={`font-mono text-xs font-semibold whitespace-nowrap ${
      emphasis === 'accent' ? 'text-[var(--pn-blossom-700)]' : 'text-[var(--pn-ink-soft)]'
    }`}
  >
    {label}
  </span>
);

export interface TableColumn<T> {
  key: string;
  header: string;
  align?: 'left' | 'right';
  sortable?: boolean;
  render: (row: T) => React.ReactNode;
}

export function SketchTable<T extends { id: string }>({
  columns,
  rows,
  sortKey,
  sortDir,
  onSort,
  emptyTitle = 'No entries yet',
  emptyDescription = 'Items matching your filters will appear here.',
}: {
  columns: TableColumn<T>[];
  rows: T[];
  sortKey?: string;
  sortDir?: 'asc' | 'desc';
  onSort?: (key: string) => void;
  emptyTitle?: string;
  emptyDescription?: string;
}) {
  if (rows.length === 0) {
    return <EmptyState title={emptyTitle} description={emptyDescription} />;
  }

  return (
    <div className="overflow-x-auto rounded-[var(--pn-radius-lg)] border border-[var(--pn-paper-line)] bg-[#FFFDF9] shadow-[var(--pn-shadow-paper-sm)]">
      <table className="w-full border-collapse text-left text-sm">
        <thead>
          <tr className="border-b border-[var(--pn-paper-line)] bg-[var(--pn-paper-shade)]/55">
            {columns.map((col) => (
              <th
                key={col.key}
                className={`px-4 py-3 font-display text-xs font-bold tracking-wide text-[var(--pn-ink-soft)] uppercase ${
                  col.align === 'right' ? 'text-right' : 'text-left'
                }`}
              >
                {col.sortable && onSort ? (
                  <button
                    type="button"
                    onClick={() => onSort(col.key)}
                    className="inline-flex items-center gap-1 hover:text-[var(--pn-ink)] cursor-pointer"
                  >
                    <span>{col.header}</span>
                    <ArrowUpDown
                      size={12}
                      className={sortKey === col.key ? 'text-[var(--pn-blossom-700)]' : 'opacity-50'}
                    />
                    {sortKey === col.key && (
                      <span className="sr-only">({sortDir})</span>
                    )}
                  </button>
                ) : (
                  col.header
                )}
              </th>
            ))}
          </tr>
        </thead>
        <tbody className="divide-y divide-[var(--pn-paper-line)]/70">
          {rows.map((row) => (
            <tr key={row.id} className="transition-colors hover:bg-[var(--pn-paper)]/75">
              {columns.map((col) => (
                <td
                  key={col.key}
                  className={`px-4 py-3 align-middle ${
                    col.align === 'right' ? 'text-right tabular-nums' : 'text-left'
                  }`}
                >
                  {col.render(row)}
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
