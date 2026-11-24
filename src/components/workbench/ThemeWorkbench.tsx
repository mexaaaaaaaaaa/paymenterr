import React, { useMemo, useState } from 'react';
import { FEATURE_REGISTRY, summarizeFeatureRegistry } from '../../data/featureRegistry';
import {
  MISSING_PAYMENTER_DEPENDENCIES,
  PETALNODE_THEME_MANIFEST,
} from '../../theme/paymenter/compatibility';
import {
  SegmentedFilter,
  SketchAlert,
  SketchButton,
  SketchDrawer,
} from '../../theme/ui';
import { OverviewSection } from './OverviewSection';
import { TokensSection } from './TokensSection';
import { SketchLabSection } from './SketchLabSection';
import { UIKitSection } from './UIKitSection';
import { FeatureRegistrySection } from './FeatureRegistrySection';

export interface ThemeWorkbenchProps {
  paperMode: 'plain' | 'grid' | 'notebook';
  onPaperModeChange: (mode: 'plain' | 'grid' | 'notebook') => void;
}

export const ThemeWorkbench: React.FC<ThemeWorkbenchProps> = ({
  paperMode,
  onPaperModeChange,
}) => {
  const [subTab, setSubTab] = useState<'overview' | 'tokens' | 'sketch-lab' | 'ui-kit' | 'registry'>('overview');
  const [auditDrawerOpen, setAuditDrawerOpen] = useState(false);

  const summary = useMemo(() => summarizeFeatureRegistry(FEATURE_REGISTRY), []);

  return (
    <div className="space-y-8">
      <div className="flex flex-wrap items-center justify-between gap-4 border-b border-[var(--pn-paper-line)] pb-4">
        <div>
          <p className="font-hand text-xs font-semibold text-[var(--pn-bark)]">
            Presentation Theme Architecture
          </p>
          <h2 className="font-display text-xl font-bold text-[var(--pn-ink)]">
            Petalnode Theme Workbench &amp; Registry
          </h2>
        </div>
        <SegmentedFilter
          ariaLabel="Theme workbench sections"
          activeId={subTab}
          onChange={(id) => setSubTab(id as typeof subTab)}
          options={[
            { id: 'overview', label: 'Overview' },
            { id: 'tokens', label: 'Tokens' },
            { id: 'sketch-lab', label: 'Sketch Lab' },
            { id: 'ui-kit', label: 'UI Kit' },
            { id: 'registry', label: `${summary.total} Registry` },
          ]}
        />
      </div>

      {subTab === 'overview' && (
        <OverviewSection
          totalFeatures={summary.total}
          themeUiCount={summary.byClassification['Theme UI implemented']}
          pendingIntegrationCount={summary.byClassification['Paymenter integration pending']}
          onNavigateTab={(id) => setSubTab(id as typeof subTab)}
          onOpenAuditDrawer={() => setAuditDrawerOpen(true)}
        />
      )}
      {subTab === 'tokens' && <TokensSection />}
      {subTab === 'sketch-lab' && (
        <SketchLabSection paperMode={paperMode} onPaperModeChange={onPaperModeChange} />
      )}
      {subTab === 'ui-kit' && <UIKitSection />}
      {subTab === 'registry' && <FeatureRegistrySection />}

      <SketchDrawer
        open={auditDrawerOpen}
        onClose={() => setAuditDrawerOpen(false)}
        title="Paymenter Integration Boundaries"
        footer={
          <SketchButton
            variant="primary"
            className="w-full"
            onClick={() => setAuditDrawerOpen(false)}
          >
            Close Audit Summary
          </SketchButton>
        }
      >
        <div className="space-y-4 text-sm">
          <SketchAlert
            tone="warning"
            title="Awaiting Real Paymenter Repository"
            description="No Paymenter backend source files are mounted in this workspace. All store/customer behaviors remain cleanly isolated behind PaymenterProvider."
          />
          <div>
            <p className="font-display font-bold text-[var(--pn-ink)]">Theme Manifest</p>
            <ul className="mt-2 space-y-1.5 text-xs text-[var(--pn-ink-soft)]">
              <li>Name: {PETALNODE_THEME_MANIFEST.name}</li>
              <li>Slug: {PETALNODE_THEME_MANIFEST.slug}</li>
              <li>Target: {PETALNODE_THEME_MANIFEST.targetPlatform}</li>
              <li>Status: {PETALNODE_THEME_MANIFEST.repositoryStatus}</li>
            </ul>
          </div>
          <div>
            <p className="font-display font-bold text-[var(--pn-ink)]">
              Missing Backend Dependencies
            </p>
            <ul className="mt-2 space-y-2 text-xs text-[var(--pn-ink-soft)]">
              {MISSING_PAYMENTER_DEPENDENCIES.map((dep) => (
                <li key={dep.area} className="rounded bg-[var(--pn-paper-shade)]/60 p-2.5">
                  <p className="font-semibold text-[var(--pn-ink)]">{dep.area}</p>
                  <p className="mt-0.5">{dep.reason}</p>
                </li>
              ))}
            </ul>
          </div>
        </div>
      </SketchDrawer>
    </div>
  );
};
