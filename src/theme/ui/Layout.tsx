import React, { useState } from 'react';
import { Menu, X } from 'lucide-react';
import { useScrollProgress } from '../animations/hooks';
import { HandIcon } from '../sketch/HandIcon';
import { SketchButton } from './SketchButton';

export interface NavItem {
  id: string;
  label: string;
}

export interface ThemeHeaderProps {
  brandName: string;
  navItems: NavItem[];
  activeNavId: string;
  onSelectNav: (id: string) => void;
  primaryActionLabel: string;
  onPrimaryAction: () => void;
}

export const ScrollProgressBar: React.FC = () => {
  const progress = useScrollProgress();
  return (
    <div
      aria-hidden="true"
      className="fixed top-0 right-0 left-0 z-[var(--pn-z-header)] h-[3px] bg-transparent pointer-events-none"
    >
      <div
        className="h-full origin-left bg-[var(--pn-blossom-600)] transition-transform duration-75"
        style={{ transform: `scaleX(${progress})` }}
      />
    </div>
  );
};

/**
 * 3-Zone Top Bar Contract:
 * [Brand wordmark] — [4-5 concise single-line nav links] — [1 primary action]
 */
export const ThemeHeader: React.FC<ThemeHeaderProps> = ({
  brandName,
  navItems,
  activeNavId,
  onSelectNav,
  primaryActionLabel,
  onPrimaryAction,
}) => {
  const [mobileOpen, setMobileOpen] = useState(false);

  const handleSelect = (id: string) => {
    onSelectNav(id);
    setMobileOpen(false);
  };

  return (
    <header className="sticky top-0 z-[var(--pn-z-header)] border-b border-[var(--pn-paper-line)] bg-[var(--pn-paper)]/95 backdrop-blur-sm">
      <div className="mx-auto flex max-w-[1200px] items-center justify-between gap-8 px-4 py-3 sm:px-6">
        {/* Zone 1: Single-line Brand Wordmark */}
        <a
          href="#top"
          onClick={(e) => {
            e.preventDefault();
            handleSelect(navItems[0]?.id ?? 'overview');
          }}
          className="inline-flex items-center gap-2 font-display text-lg font-bold tracking-tight text-[var(--pn-ink)] whitespace-nowrap shrink-0"
        >
          <HandIcon name="blossom" size={20} className="text-[var(--pn-blossom-600)]" />
          <span>{brandName}</span>
        </a>

        {/* Zone 2: 4-5 Clean Single-Line Nav Links */}
        <nav aria-label="Primary Navigation" className="hidden items-center gap-6 md:flex">
          {navItems.slice(0, 5).map((item) => {
            const active = item.id === activeNavId;
            return (
              <button
                key={item.id}
                type="button"
                onClick={() => handleSelect(item.id)}
                className={`relative py-1 text-sm font-semibold transition-colors whitespace-nowrap shrink-0 cursor-pointer ${
                  active
                    ? 'text-[var(--pn-blossom-700)]'
                    : 'text-[var(--pn-ink-soft)] hover:text-[var(--pn-ink)]'
                }`}
              >
                {item.label}
                {active && (
                  <span
                    aria-hidden="true"
                    className="absolute right-0 bottom-0 left-0 h-[2px] rounded-full bg-[var(--pn-blossom-600)]"
                  />
                )}
              </button>
            );
          })}
        </nav>

        {/* Zone 3: 1 Primary CTA + Mobile Hamburger */}
        <div className="flex items-center gap-2 shrink-0">
          <div className="hidden sm:block">
            <SketchButton variant="primary" size="sm" onClick={onPrimaryAction}>
              {primaryActionLabel}
            </SketchButton>
          </div>
          <button
            type="button"
            aria-label={mobileOpen ? 'Close navigation menu' : 'Open navigation menu'}
            aria-expanded={mobileOpen}
            onClick={() => setMobileOpen((prev) => !prev)}
            className="inline-flex h-10 w-10 items-center justify-center rounded-[var(--pn-radius-sm)] border border-[var(--pn-paper-line)] bg-[#FFFDF9] text-[var(--pn-ink)] md:hidden cursor-pointer"
          >
            {mobileOpen ? <X size={18} /> : <Menu size={18} />}
          </button>
        </div>
      </div>

      {/* Mobile Navigation Panel */}
      {mobileOpen && (
        <div className="border-t border-[var(--pn-paper-line)] bg-[var(--pn-paper)] px-4 py-4 md:hidden">
          <nav aria-label="Mobile Navigation" className="flex flex-col gap-2">
            {navItems.slice(0, 5).map((item) => {
              const active = item.id === activeNavId;
              return (
                <button
                  key={item.id}
                  type="button"
                  onClick={() => handleSelect(item.id)}
                  className={`flex h-11 items-center rounded-[var(--pn-radius-sm)] px-3 text-left text-sm font-semibold transition-colors cursor-pointer ${
                    active
                      ? 'bg-[var(--pn-blossom-50)] text-[var(--pn-blossom-700)]'
                      : 'text-[var(--pn-ink)] hover:bg-[var(--pn-paper-shade)]'
                  }`}
                >
                  {item.label}
                </button>
              );
            })}
            <div className="pt-2">
              <SketchButton
                variant="primary"
                size="md"
                className="w-full"
                onClick={() => {
                  setMobileOpen(false);
                  onPrimaryAction();
                }}
              >
                {primaryActionLabel}
              </SketchButton>
            </div>
          </nav>
        </div>
      )}
    </header>
  );
};

