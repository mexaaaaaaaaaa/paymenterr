/**
 * Presentation-only checkout step input validation.
 * Cart state and authoritative totals belong strictly to the Paymenter backend.
 */

export interface CheckoutFormDraft {
  fullName: string;
  email: string;
  authMode: 'login' | 'register';
  password: string;
  addressLine1: string;
  city: string;
  postalCode: string;
  country: string;
  companyTaxId: string;
  paymentGatewayId: string;
  agreeTos: boolean;
}

export const INITIAL_CHECKOUT_DRAFT: CheckoutFormDraft = {
  fullName: '',
  email: '',
  authMode: 'register',
  password: '',
  addressLine1: '',
  city: '',
  postalCode: '',
  country: '',
  companyTaxId: '',
  paymentGatewayId: '',
  agreeTos: false,
};

export function validateCheckoutDraftStep(
  step: number,
  data: CheckoutFormDraft,
  cartItemCount: number,
  backendConnected: boolean
): Record<string, string> {
  const errors: Record<string, string> = {};

  if (!backendConnected) {
    errors.backend = 'Paymenter backend connection is required to validate or submit orders.';
    return errors;
  }

  if (step === 1 && cartItemCount === 0) {
    errors.cart = 'Your Paymenter cart is empty.';
  }

  if (step === 2) {
    if (!data.fullName.trim() || data.fullName.trim().length < 2) {
      errors.fullName = 'Please enter your full name.';
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email.trim())) {
      errors.email = 'Please enter a valid email address.';
    }
  }

  if (step === 3) {
    if (!data.password || data.password.length < 8) {
      errors.password = 'Enter at least 8 characters.';
    }
  }

  if (step === 4) {
    if (!data.addressLine1.trim()) errors.addressLine1 = 'Street address is required.';
    if (!data.city.trim()) errors.city = 'City is required.';
    if (!data.postalCode.trim()) errors.postalCode = 'Postal code is required.';
    if (!data.country.trim()) errors.country = 'Country is required.';
  }

  if (step === 5 && !data.paymentGatewayId) {
    errors.paymentGatewayId = 'Select an active Paymenter payment gateway.';
  }

  if (step === 6 && !data.agreeTos) {
    errors.agreeTos = 'Accept the Terms of Service to proceed.';
  }

  return errors;
}
