import React, { useEffect, useId, useRef, useState } from 'react';
import { ChevronDown, X } from 'lucide-react';

export interface SketchModalProps {
  open: boolean;
  onClose: () => void;
  title: string;
  description?: string;
  children: React.ReactNode;
  footer?: React.ReactNode;
  maxWidthClass?: string;
}

export const SketchModal: React.FC<SketchModalProps> = ({
  open,
  onClose,
  title,
  description,
  children,
  footer,
  maxWidthClass = 'max-w-lg',
}) => {
  const titleId = useId();

  useEffect(() => {
    if (!open) return;
    const onKey = (e: KeyboardEvent) => {
      if (e.key === 'Escape') onClose();
    };
    window.addEventListener('keydown', onKey);
    return () => window.removeEventListener('keydown', onKey);
  }, [open, onClose]);

  if (!open) return null;

  return (
    <div
      role="dialog"
      aria-modal="true"
      aria-labelledby={titleId}
      className="fixed inset-0 z-[var(--pn-z-modal)] flex items-center justify-center p-4"
    >
      <div
        aria-hidden="true"
        onClick={onClose}
        className="fixed inset-0 bg-[var(--pn-ink)]/45 backdrop-blur-[2px]"
      />
      <div
        className={`relative z-10 w-full ${maxWidthClass} rounded-[var(--pn-radius-lg)] border-[1.5px] border-[var(--pn-ink)] bg-[#FFFDF9] p-6 shadow-[var(--pn-shadow-paper-lg)]`}
      >
        <div className="flex items-start justify-between gap-4">
          <div>
            <h3 id={titleId} className="font-display text-xl font-bold text-[var(--pn-ink)]">
              {title}
            </h3>
            {description && (
              <p className="mt-1 text-sm text-[var(--pn-ink-soft)]">{description}</p>
            )}
          </div>
          <button
            type="button"
            aria-label="Close dialog"
            onClick={onClose}
            className="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-[var(--pn-radius-sm)] border border-[var(--pn-paper-line)] text-[var(--pn-ink-soft)] hover:bg-[var(--pn-paper-shade)] hover:text-[var(--pn-ink)] cursor-pointer"
          >
            <X size={18} />
          </button>
        </div>

        <div className="mt-5">{children}</div>

        {footer && (
          <div className="mt-6 flex flex-wrap items-center justify-end gap-3 border-t border-[var(--pn-paper-line)] pt-4">
            {footer}
          </div>
        )}
      </div>
    </div>
  );
};

export interface SketchDrawerProps {
  open: boolean;
  onClose: () => void;
  title: string;
  side?: 'right' | 'left';
  children: React.ReactNode;
  footer?: React.ReactNode;
}

export const SketchDrawer: React.FC<SketchDrawerProps> = ({
  open,
  onClose,
  title,
  side = 'right',
  children,
  footer,
}) => {
  const titleId = useId();

  useEffect(() => {
    if (!open) return;
    const onKey = (e: KeyboardEvent) => {
      if (e.key === 'Escape') onClose();
    };
    window.addEventListener('keydown', onKey);
    return () => window.removeEventListener('keydown', onKey);
  }, [open, onClose]);

  if (!open) return null;

  return (
    <div
      role="dialog"
      aria-modal="true"
      aria-labelledby={titleId}
      className="fixed inset-0 z-[var(--pn-z-drawer)] flex"
    >
      <div
        aria-hidden="true"
        onClick={onClose}
        className="fixed inset-0 bg-[var(--pn-ink)]/40 backdrop-blur-[2px]"
      />
      <aside
        className={`relative z-10 flex h-full w-full max-w-md flex-col border-[var(--pn-paper-line)] bg-[#FFFDF9] shadow-[var(--pn-shadow-paper-lg)] ${
          side === 'right' ? 'ml-auto border-l-[1.5px] pn-drawer-in' : 'mr-auto border-r-[1.5px] pn-drawer-in-left'
        }`}
      >
        <div className="flex items-center justify-between border-b border-[var(--pn-paper-line)] px-6 py-4">
          <h3 id={titleId} className="font-display text-lg font-bold text-[var(--pn-ink)]">
            {title}
          </h3>
          <button
            type="button"
            aria-label="Close drawer"
            onClick={onClose}
            className="inline-flex h-10 w-10 items-center justify-center rounded-[var(--pn-radius-sm)] border border-[var(--pn-paper-line)] text-[var(--pn-ink-soft)] hover:bg-[var(--pn-paper-shade)] hover:text-[var(--pn-ink)] cursor-pointer"
          >
            <X size={18} />
          </button>
        </div>
        <div className="flex-1 overflow-y-auto p-6">{children}</div>
        {footer && (
          <div className="border-t border-[var(--pn-paper-line)] bg-[var(--pn-paper-shade)]/45 px-6 py-4">
            {footer}
          </div>
        )}
      </aside>
    </div>
  );
};

