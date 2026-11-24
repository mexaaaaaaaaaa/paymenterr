import React, { useMemo, useState } from 'react';
import { Bell, Eye, Plus, Sparkles } from 'lucide-react';
import { type PaymenterEntityStatus } from '../../theme/utils/sketchGeometry';
import {
  Breadcrumbs,
  EmptyState,
  ErrorState,
  InlineBadge,
  LoadingState,
  Pagination,
  SearchInput,
  SectionBand,
  SegmentedFilter,
  Skeleton,
  SketchAlert,
  SketchButton,
  SketchCard,
  SketchCheckbox,
  SketchDrawer,
  SketchDropdown,
  SketchInput,
  SketchModal,
  SketchSelect,
  SketchTable,
  SketchTextarea,
  SketchTooltip,
  StatusIndicator,
  type TableColumn,
  useToast,
} from '../../theme/ui';

interface UiTableSpecimen {
  id: string;
  name: string;
  plan: string;
  cycle: string;
  sortWeight: number;
  status: PaymenterEntityStatus;
}

const SAMPLE_ROWS: UiTableSpecimen[] = [
  { id: 'UI-001', name: 'Active Status Specimen', plan: 'Component Slot A', cycle: 'Badge + Icon', sortWeight: 1, status: 'active' },
  { id: 'UI-002', name: 'Pending Status Specimen', plan: 'Component Slot B', cycle: 'Badge + Icon', sortWeight: 2, status: 'pending' },
  { id: 'UI-003', name: 'Settled Status Specimen', plan: 'Component Slot C', cycle: 'Badge + Icon', sortWeight: 3, status: 'paid' },
  { id: 'UI-004', name: 'Overdue Status Specimen', plan: 'Component Slot D', cycle: 'Badge + Icon', sortWeight: 4, status: 'overdue' },
  { id: 'UI-005', name: 'Answered Status Specimen', plan: 'Component Slot E', cycle: 'Badge + Icon', sortWeight: 5, status: 'answered' },
];

