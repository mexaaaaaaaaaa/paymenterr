import React, { useState } from 'react';
import { ArrowRight, Tag, Trash2 } from 'lucide-react';
import type { PaymenterCartItem } from '../../theme/paymenter/contracts';
import { usePaymenter } from '../../theme/paymenter/PaymenterProvider';
import { RoughBox } from '../../theme/sketch';
import {
  Breadcrumbs,
  EmptyState,
  SectionBand,
  SketchAlert,
  SketchButton,
  SketchCard,
  SketchInput,
  SketchModal,
  useToast,
} from '../../theme/ui';

export interface CartPageProps {
  onContinueShopping: () => void;
  onProceedToCheckout: () => void;
}

export const CartPage: React.FC<CartPageProps> = ({
  onContinueShopping,
  onProceedToCheckout,
}) => {
  const { runtime, actions } = usePaymenter();
  const { notify } = useToast();
  const { items, summary } = runtime.cart;

  const [couponInput, setCouponInput] = useState('');
  const [couponError, setCouponError] = useState<string | undefined>();
  const [confirmRemoveItem, setConfirmRemoveItem] = useState<PaymenterCartItem | null>(null);

  const handleApplyCoupon = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!couponInput.trim()) {
      setCouponError('Enter a coupon code first.');
      return;
    }
    const res = await actions.applyCoupon(couponInput.trim());
    if (!res.ok) {
      setCouponError(res.reason ?? 'Coupon validation requires Paymenter backend.');
      return;
    }
    setCouponError(undefined);
    setCouponInput('');
    notify({
      tone: 'success',
      title: 'Coupon Applied',
      message: 'Discount updated by Paymenter.',
    });
  };

  return (
    <div className="space-y-8">
      <Breadcrumbs
        items={[
          { label: 'Home', onClick: onContinueShopping },
          { label: 'Shopping Cart' },
        ]}
      />

      <SectionBand
        title="Paymenter Shopping Cart"
        kicker="01. Order Review"
        description="Line items, coupon validation, discounts, tax breakdowns, and totals are supplied exclusively by Paymenter."
      >
        {!runtime.backendConnected && (
          <SketchAlert
            tone="warning"
            title="Paymenter Backend Not Connected"
            description={runtime.missingBackendMessage}
            className="mb-6"
          />
        )}

        {items.length === 0 ? (
          <EmptyState
            title="Your cart is empty"
            description={
              runtime.backendConnected
                ? 'No products are currently queued in your Paymenter cart.'
                : 'No demo cart items or fake coupon codes are fabricated here. Connect Paymenter to load real customer carts.'
            }
            actionLabel="Browse Catalog"
            onAction={onContinueShopping}
          />
        ) : (
          <div className="grid items-start gap-8 lg:grid-cols-12">
            <div className="space-y-4 lg:col-span-8">
              {items.map((item) => (
                <SketchCard key={item.id}>
                  <div className="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                    <div>
                      <h3 className="font-display text-xl font-bold text-[var(--pn-ink)]">
                        {item.productName}
                      </h3>
                      <p className="text-xs font-semibold text-[var(--pn-blossom-700)]">
                        {item.planLabel}
                      </p>
                      {item.selectedOptionsSummary.length > 0 && (
                        <ul className="mt-2 space-y-1 text-xs text-[var(--pn-ink-soft)]">
                          {item.selectedOptionsSummary.map((opt) => (
                            <li key={opt.name}>
                              {opt.name}: {opt.value}
                            </li>
                          ))}
                        </ul>
                      )}
                    </div>

                    <div className="text-left sm:text-right">
                      <p className="font-display text-xl font-bold text-[var(--pn-ink)] tabular-nums">
                        {item.formattedLineTotal}
                      </p>
                      <p className="text-xs text-[var(--pn-ink-soft)] tabular-nums">
                        Unit: {item.formattedUnitPrice}
                      </p>
                    </div>
                  </div>

                  <div className="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-[var(--pn-paper-line)] pt-3">
                    {item.allowQuantity ? (
                      <div className="flex items-center gap-2">
                        <span className="text-xs font-semibold text-[var(--pn-ink-soft)]">Qty</span>
                        <button
                          type="button"
                          aria-label={`Decrease quantity for ${item.productName}`}
                          onClick={() => actions.updateCartQuantity(item.id, item.quantity - 1)}
                          className="h-9 w-9 rounded border border-[var(--pn-paper-line)] bg-[var(--pn-paper)] font-bold cursor-pointer"
                        >
                          -
                        </button>
                        <span className="w-6 text-center font-mono text-sm font-bold tabular-nums">
                          {item.quantity}
                        </span>
                        <button
                          type="button"
                          aria-label={`Increase quantity for ${item.productName}`}
                          onClick={() => actions.updateCartQuantity(item.id, item.quantity + 1)}
                          className="h-9 w-9 rounded border border-[var(--pn-paper-line)] bg-[var(--pn-paper)] font-bold cursor-pointer"
                        >
                          +
                        </button>
                      </div>
                    ) : (
                      <span className="text-xs text-[var(--pn-ink-soft)]">Quantity fixed at 1</span>
                    )}

                    <SketchButton
                      variant="ghost"
                      size="sm"
                      iconLeft={<Trash2 size={14} />}
                      onClick={() => setConfirmRemoveItem(item)}
                    >
                      Remove
                    </SketchButton>
                  </div>
                </SketchCard>
              ))}
            </div>

            <div className="space-y-5 lg:col-span-4">
              <SketchCard>
                <h3 className="font-display text-base font-bold text-[var(--pn-ink)]">
                  Coupon Code
                </h3>
                <p className="mt-1 text-xs text-[var(--pn-ink-soft)]">
                  Validated exclusively against your Paymenter installation.
                </p>

                <form onSubmit={handleApplyCoupon} className="mt-3 space-y-3">
                  <SketchInput
                    label="Coupon Code"
                    placeholder="Enter coupon code..."
                    value={couponInput}
                    onChange={(e) => setCouponInput(e.target.value)}
                    error={couponError}
                  />
                  <SketchButton
                    type="submit"
                    variant="secondary"
                    size="sm"
                    iconLeft={<Tag size={14} />}
                    className="w-full"
                  >
                    Apply Coupon
                  </SketchButton>
                </form>

                {summary?.appliedCouponCode && (
                  <div className="mt-3 flex items-center justify-between rounded-[var(--pn-radius-sm)] border border-[var(--pn-moss-700)]/35 bg-[var(--pn-moss-100)] px-3 py-2 text-xs">
                    <span className="font-semibold text-[var(--pn-ink)]">
                      {summary.appliedCouponCode}
                    </span>
                    <button
                      type="button"
                      onClick={() => actions.removeCoupon()}
                      className="font-bold text-[var(--pn-status-danger)] hover:underline cursor-pointer"
                    >
                      Remove
                    </button>
                  </div>
                )}
              </SketchCard>

              {summary && (
                <RoughBox seedKey="cart-summary" strokeColor="#2B2230" className="bg-[#FFFDF9]" paddingClass="p-6">
                  <h3 className="font-display text-lg font-bold text-[var(--pn-ink)]">
                    Order Summary
                  </h3>
                  <dl className="mt-4 space-y-2 text-sm">
                    <div className="flex justify-between">
                      <dt className="text-[var(--pn-ink-soft)]">Subtotal</dt>
                      <dd className="font-semibold tabular-nums">{summary.formattedSubtotal}</dd>
                    </div>
                    {summary.formattedDiscount && (
                      <div className="flex justify-between">
                        <dt className="text-[var(--pn-ink-soft)]">Discount</dt>
                        <dd className="font-semibold text-[var(--pn-moss-700)] tabular-nums">
                          -{summary.formattedDiscount}
                        </dd>
                      </div>
                    )}
                    {summary.formattedTax && (
                      <div className="flex justify-between">
                        <dt className="text-[var(--pn-ink-soft)]">Tax</dt>
                        <dd className="font-semibold tabular-nums">{summary.formattedTax}</dd>
                      </div>
                    )}
                    <div className="flex justify-between border-t border-[var(--pn-paper-line)] pt-3 font-display text-lg font-bold">
                      <dt>Total Due</dt>
                      <dd className="text-[var(--pn-blossom-700)] tabular-nums">
                        {summary.formattedTotal}
                      </dd>
                    </div>
                  </dl>

                  <div className="mt-5">
                    <SketchButton
                      variant="primary"
                      size="lg"
                      iconRight={<ArrowRight size={17} />}
                      className="w-full"
                      onClick={onProceedToCheckout}
                    >
                      Proceed to Checkout
                    </SketchButton>
                  </div>
                </RoughBox>
              )}
            </div>
          </div>
        )}
      </SectionBand>

      <SketchModal
        open={Boolean(confirmRemoveItem)}
        onClose={() => setConfirmRemoveItem(null)}
        title="Remove item from cart?"
        description={
          confirmRemoveItem
            ? `Remove ${confirmRemoveItem.productName} from your cart?`
            : undefined
        }
        footer={
          <>
            <SketchButton variant="secondary" onClick={() => setConfirmRemoveItem(null)}>
              Keep Item
            </SketchButton>
            <SketchButton
              variant="primary"
              onClick={async () => {
                if (confirmRemoveItem) {
                  await actions.removeCartItem(confirmRemoveItem.id);
                }
                setConfirmRemoveItem(null);
              }}
            >
              Confirm Remove
            </SketchButton>
          </>
        }
      >
        <p className="text-sm text-[var(--pn-ink-soft)]">
          Changes sync with your Paymenter session cart.
        </p>
      </SketchModal>
    </div>
  );
};
