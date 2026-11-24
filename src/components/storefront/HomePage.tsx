import React, { useState } from 'react';
import { ArrowRight, CheckCircle2, Layers, Lock, ShieldCheck } from 'lucide-react';
import {
  getVisibleSortedCategories,
  getVisibleSortedProducts,
} from '../../theme/paymenter/adapters';
import type { PaymenterProduct } from '../../theme/paymenter/contracts';
import { usePaymenter } from '../../theme/paymenter/PaymenterProvider';
import { useSketchReveal } from '../../theme/animations/hooks';
import {
  Annotation,
  HandIcon,
  NodeyMascot,
  PolaroidFrame,
  RoughBox,
  RoughUnderline,
  SproutMascot,
  Stamp,
  WashiTape,
} from '../../theme/sketch';
import {
  EmptyState,
  SectionBand,
  SketchAlert,
  SketchButton,
  SketchCard,
  SketchTable,
  StatusIndicator,
  type TableColumn,
} from '../../theme/ui';
import { FloatingPetalsOverlay, SketchAccordion, SketchIllustration } from './SketchIllustration';

export interface HomePageProps {
  onOpenCatalog: (categorySlug?: string) => void;
  onSelectProduct: (product: PaymenterProduct) => void;
  onOpenThemeLab: () => void;
}

const THEME_PRESENTATION_FAQS = [
  {
    id: 'arch-1',
    question: 'Why are no products or prices displayed in this workspace?',
    answer:
      'Petalnode is strictly a presentation theme for Paymenter. Because no Paymenter Laravel repository is mounted here yet, all fake/demo products, prices, and coupons have been excluded. Live catalog data renders automatically once integrated with Paymenter.',
  },
  {
    id: 'arch-2',
    question: 'Does this theme replace Paymenter billing, tax, or coupon logic?',
    answer:
      'Never. Paymenter remains the sole authority for products, billing cycles, configurable options, coupon codes, taxes, customer accounts, orders, invoices, and payment gateways.',
  },
  {
    id: 'arch-3',
    question: 'What happens when an admin adds or edits a product in Paymenter?',
    answer:
      'Catalog cards, visibility, ordering, stock badges, and configurator choices bind directly to Paymenter data so admin updates appear automatically without duplicating any product database.',
  },
  {
    id: 'arch-4',
    question: 'What is needed to complete production integration?',
    answer:
      'Access to the target Paymenter repository so presentation templates and adapters bind cleanly to verified routes, templates, CSRF tokens, and server data contracts.',
  },
];