export interface DropdownOption {
  id: string;
  label: string;
  onSelect: () => void;
}

export const SketchDropdown: React.FC<{
  label: string;
  items: DropdownOption[];
}> = ({ label, items }) => {
  const [open, setOpen] = useState(false);
  const rootRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (!open) return;
    const onDocClick = (e: MouseEvent) => {
      if (rootRef.current && !rootRef.current.contains(e.target as Node)) {
        setOpen(false);
      }
    };
    const onKey = (e: KeyboardEvent) => {
      if (e.key === 'Escape') setOpen(false);
    };
    document.addEventListener('mousedown', onDocClick);
    window.addEventListener('keydown', onKey);
    return () => {
      document.removeEventListener('mousedown', onDocClick);
      window.removeEventListener('keydown', onKey);
    };
  }, [open]);

  return (
    <div ref={rootRef} className="relative inline-block">
      <button
        type="button"
        aria-expanded={open}
        aria-haspopup="menu"
        onClick={() => setOpen((v) => !v)}
        className="inline-flex min-h-[40px] items-center gap-2 rounded-[var(--pn-radius-md)] border-[1.5px] border-[var(--pn-ink)] bg-[#FFFDF9] px-4 py-2 text-sm font-semibold text-[var(--pn-ink)] shadow-[var(--pn-shadow-paper-sm)] hover:bg-[var(--pn-paper-shade)] cursor-pointer whitespace-nowrap"
      >
        <span>{label}</span>
        <ChevronDown size={15} className={`transition-transform ${open ? 'rotate-180' : ''}`} />
      </button>
      {open && (
        <div
          role="menu"
          className="absolute right-0 z-[var(--pn-z-dropdown)] mt-2 min-w-[200px] rounded-[var(--pn-radius-md)] border-[1.5px] border-[var(--pn-ink)] bg-[#FFFDF9] p-1.5 shadow-[var(--pn-shadow-paper-md)]"
        >
          {items.map((item) => (
            <button
              key={item.id}
              role="menuitem"
              type="button"
              onClick={() => {
                item.onSelect();
                setOpen(false);
              }}
              className="flex w-full items-center rounded-[var(--pn-radius-sm)] px-3 py-2 text-left text-sm font-medium text-[var(--pn-ink)] hover:bg-[var(--pn-blossom-50)] hover:text-[var(--pn-blossom-700)] cursor-pointer"
            >
              {item.label}
            </button>
          ))}
        </div>
      )}
    </div>
  );
};

export const SketchTooltip: React.FC<{
  content: string;
  children: React.ReactNode;
}> = ({ content, children }) => {
  const [visible, setVisible] = useState(false);
  const tipId = useId();

  return (
    <span
      className="relative inline-flex"
      onMouseEnter={() => setVisible(true)}
      onMouseLeave={() => setVisible(false)}
      onFocus={() => setVisible(true)}
      onBlur={() => setVisible(false)}
    >
      <span aria-describedby={visible ? tipId : undefined}>{children}</span>
      {visible && (
        <span
          id={tipId}
          role="tooltip"
          className="pointer-events-none absolute bottom-full left-1/2 z-[var(--pn-z-tooltip)] mb-2 -translate-x-1/2 whitespace-nowrap rounded-[var(--pn-radius-sm)] border border-[var(--pn-ink)] bg-[var(--pn-ink)] px-2.5 py-1 text-xs font-medium text-[var(--pn-paper)] shadow-[var(--pn-shadow-paper-sm)]"
        >
          {content}
        </span>
      )}
    </span>
  );
};