export const PageContainer: React.FC<{
  children: React.ReactNode;
  className?: string;
}> = ({ children, className = '' }) => (
  <main className={`mx-auto w-full max-w-[1200px] px-4 py-8 sm:px-6 lg:py-12 ${className}`}>
    {children}
  </main>
);

export const SectionBand: React.FC<{
  id?: string;
  title: string;
  kicker?: string;
  description?: string;
  actions?: React.ReactNode;
  children: React.ReactNode;
  className?: string;
}> = ({ id, title, kicker, description, actions, children, className = '' }) => (
  <section id={id} className={`py-8 sm:py-10 ${className}`}>
    <div className="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
      <div className="max-w-2xl">
        {kicker && (
          <p className="mb-1 font-hand text-sm font-semibold text-[var(--pn-bark)]">{kicker}</p>
        )}
        <h2 className="font-display text-2xl font-bold tracking-tight text-[var(--pn-ink)] sm:text-3xl">
          {title}
        </h2>
        {description && (
          <p className="mt-2 text-base text-[var(--pn-ink-soft)]">{description}</p>
        )}
      </div>
      {actions && <div className="shrink-0">{actions}</div>}
    </div>
    {children}
  </section>
);

export const ThemeFooter: React.FC<{
  brandName: string;
  links: NavItem[];
  onSelectNav: (id: string) => void;
}> = ({ brandName, links, onSelectNav }) => (
  <footer className="mt-12 border-t border-[var(--pn-paper-line)] bg-[var(--pn-paper-shade)]/55">
    <div className="mx-auto flex max-w-[1200px] flex-col gap-4 px-4 py-8 sm:flex-row sm:items-center sm:justify-between sm:px-6">
      <div className="flex items-center gap-2 text-sm text-[var(--pn-ink-soft)]">
        <HandIcon name="leaf" size={18} className="text-[var(--pn-moss-700)]" />
        <span className="font-semibold text-[var(--pn-ink)]">{brandName}</span>
        <span aria-hidden="true">·</span>
        <span>Sketchbook Theme Foundation for Paymenter</span>
      </div>
      <nav aria-label="Footer Navigation" className="flex flex-wrap items-center gap-5 text-sm">
        {links.map((item) => (
          <button
            key={item.id}
            type="button"
            onClick={() => onSelectNav(item.id)}
            className="font-medium text-[var(--pn-ink-soft)] hover:text-[var(--pn-ink)] cursor-pointer whitespace-nowrap"
          >
            {item.label}
          </button>
        ))}
      </nav>
    </div>
  </footer>
);
