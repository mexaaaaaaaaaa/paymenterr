/**
 * Paymenter Theme Compatibility Specification & Missing Repository Boundaries
 *
 * IMPORTANT: The actual Paymenter Laravel repository is NOT present in this
 * React/Vite workspace. Therefore, this file intentionally does NOT invent
 * unverified API endpoints, Blade directives, Livewire component class names,
 * or Livewire event names. Exact backend hooks must be verified directly from
 * the Paymenter source repository once provided.
 */

export interface PaymenterThemeManifest {
  name: string;
  slug: string;
  version: string;
  targetPlatform: string;
  repositoryStatus: 'awaiting-paymenter-source';
}

export const PETALNODE_THEME_MANIFEST: PaymenterThemeManifest = {
  name: 'Petalnode Sketchbook',
  slug: 'petalnode',
  version: '0.2.0-theme-foundation',
  targetPlatform: 'Paymenter (External Repository Required)',
  repositoryStatus: 'awaiting-paymenter-source',
};

export interface MissingIntegrationDependency {
  area: string;
  status: 'Paymenter integration pending';
  reason: string;
}

export const MISSING_PAYMENTER_DEPENDENCIES: MissingIntegrationDependency[] = [
  {
    area: 'Product Catalog & Categories',
    status: 'Paymenter integration pending',
    reason: 'Requires live Paymenter product, category, visibility, stock, and ordering source data.',
  },
  {
    area: 'Pricing, Billing Cycles, Tax & Coupons',
    status: 'Paymenter integration pending',
    reason: 'Must use Paymenter server-authoritative pricing, currency formatting, discount, and tax engines.',
  },
  {
    area: 'Cart & Checkout Execution',
    status: 'Paymenter integration pending',
    reason: 'Requires Paymenter session cart, CSRF/auth verification, order creation, and configured gateway extensions.',
  },
  {
    area: 'Customer Portal, Services, Invoices & Support',
    status: 'Paymenter integration pending',
    reason: 'Requires authenticated Paymenter customer records, invoices, transactions, services, and ticket threads.',
  },
  {
    area: 'Template & Asset Mounting Contract',
    status: 'Paymenter integration pending',
    reason: 'Requires inspecting the real Paymenter theme directory, layouts, and server-side view hooks before binding.',
  },
];

export const COMPATIBILITY_INVARIANTS = [
  'Theme changes presentation only; never replace or duplicate Paymenter business or billing logic.',
  'Never display fake products, fake prices, fake coupons, or fake customer/invoice records.',
  'Never invent unverified API endpoints, Blade directives, or Livewire event names without inspecting Paymenter source.',
  'Never simulate payment success or bypass backend authentication, CSRF, and webhook verification.',
  'Ensure zero-JS fallback legibility: paper surfaces, typography, and controls remain clear even before animations run.',
] as const;
