/**
 * ISOLATED UNIT-TEST-ONLY FIXTURES.
 * NEVER import this file from src/App.tsx, src/components/, or src/theme/.
 * Used strictly to test UI presentation formatting, sorting/visibility guards,
 * and empty-safe reducers in `node:test` suites.
 */

import type { PaymenterThemeRuntime } from '../theme/paymenter/contracts';

export const TEST_PAYMENTER_RUNTIME: PaymenterThemeRuntime = {
  backendConnected: true,
  missingBackendMessage: '',
  categories: [
    {
      id: 'cat-1',
      slug: 'game-hosting',
      name: 'Game Hosting',
      description: 'Test category from fixture',
      visible: true,
      sortOrder: 1,
    },
    {
      id: 'cat-hidden',
      slug: 'hidden-cat',
      name: 'Hidden Category',
      description: 'Should remain hidden when visible=false',
      visible: false,
      sortOrder: 2,
    },
  ],
  products: [
    {
      id: 'test-prod-1',
      slug: 'test-plan-a',
      name: 'Fixture Plan A',
      categorySlug: 'game-hosting',
      description: 'Test fixture product verifying visibility and sorting.',
      visible: true,
      inStock: true,
      sortOrder: 2,
      featured: true,
      badgeText: 'Featured',
      allowQuantity: true,
      specs: [
        { label: 'Memory', value: '8 GB' },
        { label: 'Storage', value: '80 GB' },
      ],
      plans: [
        {
          id: 'plan-monthly',
          label: 'Monthly',
          billingCycleLabel: '1 Month',
          formattedPrice: '$10.00',
          numericPrice: 10,
          available: true,
        },
        {
          id: 'plan-annual',
          label: 'Annual',
          billingCycleLabel: '12 Months',
          formattedPrice: '$100.00',
          numericPrice: 100,
          available: true,
        },
      ],
      configOptions: [
        {
          id: 'opt-region',
          name: 'Region',
          type: 'radio',
          required: true,
          choices: [
            { id: 'loc-1', label: 'Primary Region', available: true },
            { id: 'loc-2', label: 'Unavailable Region', available: false },
          ],
        },
      ],
      faqs: [{ question: 'Test question?', answer: 'Test answer.' }],
    },
    {
      id: 'test-prod-hidden',
      slug: 'hidden-plan',
      name: 'Hidden Fixture Plan',
      categorySlug: 'game-hosting',
      description: 'Hidden product should not appear in visible catalog list.',
      visible: false,
      inStock: true,
      sortOrder: 1,
      featured: false,
      allowQuantity: false,
      specs: [],
      plans: [],
      configOptions: [],
      faqs: [],
    },
  ],
  cart: {
    items: [
      {
        id: 'line-1',
        productId: 'test-prod-1',
        productSlug: 'test-plan-a',
        productName: 'Fixture Plan A',
        planId: 'plan-monthly',
        planLabel: 'Monthly',
        quantity: 2,
        allowQuantity: true,
        selectedOptionsSummary: [{ name: 'Region', value: 'Primary Region' }],
        formattedUnitPrice: '$10.00',
        formattedLineTotal: '$20.00',
      },
    ],
    summary: {
      formattedSubtotal: '$20.00',
      formattedDiscount: '$0.00',
      formattedTax: '$0.00',
      formattedTotal: '$20.00',
      itemCount: 2,
    },
  },
  gateways: [
    { id: 'gw-test', name: 'Test Gateway', description: 'Fixture gateway', available: true },
  ],
  customer: {
    authenticated: true,
    name: 'Test Customer',
    email: 'test@example.com',
  },
  announcements: [],
};
