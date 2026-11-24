/**
 * Presentation-layer TypeScript interfaces for Paymenter theme integration.
 * Defines the UI data shape without hardcoding any product catalog, prices,
 * coupons, tax percentages, gateways, or unverified Laravel/Livewire internals.
 */

export interface PaymenterCategory {
  id: string;
  slug: string;
  name: string;
  description: string;
  visible: boolean;
  sortOrder: number;
}

export interface PaymenterBillingPlan {
  id: string;
  label: string;
  billingCycleLabel: string;
  /** Backend-computed formatted recurring price string (e.g. "$14.00") */
  formattedPrice: string;
  /** Numeric price strictly for sorting/display alignment; authoritative totals come from backend */
  numericPrice: number;
  /** Optional backend-computed formatted setup fee */
  formattedSetupFee?: string;
  available: boolean;
}

export interface PaymenterConfigChoice {
  id: string;
  label: string;
  description?: string;
  /** Backend-computed formatted delta string if applicable */
  formattedPriceDelta?: string;
  available: boolean;
}

export interface PaymenterConfigOption {
  id: string;
  name: string;
  type: 'select' | 'radio' | 'checkbox' | 'text';
  required: boolean;
  choices: PaymenterConfigChoice[];
}

export interface PaymenterSpecRow {
  label: string;
  value: string;
}

export interface PaymenterProduct {
  id: string;
  slug: string;
  name: string;
  categorySlug: string;
  description: string;
  visible: boolean;
  inStock: boolean;
  sortOrder: number;
  featured: boolean;
  badgeText?: string;
  allowQuantity: boolean;
  imageUrl?: string;
  specs: PaymenterSpecRow[];
  plans: PaymenterBillingPlan[];
  configOptions: PaymenterConfigOption[];
  faqs: Array<{ question: string; answer: string }>;
}

export interface PaymenterCartItem {
  id: string;
  productId: string;
  productSlug: string;
  productName: string;
  planId: string;
  planLabel: string;
  quantity: number;
  allowQuantity: boolean;
  selectedOptionsSummary: Array<{ name: string; value: string }>;
  formattedUnitPrice: string;
  formattedLineTotal: string;
}

export interface PaymenterCartSummary {
  formattedSubtotal: string;
  formattedDiscount?: string;
  appliedCouponCode?: string;
  formattedTax?: string;
  formattedTotal: string;
  itemCount: number;
}

export interface PaymenterGateway {
  id: string;
  name: string;
  description?: string;
  available: boolean;
}

export interface PaymenterCustomerSummary {
  authenticated: boolean;
  name?: string;
  email?: string;
}

export interface PaymenterAnnouncement {
  id: string;
  title: string;
  excerpt: string;
  publishedAt: string;
}

export interface PaymenterThemeRuntime {
  /** True only when a real Paymenter backend adapter injects live store data */
  backendConnected: boolean;
  /** Explains clearly why live commerce/customer data is unavailable in standalone theme mode */
  missingBackendMessage: string;
  categories: PaymenterCategory[];
  products: PaymenterProduct[];
  cart: {
    items: PaymenterCartItem[];
    summary: PaymenterCartSummary | null;
  };
  gateways: PaymenterGateway[];
  customer: PaymenterCustomerSummary | null;
  announcements: PaymenterAnnouncement[];
}

export const EMPTY_PAYMENTER_RUNTIME: PaymenterThemeRuntime = {
  backendConnected: false,
  missingBackendMessage:
    'No Paymenter backend repository is mounted in this workspace. Products, categories, pricing, coupons, cart calculations, customer sessions, and payment gateways require live Paymenter integration.',
  categories: [],
  products: [],
  cart: {
    items: [],
    summary: null,
  },
  gateways: [],
  customer: null,
  announcements: [],
};
