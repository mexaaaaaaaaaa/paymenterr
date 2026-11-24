import React, { useState } from 'react';
import { ArrowLeft, ArrowRight, CheckCircle2, Lock } from 'lucide-react';
import {
  INITIAL_CHECKOUT_DRAFT,
  validateCheckoutDraftStep,
  type CheckoutFormDraft,
} from '../../store/cartReducer';
import { usePaymenter } from '../../theme/paymenter/PaymenterProvider';
import { NodeyMascot, RoughBox } from '../../theme/sketch';
import {
  Breadcrumbs,
  EmptyState,
  SectionBand,
  SegmentedFilter,
  SketchAlert,
  SketchButton,
  SketchCard,
  SketchCheckbox,
  SketchInput,
  useToast,
} from '../../theme/ui';

export interface CheckoutPageProps {
  onBackToCart: () => void;
  onBrowseCatalog: () => void;
}

const CHECKOUT_STEPS = [
  { id: 1, title: 'Review Order' },
  { id: 2, title: 'Customer Info' },
  { id: 3, title: 'Account Auth' },
  { id: 4, title: 'Billing Info' },
  { id: 5, title: 'Payment Method' },
  { id: 6, title: 'Final Review' },
  { id: 7, title: 'Paymenter Submit' },
];

export const CheckoutPage: React.FC<CheckoutPageProps> = ({
  onBackToCart,
  onBrowseCatalog,
}) => {
  const { runtime, actions } = usePaymenter();
  const { notify } = useToast();
  const { items, summary } = runtime.cart;

  const [step, setStep] = useState(1);
  const [form, setForm] = useState<CheckoutFormDraft>(INITIAL_CHECKOUT_DRAFT);
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [submitMessage, setSubmitMessage] = useState<string | null>(null);

  const updateField = <K extends keyof CheckoutFormDraft>(key: K, value: CheckoutFormDraft[K]) => {
    setForm((prev) => ({ ...prev, [key]: value }));
    setErrors((prev) => {
      if (!prev[key]) return prev;
      const copy = { ...prev };
      delete copy[key];
      return copy;
    });
  };

  const handleNext = async () => {
    const stepErrors = validateCheckoutDraftStep(
      step,
      form,
      items.length,
      runtime.backendConnected
    );
    setErrors(stepErrors);
    if (Object.keys(stepErrors).length > 0) {
      notify({
        tone: 'danger',
        title: 'Cannot proceed',
        message: Object.values(stepErrors)[0],
      });
      return;
    }

    if (step === 6) {
      const res = await actions.submitCheckout({
        gatewayId: form.paymentGatewayId,
        acceptedTerms: form.agreeTos,
      });
      if (!res.ok) {
        setSubmitMessage(
          res.reason ?? 'Checkout submission must run inside the real Paymenter backend.'
        );
      } else {
        setSubmitMessage('Order handed off to Paymenter.');
      }
      setStep(7);
      return;
    }

    setStep((s) => Math.min(7, s + 1));
  };

  if (!runtime.backendConnected || items.length === 0) {
    return (
      <div className="space-y-8">
        <Breadcrumbs
          items={[
            { label: 'Home', onClick: onBrowseCatalog },
            { label: 'Cart', onClick: onBackToCart },
            { label: 'Checkout' },
          ]}
        />
        <SectionBand
          title="Paymenter Checkout Presentation"
          kicker="02. Authentic Checkout Flow"
          description="Checkout preserves Paymenter cart sessions, authentication, tax engine, CSRF validation, and gateway redirects."
        >
          <SketchAlert
            tone="warning"
            title="Paymenter Repository Required for Checkout"
            description={runtime.missingBackendMessage}
            className="mb-6"
          />
          <EmptyState
            title={
              runtime.backendConnected
                ? 'Your checkout cart is empty'
                : 'Checkout Disabled Without Paymenter Backend'
            }
            description={
              runtime.backendConnected
                ? 'Add a product from the Paymenter catalog before starting checkout.'
                : 'No fake gateways, fake coupons, or simulated order approvals are present in this theme.'
            }
            actionLabel="Back to Catalog"
            onAction={onBrowseCatalog}
          />
        </SectionBand>
      </div>
    );
  }

  return (
    <div className="space-y-8">
      <Breadcrumbs
        items={[
          { label: 'Home', onClick: onBrowseCatalog },
          { label: 'Cart', onClick: onBackToCart },
          { label: 'Checkout' },
        ]}
      />

      <SectionBand
        title="Paymenter Checkout"
        kicker="02. Secure Order Flow"
        description="Presents Paymenter's existing checkout, authentication, billing, and configured payment gateway selection."
      >
        <div aria-label="Checkout progress" className="mb-8 grid grid-cols-2 gap-2 sm:grid-cols-4 lg:grid-cols-7">
          {CHECKOUT_STEPS.map((s) => {
            const active = s.id === step;
            const done = s.id < step;
            return (
              <div
                key={s.id}
                className={`rounded-[var(--pn-radius-sm)] border px-3 py-2 text-xs ${
                  active
                    ? 'border-[var(--pn-blossom-700)] bg-[var(--pn-blossom-50)] font-bold text-[var(--pn-ink)]'
                    : done
                      ? 'border-[var(--pn-moss-700)]/40 bg-[var(--pn-moss-100)]/65 font-semibold text-[var(--pn-ink)]'
                      : 'border-[var(--pn-paper-line)] bg-[#FFFDF9] text-[var(--pn-ink-soft)]'
                }`}
              >
                <span className="font-mono mr-1">{s.id}.</span>
                <span>{s.title}</span>
              </div>
            );
          })}
        </div>

        <div className="grid items-start gap-8 lg:grid-cols-12">
          <div className="lg:col-span-8">
            <SketchCard>
              {step === 1 && (
                <div className="space-y-4">
                  <h2 className="font-display text-xl font-bold text-[var(--pn-ink)]">
                    Step 1 — Review Order
                  </h2>
                  {items.map((item) => (
                    <div
                      key={item.id}
                      className="flex flex-wrap items-center justify-between gap-3 rounded-[var(--pn-radius-sm)] border border-[var(--pn-paper-line)] bg-[var(--pn-paper)]/65 p-4"
                    >
                      <div>
                        <p className="font-display font-bold text-[var(--pn-ink)]">
                          {item.productName} × {item.quantity}
                        </p>
                        <p className="text-xs text-[var(--pn-ink-soft)]">{item.planLabel}</p>
                      </div>
                      <span className="font-display font-bold tabular-nums">{item.formattedLineTotal}</span>
                    </div>
                  ))}
                </div>
              )}

              {step === 2 && (
                <div className="space-y-4">
                  <h2 className="font-display text-xl font-bold text-[var(--pn-ink)]">
                    Step 2 — Customer Information
                  </h2>
                  <div className="grid gap-4 sm:grid-cols-2">
                    <SketchInput
                      label="Full Name"
                      value={form.fullName}
                      onChange={(e) => updateField('fullName', e.target.value)}
                      error={errors.fullName}
                      required
                    />
                    <SketchInput
                      label="Email Address"
                      type="email"
                      value={form.email}
                      onChange={(e) => updateField('email', e.target.value)}
                      error={errors.email}
                      required
                    />
                  </div>
                </div>
              )}

              {step === 3 && (
                <div className="space-y-4">
                  <h2 className="font-display text-xl font-bold text-[var(--pn-ink)]">
                    Step 3 — Paymenter Account Auth
                  </h2>
                  <SegmentedFilter
                    ariaLabel="Account mode"
                    activeId={form.authMode}
                    onChange={(id) => updateField('authMode', id as 'login' | 'register')}
                    options={[
                      { id: 'register', label: 'Create Account' },
                      { id: 'login', label: 'Sign In' },
                    ]}
                  />
                  <SketchInput
                    label="Password"
                    type="password"
                    value={form.password}
                    onChange={(e) => updateField('password', e.target.value)}
                    error={errors.password}
                    required
                  />
                </div>
              )}

              {step === 4 && (
                <div className="space-y-4">
                  <h2 className="font-display text-xl font-bold text-[var(--pn-ink)]">
                    Step 4 — Billing Information
                  </h2>
                  <div className="grid gap-4 sm:grid-cols-2">
                    <div className="sm:col-span-2">
                      <SketchInput
                        label="Street Address"
                        value={form.addressLine1}
                        onChange={(e) => updateField('addressLine1', e.target.value)}
                        error={errors.addressLine1}
                        required
                      />
                    </div>
                    <SketchInput
                      label="City"
                      value={form.city}
                      onChange={(e) => updateField('city', e.target.value)}
                      error={errors.city}
                      required
                    />
                    <SketchInput
                      label="Postal Code"
                      value={form.postalCode}
                      onChange={(e) => updateField('postalCode', e.target.value)}
                      error={errors.postalCode}
                      required
                    />
                    <SketchInput
                      label="Country"
                      value={form.country}
                      onChange={(e) => updateField('country', e.target.value)}
                      error={errors.country}
                      required
                    />
                  </div>
                </div>
              )}

              {step === 5 && (
                <div className="space-y-4">
                  <h2 className="font-display text-xl font-bold text-[var(--pn-ink)]">
                    Step 5 — Configured Payment Gateways
                  </h2>
                  {runtime.gateways.length === 0 ? (
                    <EmptyState
                      title="No payment gateways returned"
                      description="Available gateways are supplied directly by your Paymenter installation."
                    />
                  ) : (
                    <div className="space-y-3">
                      {runtime.gateways.map((gw) => {
                        const selected = form.paymentGatewayId === gw.id;
                        return (
                          <button
                            key={gw.id}
                            type="button"
                            disabled={!gw.available}
                            onClick={() => updateField('paymentGatewayId', gw.id)}
                            className={`flex w-full items-start justify-between gap-4 rounded-[var(--pn-radius-md)] border-[1.5px] p-4 text-left ${
                              selected
                                ? 'border-[var(--pn-blossom-700)] bg-[var(--pn-blossom-50)]'
                                : 'border-[var(--pn-paper-line)] bg-[#FFFDF9]'
                            }`}
                          >
                            <div>
                              <p className="font-display text-base font-bold text-[var(--pn-ink)]">
                                {gw.name}
                              </p>
                              {gw.description && (
                                <p className="mt-0.5 text-xs text-[var(--pn-ink-soft)]">
                                  {gw.description}
                                </p>
                              )}
                            </div>
                            {selected && (
                              <CheckCircle2 size={18} className="text-[var(--pn-blossom-700)] shrink-0" />
                            )}
                          </button>
                        );
                      })}
                    </div>
                  )}
                </div>
              )}

              {step === 6 && (
                <div className="space-y-4">
                  <h2 className="font-display text-xl font-bold text-[var(--pn-ink)]">
                    Step 6 — Final Order Review
                  </h2>
                  <SketchCheckbox
                    label="I accept the Terms of Service & Privacy Policy"
                    checked={form.agreeTos}
                    onChange={(e) => updateField('agreeTos', e.target.checked)}
                  />
                  {errors.agreeTos && (
                    <p role="alert" className="text-xs font-semibold text-[var(--pn-status-danger)]">
                      {errors.agreeTos}
                    </p>
                  )}
                </div>
              )}

              {step === 7 && (
                <div className="flex items-start gap-4">
                  <NodeyMascot mood="working" size={72} />
                  <div>
                    <h2 className="font-display text-xl font-bold text-[var(--pn-ink)]">
                      Step 7 — Paymenter Backend Handoff
                    </h2>
                    <p className="mt-1 text-sm text-[var(--pn-ink-soft)]">
                      {submitMessage ?? 'Awaiting backend order verification.'}
                    </p>
                  </div>
                </div>
              )}

              <div className="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-[var(--pn-paper-line)] pt-5">
                {step > 1 ? (
                  <SketchButton
                    variant="secondary"
                    iconLeft={<ArrowLeft size={16} />}
                    onClick={() => setStep((s) => Math.max(1, s - 1))}
                  >
                    Previous Step
                  </SketchButton>
                ) : (
                  <SketchButton variant="ghost" onClick={onBackToCart}>
                    Back to Cart
                  </SketchButton>
                )}

                {step < 7 && (
                  <SketchButton
                    variant="primary"
                    iconRight={<ArrowRight size={16} />}
                    onClick={handleNext}
                  >
                    {step === 6 ? 'Submit to Paymenter' : 'Continue'}
                  </SketchButton>
                )}
              </div>
            </SketchCard>
          </div>

          <div className="space-y-4 lg:col-span-4">
            {summary && (
              <RoughBox seedKey="checkout-summary" strokeColor="#2B2230" className="bg-[#FFFDF9]" paddingClass="p-6">
                <h3 className="font-display text-lg font-bold text-[var(--pn-ink)]">
                  Order Summary
                </h3>
                <dl className="mt-4 space-y-2 text-sm">
                  <div className="flex justify-between">
                    <dt className="text-[var(--pn-ink-soft)]">Subtotal</dt>
                    <dd className="tabular-nums">{summary.formattedSubtotal}</dd>
                  </div>
                  {summary.formattedDiscount && (
                    <div className="flex justify-between text-[var(--pn-moss-700)]">
                      <dt>Discount</dt>
                      <dd className="tabular-nums">-{summary.formattedDiscount}</dd>
                    </div>
                  )}
                  {summary.formattedTax && (
                    <div className="flex justify-between">
                      <dt className="text-[var(--pn-ink-soft)]">Tax</dt>
                      <dd className="tabular-nums">{summary.formattedTax}</dd>
                    </div>
                  )}
                  <div className="flex justify-between border-t border-[var(--pn-paper-line)] pt-3 font-display text-lg font-bold">
                    <dt>Total Due</dt>
                    <dd className="text-[var(--pn-blossom-700)] tabular-nums">
                      {summary.formattedTotal}
                    </dd>
                  </div>
                </dl>
              </RoughBox>
            )}
            <div className="flex items-start gap-2 rounded-[var(--pn-radius-md)] border border-[var(--pn-paper-line)] bg-[var(--pn-paper-shade)]/50 p-3 text-xs text-[var(--pn-ink-soft)]">
              <Lock size={15} className="mt-0.5 shrink-0" aria-hidden="true" />
              <span>Order totals, auth, CSRF, and payment redirects run exclusively in Paymenter.</span>
            </div>
          </div>
        </div>
      </SectionBand>
    </div>
  );
};