export const UIKitSection: React.FC = () => {
  const { notify } = useToast();
  const [modalOpen, setModalOpen] = useState(false);
  const [drawerOpen, setDrawerOpen] = useState(false);
  const [btnLoading, setBtnLoading] = useState(false);

  // Validated form state
  const [hostname, setHostname] = useState('');
  const [region, setRegion] = useState('eu-west');
  const [notes, setNotes] = useState('');
  const [agreeTerms, setAgreeTerms] = useState(false);
  const [formErrors, setFormErrors] = useState<{ hostname?: string; agreeTerms?: string }>({});

  // Table & state demos
  const [tableQuery, setTableQuery] = useState('');
  const [statusFilter, setStatusFilter] = useState('all');
  const [sortDir, setSortDir] = useState<'asc' | 'desc'>('asc');
  const [page, setPage] = useState(1);
  const [viewMode, setViewMode] = useState<'table' | 'loading' | 'empty' | 'error' | 'skeleton'>('table');

  const handleTriggerLoading = () => {
    setBtnLoading(true);
    window.setTimeout(() => {
      setBtnLoading(false);
      notify({
        tone: 'success',
        title: 'Button action settled',
        message: 'Async loading state completed without blocking UI.',
      });
    }, 800);
  };

  const handleFormSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const errs: { hostname?: string; agreeTerms?: string } = {};
    if (!hostname.trim() || hostname.trim().length < 3) {
      errs.hostname = 'Enter a server label of at least 3 characters.';
    }
    if (!agreeTerms) {
      errs.agreeTerms = 'Accept the terms checkbox before submitting.';
    }
    setFormErrors(errs);
    if (Object.keys(errs).length > 0) {
      notify({
        tone: 'danger',
        title: 'Validation needed',
        message: 'Please fix the highlighted form fields.',
      });
      return;
    }

    notify({
      tone: 'success',
      title: 'Configuration saved',
      message: `Prepared ${hostname.trim()} in ${region.toUpperCase()}.`,
    });
    setHostname('');
    setNotes('');
    setAgreeTerms(false);
    setFormErrors({});
  };

  const filteredRows = useMemo(() => {
    const q = tableQuery.trim().toLowerCase();
    const list = SAMPLE_ROWS.filter((row) => {
      if (statusFilter !== 'all' && row.status !== statusFilter) return false;
      if (!q) return true;
      return (
        row.id.toLowerCase().includes(q) ||
        row.name.toLowerCase().includes(q) ||
        row.plan.toLowerCase().includes(q)
      );
    });
    return [...list].sort((a, b) =>
      sortDir === 'asc' ? a.sortWeight - b.sortWeight : b.sortWeight - a.sortWeight
    );
  }, [tableQuery, statusFilter, sortDir]);

  const columns: TableColumn<UiTableSpecimen>[] = [
    {
      key: 'id',
      header: 'Ref',
      render: (r) => <InlineBadge label={r.id} emphasis="accent" />,
    },
    {
      key: 'name',
      header: 'Specimen',
      render: (r) => <span className="font-semibold text-[var(--pn-ink)]">{r.name}</span>,
    },
    {
      key: 'plan',
      header: 'Slot',
      render: (r) => <span className="text-[var(--pn-ink-soft)]">{r.plan} · {r.cycle}</span>,
    },
    {
      key: 'status',
      header: 'Status',
      render: (r) => <StatusIndicator status={r.status} />,
    },
    {
      key: 'sortWeight',
      header: 'Order',
      align: 'right',
      sortable: true,
      render: (r) => (
        <span className="font-mono font-semibold text-[var(--pn-ink)] tabular-nums">
          #{r.sortWeight}
        </span>
      ),
    },
  ];

  return (
    <div className="space-y-10">
      <SectionBand
        title="Buttons, Overlays &amp; Notifications"
        kicker="06. Reusable UI Primitives"
        description="Accessible controls, modals, slide-over drawers, dropdowns, tooltips, and live toast notifications."
      >
        <div className="grid gap-6 lg:grid-cols-2">
          <SketchCard>
            <h3 className="font-display text-base font-bold text-[var(--pn-ink)]">
              Button Variants &amp; Interactive Overlays
            </h3>
            <div className="mt-4 flex flex-wrap items-center gap-3">
              <SketchButton variant="primary" iconLeft={<Plus size={16} />} onClick={() => setModalOpen(true)}>
                Open Modal
              </SketchButton>
              <SketchButton variant="secondary" iconLeft={<Eye size={16} />} onClick={() => setDrawerOpen(true)}>
                Open Drawer
              </SketchButton>
              <SketchButton variant="moss" loading={btnLoading} onClick={handleTriggerLoading}>
                {btnLoading ? 'Saving...' : 'Test Async State'}
              </SketchButton>
              <SketchButton
                variant="bark"
                iconLeft={<Bell size={16} />}
                onClick={() =>
                  notify({
                    tone: 'warning',
                    title: 'Invoice reminder',
                    message: 'Sample toast notification triggered via useToast().',
                  })
                }
              >
                Trigger Toast
              </SketchButton>
              <SketchTooltip content="Keyboard & hover tooltip">
                <SketchButton variant="ghost" iconLeft={<Sparkles size={16} />}>
                  Hover Tooltip
                </SketchButton>
              </SketchTooltip>
            </div>

            <div className="mt-5 flex flex-wrap items-center justify-between gap-4 border-t border-[var(--pn-paper-line)] pt-4">
              <Breadcrumbs
                items={[
                  { label: 'Client Area', onClick: () => notify({ tone: 'info', title: 'Breadcrumb clicked', message: 'Returned to Client Area.' }) },
                  { label: 'Catalog', onClick: () => notify({ tone: 'info', title: 'Breadcrumb clicked', message: 'Opened Catalog.' }) },
                  { label: 'UI Specimen' },
                ]}
              />
              <SketchDropdown
                label="Quick Actions"
                items={[
                  { id: 'restart', label: 'Send toast alert', onSelect: () => notify({ tone: 'success', title: 'Action dispatched', message: 'Dropdown item clicked.' }) },
                  { id: 'modal', label: 'Inspect dialog', onSelect: () => setModalOpen(true) },
                  { id: 'drawer', label: 'Open side drawer', onSelect: () => setDrawerOpen(true) },
                ]}
              />
            </div>
          </SketchCard>

          <SketchCard>
            <h3 className="font-display text-base font-bold text-[var(--pn-ink)]">
              Semantic Alerts (Icon + Text Paired)
            </h3>
            <div className="mt-4 space-y-3">
              <SketchAlert
                tone="success"
                title="Success alert pattern"
                description="Rendered when Paymenter confirms a completed backend action."
              />
              <SketchAlert
                tone="warning"
                title="Warning alert pattern"
                description="Rendered when Paymenter flags an upcoming renewal or pending setup."
              />
              <SketchAlert
                tone="danger"
                title="Danger alert pattern"
                description="Rendered when backend validation or payment authorization fails."
              />
            </div>
          </SketchCard>
        </div>
      </SectionBand>

      {/* Forms Showcase */}
      <SectionBand
        title="Accessible Form System"
        kicker="07. Validation & Labels"
        description="Native label pairing, required indicators, inline validation errors, and accessible control states."
      >
        <SketchCard>
          <form onSubmit={handleFormSubmit} noValidate className="grid gap-5 md:grid-cols-2">
            <SketchInput
              label="Server Label"
              placeholder="e.g. cherry-grove-smp"
              hint="Minimum 3 characters; used on your dashboard."
              value={hostname}
              onChange={(e) => setHostname(e.target.value)}
              error={formErrors.hostname}
              required
            />
            <SketchSelect
              label="Preferred Node Region"
              value={region}
              onChange={(e) => setRegion(e.target.value)}
              options={[
                { value: 'eu-west', label: 'Europe West (Frankfurt)' },
                { value: 'us-east', label: 'North America East (Virginia)' },
                { value: 'ap-sg', label: 'Asia Pacific (Singapore)' },
              ]}
              hint="Selects nearest low-latency datacenter."
            />
            <div className="md:col-span-2">
              <SketchTextarea
                label="Provisioning Notes"
                placeholder="Optional modpack loader or startup flags..."
                value={notes}
                onChange={(e) => setNotes(e.target.value)}
              />
            </div>
            <div className="flex flex-col justify-between gap-4 md:col-span-2 sm:flex-row sm:items-center">
              <div>
                <SketchCheckbox
                  label="Confirm Paymenter configuration rules"
                  description="Validates required fields before submitting."
                  checked={agreeTerms}
                  onChange={(e) => setAgreeTerms(e.target.checked)}
                />
                {formErrors.agreeTerms && (
                  <p role="alert" className="mt-1 text-xs font-semibold text-[var(--pn-status-danger)]">
                    {formErrors.agreeTerms}
                  </p>
                )}
              </div>
              <SketchButton type="submit" variant="primary">
                Validate &amp; Submit
              </SketchButton>
            </div>
          </form>
        </SketchCard>
      </SectionBand>

      {/* Data Table & States */}
      <SectionBand
        title="Data Table, Search, Filters &amp; State Views"
        kicker="08. Tabular Ledger & States"
        description="Switch between live populated table data and built-in loading, empty, error, and skeleton states."
        actions={
          <SegmentedFilter
            ariaLabel="Preview table state"
            activeId={viewMode}
            onChange={(id) => setViewMode(id as typeof viewMode)}
            options={[
              { id: 'table', label: 'Populated' },
              { id: 'loading', label: 'Loading' },
              { id: 'skeleton', label: 'Skeleton' },
              { id: 'empty', label: 'Empty' },
              { id: 'error', label: 'Error' },
            ]}
          />
        }
      >
        {viewMode === 'table' && (
          <div className="space-y-4">
            <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
              <SearchInput
                value={tableQuery}
                onChange={setTableQuery}
                placeholder="Filter sample services or invoices..."
                label="Filter sample table"
              />
              <SegmentedFilter
                ariaLabel="Filter by status"
                activeId={statusFilter}
                onChange={setStatusFilter}
                options={[
                  { id: 'all', label: 'All' },
                  { id: 'active', label: 'Active' },
                  { id: 'paid', label: 'Paid' },
                  { id: 'overdue', label: 'Overdue' },
                ]}
              />
            </div>
            <SketchTable
              columns={columns}
              rows={filteredRows}
              sortKey="amount"
              sortDir={sortDir}
              onSort={() => setSortDir((d) => (d === 'asc' ? 'desc' : 'asc'))}
            />
            <Pagination page={page} totalPages={3} onPageChange={setPage} />
          </div>
        )}

        {viewMode === 'loading' && <LoadingState message="Syncing Paymenter service statuses..." />}
        {viewMode === 'skeleton' && (
          <SketchCard>
            <div className="space-y-3">
              <Skeleton className="h-5 w-1/3" />
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-4/5" />
            </div>
          </SketchCard>
        )}
        {viewMode === 'empty' && (
          <EmptyState
            title="No services planted yet"
            description="Browse our cozy game server plans to launch your first world."
            actionLabel="Return to table"
            onAction={() => setViewMode('table')}
          />
        )}
        {viewMode === 'error' && (
          <ErrorState
            description="Could not reach the billing endpoint. Your data is safe—try refreshing."
            onRetry={() => setViewMode('table')}
          />
        )}
      </SectionBand>

      {/* Modal & Drawer Instances */}
      <SketchModal
        open={modalOpen}
        onClose={() => setModalOpen(false)}
        title="Sketchbook Confirmation Modal"
        description="Accessible dialog with Escape key dismissal, backdrop blur, and clean footer actions."
        footer={
          <>
            <SketchButton variant="secondary" onClick={() => setModalOpen(false)}>
              Cancel
            </SketchButton>
            <SketchButton
              variant="primary"
              onClick={() => {
                setModalOpen(false);
                notify({ tone: 'success', title: 'Confirmed', message: 'Modal action completed.' });
              }}
            >
              Confirm Action
            </SketchButton>
          </>
        }
      >
        <p className="text-sm text-[var(--pn-ink-soft)]">
          In Paymenter views, this modal pattern wraps service cancellation, plan upgrades,
          2FA setup confirmation, and gateway notices cleanly at <code className="font-mono">z-index: 70</code>.
        </p>
      </SketchModal>

      <SketchDrawer
        open={drawerOpen}
        onClose={() => setDrawerOpen(false)}
        title="Slide-Over Details Drawer"
        footer={
          <div className="flex items-center justify-between gap-3">
            <span className="font-display text-sm font-bold text-[var(--pn-ink)]">
              Backend Total Slot
            </span>
            <SketchButton variant="primary" size="sm" onClick={() => setDrawerOpen(false)}>
              Done Inspecting
            </SketchButton>
          </div>
        }
      >
        <div className="space-y-4 text-sm text-[var(--pn-ink-soft)]">
          <p>
            Drawers provide quick inspection for Paymenter-backed cart items, notification history,
            and mobile navigation without leaving the current page.
          </p>
          <SketchAlert
            tone="info"
            title="Accessible Layering"
            description="Supports Escape dismissal, backdrop click close, and preserved focus."
          />
        </div>
      </SketchDrawer>
    </div>
  );
};
