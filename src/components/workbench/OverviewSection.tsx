import React from 'react';
import { AlertCircle, ArrowRight, CheckCircle2, Layers } from 'lucide-react';
import {
  COMPATIBILITY_INVARIANTS,
  MISSING_PAYMENTER_DEPENDENCIES,
  PETALNODE_THEME_MANIFEST,
} from '../../theme/paymenter/compatibility';
import { useCountUp, useSketchReveal } from '../../theme/animations/hooks';
import {
  Annotation,
  HandIcon,
  NodeyMascot,
  PolaroidFrame,
  RoughBox,
  RoughCircle,
  RoughUnderline,
  SproutMascot,
  Stamp,
} from '../../theme/sketch';
import { SectionBand, SketchAlert, SketchButton, SketchCard } from '../../theme/ui';

export interface OverviewSectionProps {
  totalFeatures: number;
  themeUiCount: number;
  pendingIntegrationCount: number;
  onNavigateTab: (tabId: string) => void;
  onOpenAuditDrawer: () => void;
}

export const OverviewSection: React.FC<OverviewSectionProps> = ({
  totalFeatures,
  themeUiCount,
  pendingIntegrationCount,
  onNavigateTab,
  onOpenAuditDrawer,
}) => {
  const revealRef = useSketchReveal();
  const totalRef = useCountUp(totalFeatures, { duration: 0.7 });
  const implRef = useCountUp(themeUiCount, { duration: 0.7 });

  return (
    <div ref={revealRef} className="space-y-12">
      {/* Hero Sketchbook Split */}
      <section className="grid items-stretch gap-8 lg:grid-cols-12">
        <div className="flex flex-col justify-center lg:col-span-7">
          <p className="font-hand text-base font-semibold text-[var(--pn-bark)]">
            Petalnode Presentation Theme — Real Paymenter Architecture
          </p>
          <h1 className="mt-2 font-display text-[length:var(--pn-text-hero)] leading-[1.1] font-bold tracking-tight text-[var(--pn-ink)]">
            The page is a{' '}
            <span className="whitespace-nowrap">
              <RoughUnderline color="#FF8FAB" seedKey="hero-sketchbook">
                sketchbook
              </RoughUnderline>
              .
            </span>{' '}
            The world grows out of it.
          </h1>
          <p className="mt-5 max-w-2xl text-base leading-relaxed text-[var(--pn-ink-soft)] sm:text-lg">
            A presentation-only theme foundation crafted for an existing Paymenter billing
            installation—combining warm paper surfaces, Rough.js ink geometry, GSAP motion
            curves, and clean runtime adapter boundaries with zero fabricated commerce data.
          </p>

          <div className="mt-7 flex flex-wrap items-center gap-4">
            <SketchButton
              variant="primary"
              size="lg"
              iconRight={<ArrowRight size={17} />}
              onClick={() => onNavigateTab('sketch-lab')}
            >
              Explore Sketch Lab
            </SketchButton>
            <SketchButton
              variant="secondary"
              size="lg"
              iconLeft={<Layers size={17} />}
              onClick={onOpenAuditDrawer}
            >
              Inspect Integration Status
            </SketchButton>
          </div>

          <div className="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3">
            <div className="rounded-[var(--pn-radius-md)] border border-[var(--pn-paper-line)] bg-[#FFFDF9] p-4">
              <p className="text-xs font-semibold text-[var(--pn-ink-soft)]">Registry Target</p>
              <p className="mt-1 font-display text-2xl font-bold text-[var(--pn-ink)] tabular-nums">
                <span ref={totalRef}>{totalFeatures}</span>
              </p>
            </div>
            <div className="rounded-[var(--pn-radius-md)] border border-[var(--pn-paper-line)] bg-[#FFFDF9] p-4">
              <p className="text-xs font-semibold text-[var(--pn-ink-soft)]">Theme UI Built</p>
              <p className="mt-1 font-display text-2xl font-bold text-[var(--pn-moss-700)] tabular-nums">
                <span ref={implRef}>{themeUiCount}</span>
                <span className="ml-1 text-sm font-semibold text-[var(--pn-ink-soft)]">
                  · {pendingIntegrationCount} pending backend
                </span>
              </p>
            </div>
            <div className="col-span-2 rounded-[var(--pn-radius-md)] border border-[var(--pn-paper-line)] bg-[#FFFDF9] p-4 sm:col-span-1">
              <p className="text-xs font-semibold text-[var(--pn-ink-soft)]">Backend Status</p>
              <p className="mt-1 font-display text-lg font-bold text-[var(--pn-ink)]">
                Paymenter <RoughCircle color="#E2557B" seedKey="repo-ring">Required</RoughCircle>
              </p>
            </div>
          </div>
        </div>

        {/* Right Sketchbook Polaroid Canvas */}
        <div className="flex flex-col justify-center lg:col-span-5">
          <PolaroidFrame
            caption="Nodey & Sprout — cozy voxel companions"
            subcaption="100% original inline SVGs · Zero copyrighted assets"
            tapeTone="blossom"
            tiltDeg={-1.2}
          >
            <div className="pn-sketch-grid relative flex min-h-[260px] flex-col items-center justify-center gap-4 p-6">
              <div className="absolute top-3 right-3">
                <Stamp label="THEME UI" tone="moss" rotateDeg={5} />
              </div>
              <div className="flex items-end justify-center gap-6 pt-4">
                <NodeyMascot mood="happy" size={120} />
                <SproutMascot stage="blooming" size={104} />
              </div>
              <Annotation
                note="Presentation only · Zero fake data"
                arrowDirection="right"
                tone="bark"
              />
            </div>
          </PolaroidFrame>
        </div>
      </section>

      {/* Project Audit Findings */}
      <SectionBand
        title="Workspace Audit & Paymenter Integration Boundaries"
        kicker="01. Honest Architecture Report"
        description="Strict separation between verified frontend presentation components and missing Paymenter backend source dependencies."
      >
        <div className="grid gap-6 lg:grid-cols-2">
          <RoughBox
            seedKey="audit-findings"
            strokeColor="#2B2230"
            className="bg-[#FFFDF9]"
            paddingClass="p-6"
          >
            <div className="flex items-center gap-2.5">
              <HandIcon name="compass" size={20} className="text-[var(--pn-blossom-700)]" />
              <h3 className="font-display text-lg font-bold text-[var(--pn-ink)]">
                Workspace Audit Findings
              </h3>
            </div>
            <p className="mt-2 text-sm text-[var(--pn-ink-soft)]">
              This repository is a React 19 + Vite 8 + Tailwind CSS v4 + TypeScript theme
              workspace. No Paymenter repository is mounted yet, so the storefront strictly renders
              from injected runtime data (defaulting to empty states) without fabricated products,
              prices, coupons, gateways, or invented server endpoints.
            </p>
            <dl className="mt-4 grid grid-cols-2 gap-3 border-t border-[var(--pn-paper-line)] pt-4 text-xs">
              <div>
                <dt className="text-[var(--pn-ink-soft)]">Theme Slug</dt>
                <dd className="mt-0.5 font-mono font-semibold text-[var(--pn-ink)]">
                  {PETALNODE_THEME_MANIFEST.slug}
                </dd>
              </div>
              <div>
                <dt className="text-[var(--pn-ink-soft)]">Repository Status</dt>
                <dd className="mt-0.5 font-mono font-semibold text-[var(--pn-blossom-700)]">
                  {PETALNODE_THEME_MANIFEST.repositoryStatus}
                </dd>
              </div>
              <div>
                <dt className="text-[var(--pn-ink-soft)]">Target Platform</dt>
                <dd className="mt-0.5 font-semibold text-[var(--pn-ink)]">
                  {PETALNODE_THEME_MANIFEST.targetPlatform}
                </dd>
              </div>
              <div>
                <dt className="text-[var(--pn-ink-soft)]">Dependencies Added</dt>
                <dd className="mt-0.5 font-mono font-semibold text-[var(--pn-ink)]">
                  gsap, roughjs
                </dd>
              </div>
            </dl>
          </RoughBox>

          <SketchCard surface="shade">
            <div className="flex items-center gap-2.5">
              <HandIcon name="shield" size={20} className="text-[var(--pn-moss-700)]" />
              <h3 className="font-display text-lg font-bold text-[var(--pn-ink)]">
                Non-Negotiable Architecture Rules
              </h3>
            </div>
            <ul className="mt-3 space-y-2.5 text-sm text-[var(--pn-ink)]">
              {COMPATIBILITY_INVARIANTS.map((rule, idx) => (
                <li key={idx} className="flex items-start gap-2.5">
                  <CheckCircle2
                    size={16}
                    aria-hidden="true"
                    className="mt-0.5 shrink-0 text-[var(--pn-moss-700)]"
                  />
                  <span>{rule}</span>
                </li>
              ))}
            </ul>
          </SketchCard>
        </div>

        {/* Missing Paymenter Dependencies */}
        <div className="mt-6 grid gap-6 lg:grid-cols-3">
          <div className="lg:col-span-2">
            <SketchCard>
              <h3 className="font-display text-base font-bold text-[var(--pn-ink)]">
                Missing Paymenter Repository Dependencies ({MISSING_PAYMENTER_DEPENDENCIES.length})
              </h3>
              <div className="mt-3 grid gap-3 sm:grid-cols-2">
                {MISSING_PAYMENTER_DEPENDENCIES.map((dep) => (
                  <div
                    key={dep.area}
                    className="rounded-[var(--pn-radius-sm)] border border-[var(--pn-paper-line)] bg-[var(--pn-paper)]/65 p-3"
                  >
                    <div className="flex items-center justify-between gap-2">
                      <span className="font-semibold text-xs text-[var(--pn-ink)]">{dep.area}</span>
                      <AlertCircle size={14} className="shrink-0 text-[var(--pn-blossom-700)]" />
                    </div>
                    <p className="mt-1 font-mono text-[11px] font-semibold text-[var(--pn-blossom-700)]">
                      {dep.status}
                    </p>
                    <p className="mt-1 text-xs text-[var(--pn-ink-soft)]">{dep.reason}</p>
                  </div>
                ))}
              </div>
            </SketchCard>
          </div>

          <div className="space-y-4">
            <SketchAlert
              tone="warning"
              title="Awaiting Real Paymenter Repo"
              description="Customer portal routes (Prompt 03) and live catalog/billing bindings require inspecting the actual Paymenter source repository first."
            />
          </div>
        </div>
      </SectionBand>
    </div>
  );
};
