import test from 'node:test';
import assert from 'node:assert/strict';
import {
  INITIAL_CHECKOUT_DRAFT,
  validateCheckoutDraftStep,
} from './cartReducer';

test('validateCheckoutDraftStep blocks checkout when Paymenter backend is not connected', () => {
  const noBackend = validateCheckoutDraftStep(1, INITIAL_CHECKOUT_DRAFT, 0, false);
  assert.ok(noBackend.backend);
});

test('validateCheckoutDraftStep enforces required fields per step when backend is connected', () => {
  const emptyStep1 = validateCheckoutDraftStep(1, INITIAL_CHECKOUT_DRAFT, 0, true);
  assert.ok(emptyStep1.cart);

  const badCustomer = validateCheckoutDraftStep(
    2,
    { ...INITIAL_CHECKOUT_DRAFT, fullName: 'A', email: 'bad' },
    1,
    true
  );
  assert.ok(badCustomer.fullName);
  assert.ok(badCustomer.email);

  const badAuth = validateCheckoutDraftStep(
    3,
    { ...INITIAL_CHECKOUT_DRAFT, password: '123' },
    1,
    true
  );
  assert.ok(badAuth.password);

  const badBilling = validateCheckoutDraftStep(4, INITIAL_CHECKOUT_DRAFT, 1, true);
  assert.ok(badBilling.addressLine1);
  assert.ok(badBilling.city);
  assert.ok(badBilling.postalCode);
  assert.ok(badBilling.country);

  const badGateway = validateCheckoutDraftStep(5, INITIAL_CHECKOUT_DRAFT, 1, true);
  assert.ok(badGateway.paymentGatewayId);

  const badTos = validateCheckoutDraftStep(6, INITIAL_CHECKOUT_DRAFT, 1, true);
  assert.ok(badTos.agreeTos);
});