export const HomePage: React.FC<HomePageProps> = ({
  onOpenCatalog,
  onSelectProduct,
  onOpenThemeLab,
}) => {
  const { runtime } = usePaymenter();
  const revealRef = useSketchReveal();
  const [nodeyMood, setNodeyMood] = useState<'happy' | 'working' | 'idle'>('happy');
  const [sproutStage, setSproutStage] = useState<'blooming' | 'growing' | 'seedling'>('blooming');

  const categories = getVisibleSortedCategories(runtime);
  const products = getVisibleSortedProducts(runtime);
  const featuredProducts = products.filter((p) => p.featured).slice(0, 3);
  const displayProducts = featuredProducts.length > 0 ? featuredProducts : products.slice(0, 3);

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
          {p.specs.map((s) => `${s.label}: ${s.value}`).join(' · ') || 'Configured in Paymenter'}
        </span>
      ),
    },
    {
      key: 'stock',
      header: 'Availability',
      render: (p) => <StatusIndicator status={p.inStock ? 'active' : 'suspended'} />,
    },
    {
      key: 'price',
      header: 'Starting Plan',
      align: 'right',
      render: (p) => {
        const firstPlan = p.plans.find((pl) => pl.available) ?? p.plans[0];
        return firstPlan ? (
          <button
            type="button"
            onClick={() => onSelectProduct(p)}
            className="font-display font-bold text-[var(--pn-blossom-700)] hover:underline cursor-pointer"
          >
            {firstPlan.formattedPrice} ({firstPlan.label})
          </button>
        ) : (
          <span className="text-xs text-[var(--pn-ink-soft)]">Unavailable</span>
        );
      },
    },
  ];

  return (
    <div ref={revealRef} className="space-y-14">
      {/* 2, 3, 4. Animated Sketchbook Hero + Mascots + Polaroid Artwork */}
      <section className="relative grid items-stretch gap-10 pt-2 lg:grid-cols-12">
        <div className="relative flex flex-col justify-center lg:col-span-7">
          <p className="font-hand text-base font-semibold text-[var(--pn-bark)]">
            Petalnode Presentation Theme · Paymenter Storefront Layer
          </p>
          <h1 className="mt-2 font-display text-[length:var(--pn-text-hero)] leading-[1.08] font-bold tracking-tight text-[var(--pn-ink)]">
            Cozy game worlds,{' '}
            <span className="whitespace-nowrap">
              <RoughUnderline color="#FF8FAB" seedKey="home-hero-ul">
                sketched
              </RoughUnderline>{' '}
              to last.
            </span>
          </h1>
          <p className="mt-5 max-w-2xl text-base leading-relaxed text-[var(--pn-ink-soft)] sm:text-lg">
            A calm, tactile sketchbook presentation theme built for your existing Paymenter
            billing installation—rendering real catalog products, server pricing, and customer
            records without replacing backend logic.
          </p>

          <div className="mt-7 flex flex-wrap items-center gap-4">
            <SketchButton
              variant="primary"
              size="lg"
              iconRight={<ArrowRight size={17} />}
              onClick={() => onOpenCatalog('all')}
            >
              View Product Catalog
            </SketchButton>
            <SketchButton
              variant="secondary"
              size="lg"
              iconLeft={<Layers size={17} />}
              onClick={onOpenThemeLab}
            >
              Inspect Theme Lab
            </SketchButton>
          </div>

          <div className="mt-6 flex flex-wrap items-center gap-6">
            <div className="flex items-center gap-3">
              <button
                type="button"
                aria-label={`Interact with Nodey mascot (currently ${nodeyMood})`}
                onClick={() =>
                  setNodeyMood((m) => (m === 'happy' ? 'working' : m === 'working' ? 'idle' : 'happy'))
                }
                className="rounded-[var(--pn-radius-md)] p-1 transition-transform duration-150 hover:-translate-y-0.5 active:scale-95 cursor-pointer"
              >
                <NodeyMascot mood={nodeyMood} size={56} />
              </button>
              <button
                type="button"
                aria-label={`Interact with Sprout mascot (currently ${sproutStage})`}
                onClick={() =>
                  setSproutStage((s) =>
                    s === 'blooming' ? 'seedling' : s === 'seedling' ? 'growing' : 'blooming'
                  )
                }
                className="rounded-[var(--pn-radius-md)] p-1 transition-transform duration-150 hover:-translate-y-0.5 active:scale-95 cursor-pointer"
              >
                <SproutMascot stage={sproutStage} size={52} />
              </button>
            </div>
            <Annotation
              note="Zero fake products · Real Paymenter data only"
              arrowDirection="left"
              tone="bark"
            />
          </div>
        </div>

        <div className="relative flex flex-col justify-center lg:col-span-5">
          <FloatingPetalsOverlay />
          <PolaroidFrame
            caption="Petalnode Sketchbook Theme"
            subcaption="Presentation layer awaiting live Paymenter connection"
            tapeTone="blossom"
            tiltDeg={-1.4}
          >
            <div className="relative aspect-[4/3] w-full overflow-hidden">
              <SketchIllustration
                kind="island"
                alt="Hand-drawn floating cherry blossom island cottage"
              />
              <div className="absolute top-3 right-3">
                <Stamp
                  label={runtime.backendConnected ? 'LIVE STORE' : 'THEME PREVIEW'}
                  tone={runtime.backendConnected ? 'moss' : 'bark'}
                  rotateDeg={5}
                />
              </div>
            </div>
          </PolaroidFrame>
        </div>
      </section>

      {!runtime.backendConnected && (
        <SketchAlert
          tone="warning"
          title="Paymenter Backend Repository Required for Live Store Data"
          description={runtime.missingBackendMessage}
        />
      )}

      {/* 5. Dynamic Hosting Categories (Paymenter Category Tree) */}
      <SectionBand
        data-pn-reveal=""
        title="Paymenter Catalog Categories"
        kicker="01. Dynamic Category Slots"
        description="Categories render directly from your Paymenter installation according to backend visibility and ordering rules."
        actions={
          <SketchButton variant="ghost" size="sm" onClick={() => onOpenCatalog('all')}>
            Open catalog view
          </SketchButton>
        }
      >
        {categories.length === 0 ? (
          <EmptyState
            title="No Paymenter categories loaded"
            description="Connect this theme to your Paymenter repository so active product categories populate automatically."
            actionLabel="View Integration Requirements"
            onAction={onOpenThemeLab}
          />
        ) : (
          <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            {categories.map((cat) => (
              <SketchCard key={cat.id} tilt className="flex flex-col justify-between">
                <div>
                  <span className="inline-flex h-11 w-11 items-center justify-center rounded-[var(--pn-radius-md)] border border-[var(--pn-paper-line)] bg-[var(--pn-blossom-50)] text-[var(--pn-blossom-700)]">
                    <HandIcon name="blossom" size={22} />
                  </span>
                  <h3 className="mt-4 font-display text-lg font-bold text-[var(--pn-ink)]">
                    {cat.name}
                  </h3>
                  <p className="mt-1.5 text-sm text-[var(--pn-ink-soft)]">{cat.description}</p>
                </div>
                <div className="mt-5 pt-2">
                  <SketchButton
                    variant="secondary"
                    size="sm"
                    className="w-full"
                    onClick={() => onOpenCatalog(cat.slug)}
                  >
                    Browse Category
                  </SketchButton>
                </div>
              </SketchCard>
            ))}
          </div>
        )}
      </SectionBand>

      {/* 6. Dynamic Products / Plans Section */}
      <SectionBand
        data-pn-reveal=""
        title="Featured Storefront Products"
        kicker="02. Real Paymenter Products"
        description="Product titles, billing cycles, prices, and stock availability come strictly from Paymenter—never hardcoded fixtures."
      >
        {displayProducts.length === 0 ? (
          <EmptyState
            title="No live Paymenter products available"
            description="Once mounted in Paymenter, products configured by your administrator appear here automatically."
            actionLabel="Open Catalog View"
            onAction={() => onOpenCatalog('all')}
          />
        ) : (
          <div className="grid gap-6 lg:grid-cols-3">
            {displayProducts.map((product) => {
              const primaryPlan = product.plans.find((p) => p.available) ?? product.plans[0];
              return (
                <SketchCard key={product.id} tilt className="flex flex-col justify-between">
                  <div>
                    <div className="flex items-center justify-between gap-2">
                      <StatusIndicator status={product.inStock ? 'active' : 'suspended'} />
                      {product.badgeText && (
                        <Stamp label={product.badgeText} tone="blossom" rotateDeg={-2} />
                      )}
                    </div>
                    <h3 className="mt-3 font-display text-2xl font-bold text-[var(--pn-ink)]">
                      {product.name}
                    </h3>
                    <p className="mt-1.5 text-sm text-[var(--pn-ink-soft)]">{product.description}</p>
                    {primaryPlan && (
                      <div className="mt-4 flex items-baseline gap-2">
                        <span className="font-display text-3xl font-bold text-[var(--pn-ink)] tabular-nums">
                          {primaryPlan.formattedPrice}
                        </span>
                        <span className="text-xs font-semibold text-[var(--pn-ink-soft)]">
                          {primaryPlan.billingCycleLabel}
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

                  <div className="mt-6">
                    <SketchButton
                      variant="primary"
                      size="md"
                      disabled={!product.inStock}
                      className="w-full"
                      onClick={() => onSelectProduct(product)}
                    >
                      {product.inStock ? 'Inspect & Configure' : 'Unavailable'}
                    </SketchButton>
                  </div>
                </SketchCard>
              );
            })}
          </div>
        )}
      </SectionBand>

      {/* 7 & 8 & 9. Editorial Hardware Craft & Network Illustration Showcase */}
      <SectionBand
        data-pn-reveal=""
        title="Sketchbook Hardware &amp; Network Presentation"
        kicker="03. Theme Showcase Art"
        description="Hand-illustrated Polaroid frames and blueprint containers ready to display your Paymenter host announcements and node metadata."
      >
        <div className="grid items-stretch gap-8 lg:grid-cols-12">
          <div className="lg:col-span-6">
            <PolaroidFrame
              caption="Workbench Craft Artwork"
              subcaption="Reusable Polaroid illustration container for host specifications"
              tapeTone="lantern"
              tiltDeg={1.0}
            >
              <div className="aspect-[4/3] w-full overflow-hidden">
                <SketchIllustration
                  kind="craft"
                  alt="Hand-drawn craft desk with glowing amber NVMe server cube"
                />
              </div>
            </PolaroidFrame>
          </div>
          <div className="lg:col-span-6">
            <PolaroidFrame
              caption="Network Cartography Artwork"
              subcaption="Reusable globe artwork for real Paymenter node/location pages"
              tapeTone="moss"
              tiltDeg={-1.0}
            >
              <div className="aspect-[4/3] w-full overflow-hidden">
                <SketchIllustration
                  kind="globe"
                  alt="Hand-drawn globe linked by cherry blossom lines to paper lantern nodes"
                />
              </div>
            </PolaroidFrame>
          </div>
        </div>
      </SectionBand>

      {/* 10. Presentation Principles & Backend Integrity */}
      <SectionBand
        data-pn-reveal=""
        title="Strict Presentation-Only Architecture"
        kicker="04. Built For Real Paymenter"
        description="Petalnode refreshes every visual surface while leaving Paymenter's billing, auth, and gateway logic untouched."
      >
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
          {[
            {
              icon: 'receipt' as const,
              title: 'Zero Fake Catalogs',
              body: 'Products, categories, and availability load exclusively from Paymenter admin records.',
            },
            {
              icon: 'shield' as const,
              title: 'Server-Side Billing',
              body: 'Tax, coupons, renewals, and totals resolve solely through Paymenter backend logic.',
            },
            {
              icon: 'compass' as const,
              title: 'Authentic Checkout',
              body: 'Preserves customer accounts, CSRF tokens, gateways, and invoice verification.',
            },
            {
              icon: 'quill' as const,
              title: 'Warm Sketchbook Craft',
              body: 'Accessible Paper, Ink, Blossom, and Moss tokens paired with Rough.js and GSAP.',
            },
          ].map((pillar) => (
            <SketchCard key={pillar.title} surface="paper">
              <span className="inline-flex h-10 w-10 items-center justify-center rounded-[var(--pn-radius-sm)] bg-[var(--pn-moss-100)] text-[var(--pn-moss-700)]">
                <HandIcon name={pillar.icon} size={20} />
              </span>
              <h3 className="mt-3 font-display text-base font-bold text-[var(--pn-ink)]">
                {pillar.title}
              </h3>
              <p className="mt-1 text-sm text-[var(--pn-ink-soft)]">{pillar.body}</p>
            </SketchCard>
          ))}
        </div>
      </SectionBand>

      {/* 11. Plan Comparison Slot */}
      {displayProducts.length > 0 && (
        <SectionBand
          data-pn-reveal=""
          title="Compare Paymenter Products"
          kicker="05. Dynamic Comparison"
          description="Rendered directly from visible Paymenter catalog items."
        >
          <SketchTable columns={compareCols} rows={displayProducts} />
        </SectionBand>
      )}

      {/* 12. Paymenter Announcements / CMS Slot */}
      <SectionBand
        data-pn-reveal=""
        title="Storefront Announcements &amp; Host Notes"
        kicker="06. Paymenter CMS Slot"
        description="Displays published Paymenter announcements or host notices without inventing fake customer testimonials."
      >
        {runtime.announcements.length === 0 ? (
          <SketchCard surface="shade">
            <div className="flex flex-wrap items-center justify-between gap-4">
              <div className="flex items-start gap-3">
                <WashiTape tone="bark" rotation={-2} label="CMS Slot" />
                <div>
                  <p className="font-display text-base font-bold text-[var(--pn-ink)]">
                    No Paymenter announcements published yet
                  </p>
                  <p className="mt-1 text-sm text-[var(--pn-ink-soft)]">
                    Published news or community notes from Paymenter will appear here automatically.
                  </p>
                </div>
              </div>
              <span className="inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--pn-ink-soft)]">
                <Lock size={14} aria-hidden="true" />
                <span>Real CMS data only</span>
              </span>
            </div>
          </SketchCard>
        ) : (
          <div className="grid gap-6 md:grid-cols-3">
            {runtime.announcements.map((post) => (
              <SketchCard key={post.id}>
                <p className="font-mono text-xs text-[var(--pn-ink-soft)]">{post.publishedAt}</p>
                <h3 className="mt-2 font-display text-lg font-bold text-[var(--pn-ink)]">
                  {post.title}
                </h3>
                <p className="mt-1.5 text-sm text-[var(--pn-ink-soft)]">{post.excerpt}</p>
              </SketchCard>
            ))}
          </div>
        )}
      </SectionBand>

      {/* 13. Architecture & Integration FAQ */}
      <SectionBand
        data-pn-reveal=""
        title="Theme &amp; Integration Questions"
        kicker="07. Honest Architecture"
        description="Clear answers on Paymenter data boundaries and missing repository requirements."
      >
        <SketchAccordion items={THEME_PRESENTATION_FAQS} />
      </SectionBand>

      {/* 14. Final CTA Band */}
      <section data-pn-reveal="">
        <RoughBox seedKey="home-cta" strokeColor="#2B2230" className="bg-[var(--pn-blossom-50)]" paddingClass="p-8 sm:p-10">
          <div className="grid items-center gap-8 lg:grid-cols-12">
            <div className="lg:col-span-8">
              <div className="flex items-center gap-2 text-xs font-semibold text-[var(--pn-bark)]">
                <ShieldCheck size={16} aria-hidden="true" />
                <span>Presentation Layer Ready · Awaiting Paymenter Repository</span>
              </div>
              <h2 className="mt-2 font-display text-2xl font-bold text-[var(--pn-ink)] sm:text-3xl">
                Connect the Paymenter Laravel repository to bind live products and checkout.
              </h2>
              <p className="mt-2 max-w-2xl text-sm text-[var(--pn-ink-soft)]">
                Inspect the theme tokens, Sketchbook components, and updated 336-item feature
                registry inside Theme Lab, or review missing backend dependencies.
              </p>
              <div className="mt-6 flex flex-wrap gap-3">
                <SketchButton variant="primary" size="lg" onClick={onOpenThemeLab}>
                  Open Theme Lab &amp; Registry
                </SketchButton>
                <SketchButton variant="secondary" size="lg" onClick={() => onOpenCatalog('all')}>
                  Inspect Empty Catalog State
                </SketchButton>
              </div>
            </div>
            <div className="flex items-center justify-center gap-4 lg:col-span-4">
              <NodeyMascot mood="happy" size={88} />
              <SproutMascot stage="blooming" size={80} />
            </div>
          </div>
        </RoughBox>
      </section>
    </div>
  );
};
