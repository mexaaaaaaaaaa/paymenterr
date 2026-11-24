import React, { useEffect, useMemo, useState } from 'react';
import { CheckCircle2, RotateCcw, ShoppingBag } from 'lucide-react';
import {
  createDefaultProductSelection,
  validateProductSelection,
  type ProductOptionSelection,
} from '../../store/pricing';
import type { PaymenterProduct } from '../../theme/paymenter/contracts';
import { usePaymenter } from '../../theme/paymenter/PaymenterProvider';
import { PolaroidFrame, RoughBox, Stamp } from '../../theme/sketch';
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
  SketchSelect,
  useToast,
} from '../../theme/ui';
import { SketchAccordion, SketchIllustration } from './SketchIllustration';

export interface ProductDetailPageProps {
  product: PaymenterProduct | null;
  onBackToCatalog: () => void;
}

export const ProductDetailPage: React.FC<ProductDetailPageProps> = ({
  product,
  onBackToCatalog,
}) => {
  const { runtime, actions } = usePaymenter();
  const { notify } = useToast();

  const [selection, setSelection] = useState<ProductOptionSelection>(() =>
    product
      ? createDefaultProductSelection(product)
      : { planId: '', selectedChoices: {} }
  );
  const [quantity, setQuantity] = useState(1);
  const [actionError, setActionError] = useState<string | null>(null);

  useEffect(() => {
    if (product) {
      setSelection(createDefaultProductSelection(product));
      setQuantity(1);
      setActionError(null);
    }
  }, [product]);

  const activePlan = useMemo(
    () =>
      product
        ? product.plans.find((p) => p.id === selection.planId) ?? product.plans[0]
        : undefined,
    [product, selection.planId]
  );

  const validation = useMemo(
    () =>
      product
        ? validateProductSelection(product, selection)
        : { valid: false, errors: [] },
    [product, selection]
  );

  if (!product) {
    return (
      <div className="space-y-8">
        <Breadcrumbs
          items={[
            { label: 'Catalog', onClick: onBackToCatalog },
            { label: 'Product Details' },
          ]}
        />
        <EmptyState
          title="No Paymenter product selected"
          description="Select an active product from the Paymenter catalog once connected to view specifications and configurable options."
          actionLabel="Back to Catalog"
          onAction={onBackToCatalog}
        />
      </div>
    );
  }

  const handleReset = () => {
    setSelection(createDefaultProductSelection(product));
    setQuantity(1);
    setActionError(null);
  };

  const handleSubmit = async () => {
    if (!validation.valid) return;
    const res = await actions.addToCart({
      productId: product.id,
      planId: selection.planId,
      quantity,
      selectedOptions: selection.selectedChoices,
    });
    if (!res.ok) {
      setActionError(res.reason ?? 'Backend action unavailable.');
      notify({
        tone: 'warning',
        title: 'Paymenter Backend Required',
        message: res.reason ?? 'Cannot add items without a live Paymenter backend.',
      });
    } else {
      notify({
        tone: 'success',
        title: `Added ${product.name}`,
        message: 'Cart updated via Paymenter backend.',
      });
    }
  };

  return (
    <div className="space-y-12">
      <Breadcrumbs
        items={[
          { label: 'Catalog', onClick: onBackToCatalog },
          { label: product.name },
        ]}
      />

      <section className="grid items-start gap-8 lg:grid-cols-12">
        <div className="space-y-6 lg:col-span-5">
          <PolaroidFrame
            caption={product.name}
            subcaption={product.categorySlug}
            tapeTone="blossom"
            tiltDeg={-1}
          >
            <div className="relative aspect-[4/3] w-full overflow-hidden">
              <SketchIllustration
                kind="island"
                src={product.imageUrl}
                alt={product.name}
              />
              {product.badgeText && (
                <div className="absolute top-3 right-3">
                  <Stamp label={product.badgeText} tone={product.inStock ? 'moss' : 'bark'} rotateDeg={4} />
                </div>
              )}
            </div>
          </PolaroidFrame>

          {product.specs.length > 0 && (
            <SketchCard surface="shade">
              <h2 className="font-display text-lg font-bold text-[var(--pn-ink)]">
                Product Specifications
              </h2>
              <dl className="mt-4 grid grid-cols-2 gap-3 text-xs">
                {product.specs.map((spec) => (
                  <div key={spec.label} className="rounded-[var(--pn-radius-sm)] bg-[#FFFDF9] p-3">
                    <dt className="text-[var(--pn-ink-soft)]">{spec.label}</dt>
                    <dd className="mt-0.5 font-semibold text-[var(--pn-ink)]">{spec.value}</dd>
                  </div>
                ))}
              </dl>
            </SketchCard>
          )}
        </div>

        <div className="space-y-6 lg:col-span-7">
          <div>
            <h1 className="font-display text-3xl font-bold text-[var(--pn-ink)]">{product.name}</h1>
            <p className="mt-2 text-base text-[var(--pn-ink-soft)]">{product.description}</p>
          </div>

          <SketchCard>
            <div className="flex flex-wrap items-center justify-between gap-3">
              <h2 className="font-display text-base font-bold text-[var(--pn-ink)]">
                1. Billing Plan (From Paymenter)
              </h2>
              <SketchButton
                variant="ghost"
                size="sm"
                iconLeft={<RotateCcw size={14} />}
                onClick={handleReset}
              >
                Reset
              </SketchButton>
            </div>

            {product.plans.length > 0 ? (
              <div className="mt-4">
                <SegmentedFilter
                  ariaLabel="Select Paymenter billing plan"
                  activeId={selection.planId}
                  onChange={(id) => setSelection((p) => ({ ...p, planId: id }))}
                  options={product.plans.map((pl) => ({
                    id: pl.id,
                    label: `${pl.label} · ${pl.formattedPrice}`,
                  }))}
                />
              </div>
            ) : (
              <p className="mt-3 text-sm text-[var(--pn-ink-soft)]">
                No billing cycles provided by backend.
              </p>
            )}

            {product.configOptions.length > 0 && (
              <div className="mt-6 space-y-5 border-t border-[var(--pn-paper-line)] pt-5">
                <h2 className="font-display text-base font-bold text-[var(--pn-ink)]">
                  2. Configurable Options
                </h2>
                {product.configOptions.map((opt) => {
                  const val = selection.selectedChoices[opt.id];
                  if (opt.type === 'select') {
                    return (
                      <SketchSelect
                        key={opt.id}
                        label={opt.name}
                        required={opt.required}
                        value={typeof val === 'string' ? val : ''}
                        onChange={(e) =>
                          setSelection((prev) => ({
                            ...prev,
                            selectedChoices: { ...prev.selectedChoices, [opt.id]: e.target.value },
                          }))
                        }
                        options={opt.choices.map((c) => ({
                          value: c.id,
                          disabled: !c.available,
                          label: `${c.label}${c.formattedPriceDelta ? ` (${c.formattedPriceDelta})` : ''}${!c.available ? ' (Unavailable)' : ''}`,
                        }))}
                      />
                    );
                  }

                  if (opt.type === 'radio') {
                    return (
                      <div key={opt.id}>
                        <p className="mb-2 text-sm font-semibold text-[var(--pn-ink)]">{opt.name}</p>
                        <div className="grid gap-2.5 sm:grid-cols-2">
                          {opt.choices.map((choice) => {
                            const selected = val === choice.id;
                            return (
                              <button
                                key={choice.id}
                                type="button"
                                disabled={!choice.available}
                                onClick={() =>
                                  setSelection((prev) => ({
                                    ...prev,
                                    selectedChoices: { ...prev.selectedChoices, [opt.id]: choice.id },
                                  }))
                                }
                                className={`flex items-center justify-between rounded-[var(--pn-radius-md)] border-[1.5px] p-3 text-left ${
                                  !choice.available
                                    ? 'cursor-not-allowed opacity-55 border-[var(--pn-paper-line)] bg-[var(--pn-paper-shade)]/40'
                                    : selected
                                      ? 'cursor-pointer border-[var(--pn-blossom-700)] bg-[var(--pn-blossom-50)]'
                                      : 'cursor-pointer border-[var(--pn-paper-line)] bg-[#FFFDF9]'
                                }`}
                              >
                                <span className="text-sm font-semibold text-[var(--pn-ink)]">
                                  {choice.label}
                                </span>
                                {choice.formattedPriceDelta && (
                                  <span className="font-mono text-xs text-[var(--pn-ink-soft)]">
                                    {choice.formattedPriceDelta}
                                  </span>
                                )}
                              </button>
                            );
                          })}
                        </div>
                      </div>
                    );
                  }

                  if (opt.type === 'checkbox') {
                    const selectedList = Array.isArray(val) ? val : [];
                    return (
                      <div key={opt.id} className="space-y-2">
                        <p className="text-sm font-semibold text-[var(--pn-ink)]">{opt.name}</p>
                        {opt.choices.map((choice) => (
                          <SketchCheckbox
                            key={choice.id}
                            label={choice.label}
                            description={choice.formattedPriceDelta}
                            disabled={!choice.available}
                            checked={selectedList.includes(choice.id)}
                            onChange={(e) => {
                              const next = e.target.checked
                                ? [...selectedList, choice.id]
                                : selectedList.filter((x) => x !== choice.id);
                              setSelection((prev) => ({
                                ...prev,
                                selectedChoices: { ...prev.selectedChoices, [opt.id]: next },
                              }));
                            }}
                          />
                        ))}
                      </div>
                    );
                  }

                  return (
                    <SketchInput
                      key={opt.id}
                      label={opt.name}
                      required={opt.required}
                      value={typeof val === 'string' ? val : ''}
                      onChange={(e) =>
                        setSelection((prev) => ({
                          ...prev,
                          selectedChoices: { ...prev.selectedChoices, [opt.id]: e.target.value },
                        }))
                      }
                    />
                  );
                })}
              </div>
            )}
          </SketchCard>

          <RoughBox seedKey={`summary-${product.id}`} strokeColor="#2B2230" className="bg-[#FFFDF9]" paddingClass="p-6">
            <div className="flex flex-wrap items-center justify-between gap-4">
              <div>
                <p className="text-xs font-semibold text-[var(--pn-ink-soft)]">Selected Plan Rate</p>
                <p className="font-display text-2xl font-bold text-[var(--pn-blossom-700)] tabular-nums">
                  {activePlan ? `${activePlan.formattedPrice} (${activePlan.billingCycleLabel})` : 'Not available'}
                </p>
                {activePlan?.formattedSetupFee && (
                  <p className="text-xs text-[var(--pn-ink-soft)]">
                    Setup fee: {activePlan.formattedSetupFee}
                  </p>
                )}
              </div>
              {product.allowQuantity && (
                <div className="flex items-center gap-2">
                  <span className="text-xs font-semibold text-[var(--pn-ink-soft)]">Qty:</span>
                  <button
                    type="button"
                    aria-label="Decrease quantity"
                    onClick={() => setQuantity((q) => Math.max(1, q - 1))}
                    className="h-9 w-9 rounded border border-[var(--pn-paper-line)] bg-[var(--pn-paper)] font-bold cursor-pointer"
                  >
                    -
                  </button>
                  <span className="w-6 text-center font-mono text-sm font-bold tabular-nums">{quantity}</span>
                  <button
                    type="button"
                    aria-label="Increase quantity"
                    onClick={() => setQuantity((q) => Math.min(20, q + 1))}
                    className="h-9 w-9 rounded border border-[var(--pn-paper-line)] bg-[var(--pn-paper)] font-bold cursor-pointer"
                  >
                    +
                  </button>
                </div>
              )}
            </div>

            {!validation.valid && (
              <SketchAlert
                tone="danger"
                title="Invalid Configuration"
                description={validation.errors[0]}
                className="mt-4"
              />
            )}

            {actionError && (
              <SketchAlert
                tone="warning"
                title="Backend Connection Needed"
                description={actionError}
                className="mt-4"
              />
            )}

            <div className="mt-5 flex flex-wrap gap-3">
              <SketchButton
                variant="primary"
                size="lg"
                disabled={!validation.valid || !runtime.backendConnected}
                iconLeft={<ShoppingBag size={17} />}
                className="flex-1"
                onClick={handleSubmit}
              >
                Add to Cart
              </SketchButton>
              <SketchButton variant="secondary" size="lg" onClick={onBackToCatalog}>
                Back to Catalog
              </SketchButton>
            </div>
          </RoughBox>
        </div>
      </section>

      {product.faqs.length > 0 && (
        <SectionBand title="Product Questions" kicker="02. Details & Limits">
          <SketchAccordion items={product.faqs} />
        </SectionBand>
      )}
    </div>
  );
};
