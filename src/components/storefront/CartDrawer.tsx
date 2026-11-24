import React from 'react';
import { Trash2 } from 'lucide-react';
import { usePaymenter } from '../../theme/paymenter/PaymenterProvider';
import { EmptyState, SketchButton, SketchDrawer, useToast } from '../../theme/ui';

export interface CartDrawerProps {
  open: boolean;
  onClose: () => void;
  onOpenFullCart: () => void;
  onProceedToCheckout: () => void;
  onBrowseCatalog: () => void;
}

export const CartDrawer: React.FC<CartDrawerProps> = ({
  open,
  onClose,
  onOpenFullCart,
  onProceedToCheckout,
  onBrowseCatalog,
}) => {
  const { runtime, actions } = usePaymenter();
  const { notify } = useToast();
  const { items, summary } = runtime.cart;

  const handleQty = async (itemId: string, nextQty: number) => {
    const res = await actions.updateCartQuantity(itemId, nextQty);
    if (!res.ok && res.reason) {
      notify({ tone: 'warning', title: 'Backend Required', message: res.reason });
    }
  };

  const handleRemove = async (itemId: string) => {
    const res = await actions.removeCartItem(itemId);
    if (!res.ok && res.reason) {
      notify({ tone: 'warning', title: 'Backend Required', message: res.reason });
    }
  };

  return (
    <SketchDrawer
      open={open}
      onClose={onClose}
      title={`Your Paymenter Cart (${summary?.itemCount ?? 0})`}
      footer={
        items.length > 0 && summary ? (
          <div className="space-y-3">
            <dl className="space-y-1.5 text-sm">
              <div className="flex justify-between text-[var(--pn-ink-soft)]">
                <dt>Subtotal</dt>
                <dd className="tabular-nums">{summary.formattedSubtotal}</dd>
              </div>
              {summary.formattedDiscount && (
                <div className="flex justify-between font-semibold text-[var(--pn-moss-700)]">
                  <dt>Discount ({summary.appliedCouponCode})</dt>
                  <dd className="tabular-nums">-{summary.formattedDiscount}</dd>
                </div>
              )}
              {summary.formattedTax && (
                <div className="flex justify-between text-[var(--pn-ink-soft)]">
                  <dt>Tax</dt>
                  <dd className="tabular-nums">{summary.formattedTax}</dd>
                </div>
              )}
              <div className="flex justify-between border-t border-[var(--pn-paper-line)] pt-2 font-display text-base font-bold text-[var(--pn-ink)]">
                <dt>Total</dt>
                <dd className="tabular-nums text-[var(--pn-blossom-700)]">
                  {summary.formattedTotal}
                </dd>
              </div>
            </dl>
            <div className="flex flex-col gap-2">
              <SketchButton
                variant="primary"
                className="w-full"
                onClick={() => {
                  onClose();
                  onProceedToCheckout();
                }}
              >
                Proceed to Checkout
              </SketchButton>
              <div className="flex gap-2">
                <SketchButton
                  variant="secondary"
                  size="sm"
                  className="flex-1"
                  onClick={() => {
                    onClose();
                    onOpenFullCart();
                  }}
                >
                  Full Cart Page
                </SketchButton>
                <SketchButton variant="ghost" size="sm" className="flex-1" onClick={onClose}>
                  Continue Shopping
                </SketchButton>
              </div>
            </div>
          </div>
        ) : (
          <SketchButton
            variant="secondary"
            className="w-full"
            onClick={() => {
              onClose();
              onBrowseCatalog();
            }}
          >
            Browse Catalog
          </SketchButton>
        )
      }
    >
      {items.length === 0 ? (
        <EmptyState
          title="Your cart is empty"
          description={
            runtime.backendConnected
              ? 'Select a product from the catalog to add it to your Paymenter cart.'
              : 'Cart items and server-side pricing load directly from Paymenter once connected.'
          }
          actionLabel="View Catalog"
          onAction={() => {
            onClose();
            onBrowseCatalog();
          }}
        />
      ) : (
        <div className="space-y-4">
          {items.map((item) => (
            <div
              key={item.id}
              className="rounded-[var(--pn-radius-md)] border border-[var(--pn-paper-line)] bg-[var(--pn-paper)]/65 p-4"
            >
              <div className="flex items-start justify-between gap-3">
                <div>
                  <h4 className="font-display text-base font-bold text-[var(--pn-ink)]">
                    {item.productName}
                  </h4>
                  <p className="text-xs text-[var(--pn-ink-soft)]">{item.planLabel}</p>
                  {item.selectedOptionsSummary.length > 0 && (
                    <p className="mt-1 text-xs text-[var(--pn-ink-soft)]">
                      {item.selectedOptionsSummary.map((o) => `${o.name}: ${o.value}`).join(' · ')}
                    </p>
                  )}
                </div>
                <span className="font-display text-sm font-bold text-[var(--pn-ink)] tabular-nums">
                  {item.formattedLineTotal}
                </span>
              </div>

              <div className="mt-3 flex items-center justify-between gap-2 border-t border-[var(--pn-paper-line)] pt-3">
                {item.allowQuantity ? (
                  <div className="flex items-center gap-1.5">
                    <button
                      type="button"
                      aria-label={`Decrease quantity for ${item.productName}`}
                      onClick={() => handleQty(item.id, item.quantity - 1)}
                      className="h-8 w-8 rounded border border-[var(--pn-paper-line)] bg-[#FFFDF9] text-xs font-bold cursor-pointer"
                    >
                      -
                    </button>
                    <span className="w-6 text-center font-mono text-xs font-bold tabular-nums">
                      {item.quantity}
                    </span>
                    <button
                      type="button"
                      aria-label={`Increase quantity for ${item.productName}`}
                      onClick={() => handleQty(item.id, item.quantity + 1)}
                      className="h-8 w-8 rounded border border-[var(--pn-paper-line)] bg-[#FFFDF9] text-xs font-bold cursor-pointer"
                    >
                      +
                    </button>
                  </div>
                ) : (
                  <span className="text-xs text-[var(--pn-ink-soft)]">Qty: 1</span>
                )}

                <button
                  type="button"
                  aria-label={`Remove ${item.productName}`}
                  onClick={() => handleRemove(item.id)}
                  className="inline-flex h-8 items-center gap-1 rounded px-2 text-xs font-semibold text-[var(--pn-status-danger)] hover:bg-[var(--pn-blossom-50)] cursor-pointer"
                >
                  <Trash2 size={12} />
                  <span>Remove</span>
                </button>
              </div>
            </div>
          ))}
        </div>
      )}
    </SketchDrawer>
  );
};
