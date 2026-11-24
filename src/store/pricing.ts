/**
 * Presentation-only configuration validation & display helpers.
 *
 * IMPORTANT: Never hardcodes product prices, taxes, coupon codes, or discount
 * percentages. All authoritative pricing, tax, coupon, and renewal totals MUST
 * be supplied by Paymenter's backend.
 */

import type { PaymenterProduct } from '../theme/paymenter/contracts';

export interface ProductOptionSelection {
  planId: string;
  selectedChoices: Record<string, string | string[]>;
}

export function createDefaultProductSelection(
  product: PaymenterProduct
): ProductOptionSelection {
  const defaultPlan = product.plans.find((p) => p.available) ?? product.plans[0];
  const selectedChoices: Record<string, string | string[]> = {};

  for (const option of product.configOptions) {
    if (option.type === 'checkbox') {
      selectedChoices[option.id] = [];
    } else if (option.type === 'text') {
      selectedChoices[option.id] = '';
    } else {
      const firstAvailable = option.choices.find((c) => c.available) ?? option.choices[0];
      selectedChoices[option.id] = firstAvailable?.id ?? '';
    }
  }

  return {
    planId: defaultPlan?.id ?? '',
    selectedChoices,
  };
}

export function validateProductSelection(
  product: PaymenterProduct,
  selection: ProductOptionSelection
): { valid: boolean; errors: string[] } {
  const errors: string[] = [];

  if (!product.visible) {
    errors.push('This product is not currently visible.');
  }
  if (!product.inStock) {
    errors.push(`${product.name} is currently unavailable.`);
  }

  const plan = product.plans.find((p) => p.id === selection.planId);
  if (!plan || !plan.available) {
    errors.push('Selected billing plan is unavailable.');
  }

  for (const option of product.configOptions) {
    const val = selection.selectedChoices[option.id];
    if (option.type === 'checkbox') {
      const chosenIds = Array.isArray(val) ? val : [];
      if (option.required && chosenIds.length === 0) {
        errors.push(`${option.name} requires at least one selection.`);
      }
      for (const id of chosenIds) {
        const choice = option.choices.find((c) => c.id === id);
        if (!choice || !choice.available) {
          errors.push(`Selected choice for ${option.name} is unavailable.`);
        }
      }
    } else if (option.type === 'text') {
      const textVal = typeof val === 'string' ? val.trim() : '';
      if (option.required && !textVal) {
        errors.push(`${option.name} is required.`);
      }
    } else {
      const chosenId = typeof val === 'string' ? val : '';
      const choice = option.choices.find((c) => c.id === chosenId);
      if (option.required && (!choice || !choice.available)) {
        errors.push(`Selected option for ${option.name} is unavailable.`);
      } else if (chosenId && (!choice || !choice.available)) {
        errors.push(`Selected option for ${option.name} is unavailable.`);
      }
    }
  }

  return { valid: errors.length === 0, errors };
}
