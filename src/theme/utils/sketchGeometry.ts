/**
 * Pure geometry, deterministic seed, formatting, and semantic status helpers.
 * Decoupled from DOM/React for fast unit testing via node:test.
 */

export type SemanticTone = 'success' | 'warning' | 'danger' | 'info' | 'neutral';

export type PaymenterEntityStatus =
  | 'active'
  | 'pending'
  | 'suspended'
  | 'cancelled'
  | 'paid'
  | 'unpaid'
  | 'overdue'
  | 'open'
  | 'answered'
  | 'closed';

export interface StatusPresentation {
  label: string;
  tone: SemanticTone;
  symbol: 'check' | 'clock' | 'pause' | 'x' | 'sparkles' | 'mail';
}

const STATUS_MAP: Record<PaymenterEntityStatus, StatusPresentation> = {
  active: { label: 'Active', tone: 'success', symbol: 'check' },
  paid: { label: 'Paid', tone: 'success', symbol: 'check' },
  answered: { label: 'Answered', tone: 'success', symbol: 'mail' },
  pending: { label: 'Pending', tone: 'warning', symbol: 'clock' },
  unpaid: { label: 'Unpaid', tone: 'warning', symbol: 'clock' },
  open: { label: 'Open', tone: 'info', symbol: 'sparkles' },
  suspended: { label: 'Suspended', tone: 'danger', symbol: 'pause' },
  overdue: { label: 'Overdue', tone: 'danger', symbol: 'pause' },
  cancelled: { label: 'Cancelled', tone: 'neutral', symbol: 'x' },
  closed: { label: 'Closed', tone: 'neutral', symbol: 'x' },
};

export function getPaymenterStatusPresentation(status: PaymenterEntityStatus): StatusPresentation {
  return STATUS_MAP[status];
}

/**
 * Deterministic 32-bit FNV-1a hash so Rough.js strokes remain stable across renders
 * without flickering or random jitter on layout recalculation.
 */
export function hashSeed(input: string | number): number {
  const str = String(input);
  let hash = 2166136261;
  for (let i = 0; i < str.length; i += 1) {
    hash ^= str.charCodeAt(i);
    hash = Math.imul(hash, 16777619);
  }
  return (hash >>> 0) % 2147483647 || 1337;
}

/**
 * Clamp and normalize a bounding box for Rough.js SVG rendering.
 */
export function normalizeSketchBounds(width: number, height: number, padding = 2): {
  x: number;
  y: number;
  w: number;
  h: number;
} {
  const safeW = Math.max(24, Math.round(width));
  const safeH = Math.max(24, Math.round(height));
  const p = Math.max(1, Math.min(padding, Math.floor(Math.min(safeW, safeH) / 4)));
  return {
    x: p,
    y: p,
    w: Math.max(4, safeW - p * 2),
    h: Math.max(4, safeH - p * 2),
  };
}

/**
 * Format currency amounts with consistent precision for Paymenter pricing & invoices.
 */
export function formatCurrencyAmount(amount: number, currency = 'USD', locale = 'en-US'): string {
  const safe = Number.isFinite(amount) ? amount : 0;
  return new Intl.NumberFormat(locale, {
    style: 'currency',
    currency,
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(safe);
}

/**
 * Calculate relative luminance contrast ratio (WCAG 2.1) for hex pairs.
 */
export function getContrastRatio(hexA: string, hexB: string): number {
  const lum = (hex: string): number => {
    const clean = hex.replace('#', '');
    const full = clean.length === 3
      ? clean.split('').map((c) => c + c).join('')
      : clean;
    const num = parseInt(full, 16);
    const channels = [(num >> 16) & 255, (num >> 8) & 255, num & 255].map((v) => {
      const s = v / 255;
      return s <= 0.03928 ? s / 12.92 : Math.pow((s + 0.055) / 1.055, 2.4);
    });
    return 0.2126 * channels[0] + 0.7152 * channels[1] + 0.0722 * channels[2];
  };

  const l1 = lum(hexA);
  const l2 = lum(hexB);
  const lighter = Math.max(l1, l2);
  const darker = Math.min(l1, l2);
  return Number(((lighter + 0.05) / (darker + 0.05)).toFixed(2));
}
