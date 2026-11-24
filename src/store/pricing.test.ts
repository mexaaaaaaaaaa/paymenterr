import test from 'node:test';
import assert from 'node:assert/strict';
import { TEST_PAYMENTER_RUNTIME } from '../test-fixtures/paymenterTestFixtures';
import {
  getVisibleSortedCategories,
  getVisibleSortedProducts,
  UNCONNECTED_PAYMENTER_ACTIONS,
} from '../theme/paymenter/adapters';
import { EMPTY_PAYMENTER_RUNTIME } from '../theme/paymenter/contracts';
import { createDefaultProductSelection, validateProductSelection } from './pricing';

test('EMPTY_PAYMENTER_RUNTIME exposes zero fake products, categories, coupons, or gateways', () => {
  assert.equal(EMPTY_PAYMENTER_RUNTIME.backendConnected, false);
  assert.equal(EMPTY_PAYMENTER_RUNTIME.products.length, 0);
  assert.equal(EMPTY_PAYMENTER_RUNTIME.categories.length, 0);
  assert.equal(EMPTY_PAYMENTER_RUNTIME.gateways.length, 0);
  assert.equal(EMPTY_PAYMENTER_RUNTIME.cart.items.length, 0);
  assert.equal(EMPTY_PAYMENTER_RUNTIME.cart.summary, null);
  assert.equal(EMPTY_PAYMENTER_RUNTIME.customer, null);

  const couponRes = UNCONNECTED_PAYMENTER_ACTIONS.applyCoupon('ANY');
  assert.deepEqual(couponRes, {
    ok: false,
    reason: 'Paymenter backend repository is not present in this workspace. This action must be executed by the real Paymenter installation.',
  });
});

test('getVisibleSortedProducts & getVisibleSortedCategories strictly respect Paymenter visibility and sortOrder', () => {
  const visibleProducts = getVisibleSortedProducts(TEST_PAYMENTER_RUNTIME);
  assert.equal(visibleProducts.length, 1);
  assert.equal(visibleProducts[0].id, 'test-prod-1');

  const visibleCats = getVisibleSortedCategories(TEST_PAYMENTER_RUNTIME);
  assert.equal(visibleCats.length, 1);
  assert.equal(visibleCats[0].slug, 'game-hosting');
});

test('validateProductSelection validates Paymenter product options and rejects unavailable choices', () => {
  const product = TEST_PAYMENTER_RUNTIME.products[0];
  const defaultSel = createDefaultProductSelection(product);
  assert.equal(defaultSel.planId, 'plan-monthly');
  assert.equal(defaultSel.selectedChoices['opt-region'], 'loc-1');

  const ok = validateProductSelection(product, defaultSel);
  assert.equal(ok.valid, true);

  const bad = validateProductSelection(product, {
    ...defaultSel,
    selectedChoices: { 'opt-region': 'loc-2' },
  });
  assert.equal(bad.valid, false);
  assert.ok(bad.errors.length >= 1);
});
