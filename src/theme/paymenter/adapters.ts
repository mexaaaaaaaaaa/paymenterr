/**
 * Paymenter Integration Adapters (Presentation Boundary)
 *
 * Strictly delegates catalog, pricing, coupon, cart, and checkout actions to
 * an external Paymenter backend bridge. Contains ZERO hardcoded products,
 * prices, coupons, tax rates, gateways, or invented Laravel endpoints.
 */

import {
  EMPTY_PAYMENTER_RUNTIME,
  type PaymenterProduct,
  type PaymenterThemeRuntime,
} from './contracts';

export interface AdapterActionResult {
  ok: boolean;
  reason?: string;
}

export interface PaymenterThemeActions {
  addToCart: (input: {
    productId: string;
    planId: string;
    quantity: number;
    selectedOptions: Record<string, string | string[]>;
  }) => Promise<AdapterActionResult> | AdapterActionResult;
  updateCartQuantity: (itemId: string, quantity: number) => Promise<AdapterActionResult> | AdapterActionResult;
  removeCartItem: (itemId: string) => Promise<AdapterActionResult> | AdapterActionResult;
  applyCoupon: (code: string) => Promise<AdapterActionResult> | AdapterActionResult;
  removeCoupon: () => Promise<AdapterActionResult> | AdapterActionResult;
  submitCheckout: (input: {
    gatewayId: string;
    acceptedTerms: boolean;
  }) => Promise<AdapterActionResult> | AdapterActionResult;
}

export const MISSING_BACKEND_REASON =
  'Paymenter backend repository is not present in this workspace. This action must be executed by the real Paymenter installation.';

export const UNCONNECTED_PAYMENTER_ACTIONS: PaymenterThemeActions = {
  addToCart: () => ({ ok: false, reason: MISSING_BACKEND_REASON }),
  updateCartQuantity: () => ({ ok: false, reason: MISSING_BACKEND_REASON }),
  removeCartItem: () => ({ ok: false, reason: MISSING_BACKEND_REASON }),
  applyCoupon: () => ({ ok: false, reason: MISSING_BACKEND_REASON }),
  removeCoupon: () => ({ ok: false, reason: MISSING_BACKEND_REASON }),
  submitCheckout: () => ({ ok: false, reason: MISSING_BACKEND_REASON }),
};

/**
 * Filters and sorts Paymenter products strictly respecting backend visibility
 * and ordering rules without inventing availability.
 */
export function getVisibleSortedProducts(
  runtime: PaymenterThemeRuntime
): PaymenterProduct[] {
  return [...runtime.products]
    .filter((p) => p.visible)
    .sort((a, b) => a.sortOrder - b.sortOrder);
}

/**
 * Filters and sorts Paymenter categories strictly respecting backend visibility
 * and ordering rules.
 */
export function getVisibleSortedCategories(runtime: PaymenterThemeRuntime) {
  return [...runtime.categories]
    .filter((c) => c.visible)
    .sort((a, b) => a.sortOrder - b.sortOrder);
}

export function createInitialRuntime(
  injected?: Partial<PaymenterThemeRuntime>
): PaymenterThemeRuntime {
  if (!injected) return EMPTY_PAYMENTER_RUNTIME;
  return {
    ...EMPTY_PAYMENTER_RUNTIME,
    ...injected,
    cart: injected.cart ?? EMPTY_PAYMENTER_RUNTIME.cart,
  };
}
