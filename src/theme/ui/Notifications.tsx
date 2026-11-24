import React, { createContext, useCallback, useContext, useState } from 'react';
import { AlertCircle, CheckCircle2, Info, AlertTriangle, X } from 'lucide-react';
import type { SemanticTone } from '../utils/sketchGeometry';

export interface ToastItem {
  id: string;
  title: string;
  message?: string;
  tone: SemanticTone;
}

interface ToastContextValue {
  notify: (toast: Omit<ToastItem, 'id'>) => void;
  dismiss: (id: string) => void;
}

const ToastContext = createContext<ToastContextValue | null>(null);

const TONE_STYLE: Record<SemanticTone, string> = {
  success: 'bg-[var(--pn-moss-100)] border-[var(--pn-moss-700)] text-[var(--pn-ink)]',
  warning: 'bg-[var(--pn-lantern-100)] border-[var(--pn-lantern-700)] text-[var(--pn-ink)]',
  danger: 'bg-[var(--pn-blossom-50)] border-[var(--pn-status-danger)] text-[var(--pn-ink)]',
  info: 'bg-[var(--pn-bark-100)] border-[var(--pn-bark)] text-[var(--pn-ink)]',
  neutral: 'bg-[#FFFDF9] border-[var(--pn-paper-line)] text-[var(--pn-ink)]',
};

const TONE_ICON: Record<SemanticTone, React.ReactNode> = {
  success: <CheckCircle2 size={18} className="text-[var(--pn-moss-700)] shrink-0" aria-hidden="true" />,
  warning: <AlertTriangle size={18} className="text-[var(--pn-lantern-700)] shrink-0" aria-hidden="true" />,
  danger: <AlertCircle size={18} className="text-[var(--pn-status-danger)] shrink-0" aria-hidden="true" />,
  info: <Info size={18} className="text-[var(--pn-bark)] shrink-0" aria-hidden="true" />,
  neutral: <Info size={18} className="text-[var(--pn-ink-soft)] shrink-0" aria-hidden="true" />,
};

export const ToastProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [toasts, setToasts] = useState<ToastItem[]>([]);

  const dismiss = useCallback((id: string) => {
    setToasts((prev) => prev.filter((item) => item.id !== id));
  }, []);

  const notify = useCallback((toast: Omit<ToastItem, 'id'>) => {
    const id = `pn-toast-${Date.now()}-${Math.random().toString(36).slice(2, 6)}`;
    setToasts((prev) => [...prev.slice(-3), { ...toast, id }]);
    window.setTimeout(() => {
      setToasts((prev) => prev.filter((item) => item.id !== id));
    }, 4500);
  }, []);

  return (
    <ToastContext.Provider value={{ notify, dismiss }}>
      {children}
      <div
        aria-live="polite"
        aria-label="Notifications"
        className="fixed right-4 bottom-4 z-[var(--pn-z-toast)] flex w-[calc(100vw-2rem)] max-w-sm flex-col gap-2.5"
      >
        {toasts.map((toast) => (
          <div
            key={toast.id}
            role="status"
            className={`flex items-start gap-3 rounded-[var(--pn-radius-md)] border-[1.5px] p-4 shadow-[var(--pn-shadow-paper-md)] ${TONE_STYLE[toast.tone]}`}
          >
            {TONE_ICON[toast.tone]}
            <div className="min-w-0 flex-1">
              <p className="font-display text-sm font-bold text-[var(--pn-ink)]">{toast.title}</p>
              {toast.message && (
                <p className="mt-0.5 text-xs text-[var(--pn-ink-soft)]">{toast.message}</p>
              )}
            </div>
            <button
              type="button"
              aria-label={`Dismiss ${toast.title}`}
              onClick={() => dismiss(toast.id)}
              className="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-[var(--pn-radius-xs)] text-[var(--pn-ink-soft)] hover:bg-black/5 hover:text-[var(--pn-ink)] cursor-pointer"
            >
              <X size={14} />
            </button>
          </div>
        ))}
      </div>
    </ToastContext.Provider>
  );
};

export function useToast(): ToastContextValue {
  const ctx = useContext(ToastContext);
  if (!ctx) {
    throw new Error('useToast must be used within ToastProvider');
  }
  return ctx;
}

export const SketchAlert: React.FC<{
  tone?: SemanticTone;
  title: string;
  description?: string;
  className?: string;
}> = ({ tone = 'info', title, description, className = '' }) => (
  <div
    role="note"
    className={`flex items-start gap-3 rounded-[var(--pn-radius-md)] border p-4 ${TONE_STYLE[tone]} ${className}`}
  >
    {TONE_ICON[tone]}
    <div className="min-w-0 flex-1">
      <p className="font-display text-sm font-bold text-[var(--pn-ink)]">{title}</p>
      {description && <p className="mt-0.5 text-sm text-[var(--pn-ink-soft)]">{description}</p>}
    </div>
  </div>
);
