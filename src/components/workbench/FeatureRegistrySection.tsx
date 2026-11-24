import React, { useMemo, useState } from 'react';
import {
  FEATURE_CATEGORIES,
  FEATURE_REGISTRY,
  filterFeatures,
  summarizeFeatureRegistry,
  type FeatureCategory,
  type FeatureClassification,
  type FeaturePriority,
  type ThemeFeature,
} from '../../data/featureRegistry';
import {
  InlineBadge,
  Pagination,
  SearchInput,
  SectionBand,
  SegmentedFilter,
  SketchCard,
  SketchSelect,
  SketchTable,
  type TableColumn,
} from '../../theme/ui';

const PAGE_SIZE = 15;

const CLASSIFICATION_STYLE: Record<FeatureClassification, string> = {
  'Theme UI implemented': 'text-[var(--pn-moss-700)]',
  'Paymenter integration pending': 'text-[var(--pn-blossom-700)]',
  'Backend verified': 'text-[var(--pn-bark)]',
  'Production tested': 'text-[var(--pn-ink)]',
};

export const FeatureRegistrySection: React.FC = () => {
  const [query, setQuery] = useState('');
  const [category, setCategory] = useState<FeatureCategory | 'ALL'>('ALL');
  const [classification, setClassification] = useState<FeatureClassification | 'ALL'>('ALL');
  const [priority, setPriority] = useState<FeaturePriority | 'ALL'>('ALL');
  const [page, setPage] = useState(1);

  const summary = useMemo(() => summarizeFeatureRegistry(FEATURE_REGISTRY), []);

  const filtered = useMemo(() => {
    return filterFeatures(FEATURE_REGISTRY, {
      query,
      category,
      classification,
      priority,
    });
  }, [query, category, classification, priority]);

  const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
  const pagedRows = useMemo(() => {
    const start = (page - 1) * PAGE_SIZE;
    return filtered.slice(start, start + PAGE_SIZE);
  }, [filtered, page]);

  const columns: TableColumn<ThemeFeature>[] = [
    {
      key: 'id',
      header: 'ID',
      render: (f) => <InlineBadge label={f.id} emphasis="accent" />,
    },
    {
      key: 'name',
      header: 'Feature',
      render: (f) => (
        <div className="max-w-md">
          <p className="font-semibold text-[var(--pn-ink)]">{f.name}</p>
          <p className="mt-0.5 text-xs text-[var(--pn-ink-soft)]">{f.description}</p>
        </div>
      ),
    },
    {
      key: 'category',
      header: 'Category',
      render: (f) => <span className="text-xs font-medium text-[var(--pn-ink)]">{f.category}</span>,
    },
    {
      key: 'priority',
      header: 'Priority',
      render: (f) => <span className="font-mono text-xs font-bold text-[var(--pn-ink)]">{f.priority}</span>,
    },
    {
      key: 'classification',
      header: 'Classification',
      render: (f) => (
        <span className={`text-xs font-bold whitespace-nowrap ${CLASSIFICATION_STYLE[f.classification]}`}>
          {f.classification}
        </span>
      ),
    },
    {
      key: 'testing',
      header: 'Testing',
      render: (f) => (
        <span className="text-xs text-[var(--pn-ink-soft)] whitespace-nowrap">
          {f.testingStatus}
        </span>
      ),
    },
  ];

  return (
    <div className="space-y-8">
      <SectionBand
        title="336 Feature Registry & Verification Classification"
        kicker="09. Honest Implementation Audit"
        description={`Tracking ${summary.total} features across ${FEATURE_CATEGORIES.length} Paymenter & Petalnode categories with strict backend separation.`}
      >
        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <SketchCard surface="moss">
            <p className="text-xs font-semibold text-[var(--pn-moss-700)]">Theme UI implemented</p>
            <p className="mt-1 font-display text-3xl font-bold text-[var(--pn-ink)] tabular-nums">
              {summary.byClassification['Theme UI implemented']}
            </p>
          </SketchCard>
          <SketchCard surface="blossom">
            <p className="text-xs font-semibold text-[var(--pn-blossom-700)]">Paymenter integration pending</p>
            <p className="mt-1 font-display text-3xl font-bold text-[var(--pn-ink)] tabular-nums">
              {summary.byClassification['Paymenter integration pending']}
            </p>
          </SketchCard>
          <SketchCard surface="shade">
            <p className="text-xs font-semibold text-[var(--pn-ink-soft)]">Backend verified</p>
            <p className="mt-1 font-display text-3xl font-bold text-[var(--pn-ink)] tabular-nums">
              {summary.byClassification['Backend verified']}
            </p>
          </SketchCard>
          <SketchCard surface="shade">
            <p className="text-xs font-semibold text-[var(--pn-ink-soft)]">Production tested</p>
            <p className="mt-1 font-display text-3xl font-bold text-[var(--pn-ink)] tabular-nums">
              {summary.byClassification['Production tested']}
            </p>
          </SketchCard>
        </div>

        {/* Filter Controls */}
        <div className="mt-6 flex flex-col gap-4 rounded-[var(--pn-radius-lg)] border border-[var(--pn-paper-line)] bg-[#FFFDF9] p-4">
          <div className="grid items-end gap-4 md:grid-cols-3">
            <div className="md:col-span-1">
              <SearchInput
                value={query}
                onChange={(val) => {
                  setQuery(val);
                  setPage(1);
                }}
                placeholder="Search ID, feature, or keyword..."
                label="Search feature registry"
                visibleLabel
              />
            </div>
            <SketchSelect
              label="Category"
              value={category}
              onChange={(e) => {
                setCategory(e.target.value as FeatureCategory | 'ALL');
                setPage(1);
              }}
              options={[
                { value: 'ALL', label: `All Categories (${FEATURE_CATEGORIES.length})` },
                ...FEATURE_CATEGORIES.map((c) => ({
                  value: c,
                  label: `${c} (${summary.byCategory[c]})`,
                })),
              ]}
            />
            <SketchSelect
              label="Priority"
              value={priority}
              onChange={(e) => {
                setPriority(e.target.value as FeaturePriority | 'ALL');
                setPage(1);
              }}
              options={[
                { value: 'ALL', label: 'All Priorities' },
                { value: 'P0', label: `P0 Critical (${summary.byPriority.P0})` },
                { value: 'P1', label: `P1 Core (${summary.byPriority.P1})` },
                { value: 'P2', label: `P2 Polish (${summary.byPriority.P2})` },
              ]}
            />
          </div>

          <div className="flex flex-wrap items-center justify-between gap-3">
            <SegmentedFilter
              ariaLabel="Filter by classification"
              activeId={classification}
              onChange={(id) => {
                setClassification(id as FeatureClassification | 'ALL');
                setPage(1);
              }}
              options={[
                { id: 'ALL', label: 'All', count: summary.total },
                {
                  id: 'Theme UI implemented',
                  label: 'Theme UI implemented',
                  count: summary.byClassification['Theme UI implemented'],
                },
                {
                  id: 'Paymenter integration pending',
                  label: 'Paymenter integration pending',
                  count: summary.byClassification['Paymenter integration pending'],
                },
                {
                  id: 'Backend verified',
                  label: 'Backend verified',
                  count: summary.byClassification['Backend verified'],
                },
                {
                  id: 'Production tested',
                  label: 'Production tested',
                  count: summary.byClassification['Production tested'],
                },
              ]}
            />
            <p className="text-xs font-semibold text-[var(--pn-ink-soft)] tabular-nums">
              Showing {filtered.length} matching features
            </p>
          </div>
        </div>

        <div className="mt-5">
          <SketchTable
            columns={columns}
            rows={pagedRows}
            emptyTitle="No matching features"
            emptyDescription="Try clearing the search query or selecting All Classifications."
          />
          <Pagination page={page} totalPages={totalPages} onPageChange={setPage} />
        </div>
      </SectionBand>
    </div>
  );
};
