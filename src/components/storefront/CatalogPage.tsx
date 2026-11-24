import React, { useMemo, useState } from 'react';
import { ArrowRight, CheckCircle2, Scale, SlidersHorizontal } from 'lucide-react';
import {
  getVisibleSortedCategories,
  getVisibleSortedProducts,
} from '../../theme/paymenter/adapters';
import type { PaymenterProduct } from '../../theme/paymenter/contracts';
import { usePaymenter } from '../../theme/paymenter/PaymenterProvider';
import { Stamp } from '../../theme/sketch';
import {
  EmptyState,
  Pagination,
  SearchInput,
  SectionBand,
  SegmentedFilter,
  Skeleton,
  SketchAlert,
  SketchButton,
  SketchCard,
  SketchSelect,
  SketchTable,
  StatusIndicator,
  type TableColumn,
} from '../../theme/ui';
import { SketchIllustration } from './SketchIllustration';

export interface CatalogPageProps {
  initialCategorySlug?: string;
  onSelectProduct: (product: PaymenterProduct) => void;
  onOpenThemeLab: () => void;
}

const PAGE_SIZE = 6;

export const CatalogPage: React.FC<CatalogPageProps> = ({
  initialCategorySlug = 'all',
  onSelectProduct,
  onOpenThemeLab,
}) => {
  const { runtime } = usePaymenter();
  const [categorySlug, setCategorySlug] = useState<string>(initialCategorySlug);
  const [query, setQuery] = useState('');
  const [sortBy, setSortBy] = useState<'backend-order' | 'price-asc' | 'price-desc'>('backend-order');
  const [page, setPage] = useState(1);
  const [compareIds, setCompareIds] = useState<string[]>([]);
  const [showSkeletonPreview, setShowSkeletonPreview] = useState(false);

  const visibleCategories = useMemo(() => getVisibleSortedCategories(runtime), [runtime]);
  const visibleProducts = useMemo(() => getVisibleSortedProducts(runtime), [runtime]);

  const getPrimaryPlan = (product: PaymenterProduct) =>
    product.plans.find((p) => p.available) ?? product.plans[0];

  const filtered = useMemo(() => {
    const q = query.trim().toLowerCase();
    const list = visibleProducts.filter((p) => {
      if (categorySlug !== 'all' && p.categorySlug !== categorySlug) return false;
      if (!q) return true;
      return (
        p.name.toLowerCase().includes(q) ||
        p.description.toLowerCase().includes(q) ||
        p.specs.some((s) => s.value.toLowerCase().includes(q))
      );
    });

    if (sortBy === 'backend-order') return list;

    return [...list].sort((a, b) => {
      const pa = getPrimaryPlan(a)?.numericPrice ?? 0;
      const pb = getPrimaryPlan(b)?.numericPrice ?? 0;
      return sortBy === 'price-asc' ? pa - pb : pb - pa;
    });
  }, [visibleProducts, categorySlug, query, sortBy]);

  const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
  const paged = useMemo(() => {
    const start = (page - 1) * PAGE_SIZE;
    return filtered.slice(start, start + PAGE_SIZE);
  }, [filtered, page]);

  const comparedProducts = useMemo(
    () => visibleProducts.filter((p) => compareIds.includes(p.id)),
    [visibleProducts, compareIds]
  );

  const toggleCompare = (id: string) => {
    setCompareIds((prev) =>
      prev.includes(id)
        ? prev.filter((item) => item !== id)
        : prev.length >= 3
          ? [...prev.slice(1), id]
          : [...prev, id]
    );
  };

  const compareCols: TableColumn<PaymenterProduct>[] = [
    {
      key: 'name',
      header: 'Product',
      render: (p) => <span className="font-display font-bold text-[var(--pn-ink)]">{p.name}</span>,
    },
    {
      key: 'specs',
      header: 'Specifications',
      render: (p) => (
        <span className="text-xs text-[var(--pn-ink-soft)]">
          {p.specs.map((s) => `${s.label}: ${s.value}`).join(' · ') || '—'}
        </span>
      ),
    },
    {
      key: 'price',
      header: 'Backend Rate',
      align: 'right',
      render: (p) => {
        const plan = getPrimaryPlan(p);
        return (
          <span className="font-display font-bold text-[var(--pn-blossom-700)] tabular-nums">
            {plan ? `${plan.formattedPrice} (${plan.label})` : 'N/A'}
          </span>
        );
      },
    },
  ];

  return (
    <div className="space-y-10">
      <SectionBand
        title="Paymenter Product Catalog"
        kicker="01. Presentation Catalog View"
        description="Filters, sorts, and renders products directly from Paymenter backend visibility, stock, and ordering rules."
        actions={
          <SketchButton
            variant="ghost"
            size="sm"
            onClick={() => setShowSkeletonPreview((v) => !v)}
          >
            {showSkeletonPreview ? 'Hide Skeleton Preview' : 'Preview Loading State'}
          </SketchButton>
        }
      >
        {!runtime.backendConnected && (
          <SketchAlert
            tone="warning"
            title="Paymenter Backend Not Connected"
            description={runtime.missingBackendMessage}
            className="mb-6"
          />
        )}

        {/* Controls Bar */}
        <div className="space-y-4 rounded-[var(--pn-radius-lg)] border border-[var(--pn-paper-line)] bg-[#FFFDF9] p-4 shadow-[var(--pn-shadow-paper-sm)]">
          <div className="grid items-end gap-4 md:grid-cols-2">
            <SearchInput
              value={query}
              onChange={(val) => {
                setQuery(val);
                setPage(1);
              }}
              placeholder="Search live Paymenter products..."
              label="Search catalog"
              visibleLabel
            />
            <SketchSelect
              label="Sort Order"
              value={sortBy}
              onChange={(e) => setSortBy(e.target.value as typeof sortBy)}
              options={[
                { value: 'backend-order', label: 'Paymenter Default Order' },
                { value: 'price-asc', label: 'Price: Low to High' },
                { value: 'price-desc', label: 'Price: High to Low' },
              ]}
            />
          </div>

          <div className="flex flex-wrap items-center justify-between gap-3">
            <SegmentedFilter
              ariaLabel="Filter by Paymenter category"
              activeId={categorySlug}
              onChange={(id) => {
                setCategorySlug(id);
                setPage(1);
              }}
              options={[
                { id: 'all', label: 'All Categories' },
                ...visibleCategories.map((c) => ({
                  id: c.slug,
                  label: c.name,
                })),
              ]}
            />
            <p className="text-xs font-semibold text-[var(--pn-ink-soft)] tabular-nums">
              {filtered.length} product{filtered.length === 1 ? '' : 's'} loaded
            </p>
          </div>
        </div>

        {/* Loading Skeleton Preview or Real Product Grid / Honest Empty State */}
        {showSkeletonPreview ? (
          <div className="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            {[1, 2, 3].map((n) => (
              <SketchCard key={n}>
                <Skeleton className="h-36 w-full" />
                <Skeleton className="mt-4 h-6 w-2/3" />
                <Skeleton className="mt-2 h-4 w-full" />
                <Skeleton className="mt-5 h-10 w-full" />
              </SketchCard>
            ))}
          </div>
        ) : paged.length === 0 ? (
          <div className="mt-6">
            <EmptyState
              title={
                runtime.backendConnected
                  ? 'No matching Paymenter products'
                  : 'Waiting for Paymenter Catalog Data'
              }
              description={
                runtime.backendConnected
                  ? 'Try clearing your search filter or switching categories.'
                  : 'No fake or fallback products are rendered. Connect the Paymenter Laravel repository to load your store catalog.'
              }
              actionLabel={runtime.backendConnected ? 'Reset Filters' : 'Inspect Integration Spec'}
              onAction={() => {
                if (runtime.backendConnected) {
                  setCategorySlug('all');
                  setQuery('');
                  setPage(1);
                } else {
                  onOpenThemeLab();
                }
              }}
            />
          </div>
        ) : (
          <>
            <div className="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
              {paged.map((product) => {
                const plan = getPrimaryPlan(product);
                const isCompared = compareIds.includes(product.id);
                return (
                  <SketchCard key={product.id} tilt className="flex flex-col justify-between p-0 overflow-hidden">
                    <div>
                      <div className="relative aspect-[4/3] w-full overflow-hidden border-b border-[var(--pn-paper-line)] bg-[var(--pn-paper-shade)]/40">
                        <SketchIllustration
                          kind="island"
                          src={product.imageUrl}
                          alt={product.name}
                        />
                        {product.badgeText && (
                          <div className="absolute top-3 right-3">
                            <Stamp
                              label={product.badgeText}
                              tone={product.inStock ? 'blossom' : 'bark'}
                              rotateDeg={3}
                            />
                          </div>
                        )}
                      </div>

                      <div className="p-5 pb-0">
                        <div className="flex items-center justify-between gap-2">
                          <span className="font-mono text-xs text-[var(--pn-ink-soft)]">
                            {product.categorySlug}
                          </span>
                          <StatusIndicator status={product.inStock ? 'active' : 'suspended'} />
                        </div>

                        <h3 className="mt-2 font-display text-xl font-bold text-[var(--pn-ink)]">
                          {product.name}
                        </h3>
                        <p className="mt-1 text-sm text-[var(--pn-ink-soft)]">{product.description}</p>

                        {plan && (
                          <div className="mt-4 flex items-baseline gap-1.5">
                            <span className="font-display text-2xl font-bold text-[var(--pn-ink)] tabular-nums">
                              {plan.formattedPrice}
                            </span>
                            <span className="text-xs font-semibold text-[var(--pn-ink-soft)]">
                              {plan.billingCycleLabel}
                            </span>
                          </div>
                        )}

                        {product.specs.length > 0 && (
                          <ul className="mt-4 space-y-1.5 border-t border-[var(--pn-paper-line)] pt-3 text-xs text-[var(--pn-ink)]">
                            {product.specs.map((spec) => (
                              <li key={spec.label} className="flex items-center gap-2">
                                <CheckCircle2 size={14} className="text-[var(--pn-moss-700)] shrink-0" />
                                <span>
                                  {spec.label}: {spec.value}
                                </span>
                              </li>
                            ))}
                          </ul>
                        )}
                      </div>
                    </div>

                    <div className="mt-5 flex flex-col gap-2 p-5 pt-3">
                      <div className="flex gap-2">
                        <SketchButton
                          variant={product.inStock ? 'primary' : 'secondary'}
                          size="sm"
                          disabled={!product.inStock}
                          className="flex-1"
                          iconLeft={<SlidersHorizontal size={14} />}
                          onClick={() => onSelectProduct(product)}
                        >
                          {product.inStock ? 'Configure' : 'Unavailable'}
                        </SketchButton>
                        <SketchButton
                          variant="secondary"
                          size="sm"
                          iconRight={<ArrowRight size={14} />}
                          onClick={() => onSelectProduct(product)}
                        >
                          Details
                        </SketchButton>
                      </div>
                      <button
                        type="button"
                        onClick={() => toggleCompare(product.id)}
                        className="inline-flex h-8 items-center justify-center gap-1.5 rounded-[var(--pn-radius-sm)] text-xs font-semibold text-[var(--pn-ink-soft)] hover:bg-[var(--pn-paper-shade)] hover:text-[var(--pn-ink)] cursor-pointer"
                      >
                        <Scale size={13} aria-hidden="true" />
                        <span>{isCompared ? 'Remove from comparison' : 'Compare product'}</span>
                      </button>
                    </div>
                  </SketchCard>
                );
              })}
            </div>
            <Pagination page={page} totalPages={totalPages} onPageChange={setPage} />
          </>
        )}
      </SectionBand>

      {comparedProducts.length > 0 && (
        <SectionBand
          title="Product Comparison"
          kicker="02. Side-by-Side Specs"
          description="Compares real specifications and backend rates across selected products."
        >
          <SketchTable columns={compareCols} rows={comparedProducts} />
        </SectionBand>
      )}
    </div>
  );
};
