import React from 'react';
import { Copy } from 'lucide-react';
import {
  COLOR_SWATCH_META,
  PETALNODE_FONTS,
  PETALNODE_Z_INDEX,
} from '../../theme/tokens';
import { SectionBand, SketchCard, useToast } from '../../theme/ui';

export const TokensSection: React.FC = () => {
  const { notify } = useToast();

  const copyToken = async (label: string, value: string) => {
    try {
      await navigator.clipboard.writeText(value);
      notify({
        tone: 'success',
        title: `Copied ${label}`,
        message: `${value} copied to clipboard.`,
      });
    } catch {
      notify({
        tone: 'info',
        title: label,
        message: `Token value: ${value}`,
      });
    }
  };

  return (
    <div className="space-y-10">
      <SectionBand
        title="Color Tokens (:root)"
        kicker="02. Petalnode Palette"
        description="Warm sketchbook paper canvas paired with high-contrast charcoal ink, cherry blossom accents, moss, bark, and lantern gold."
      >
        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
          {COLOR_SWATCH_META.map((swatch) => (
            <button
              key={swatch.key}
              type="button"
              onClick={() => copyToken(swatch.label, swatch.hex)}
              className="group flex flex-col overflow-hidden rounded-[var(--pn-radius-md)] border border-[var(--pn-paper-line)] bg-[#FFFDF9] text-left shadow-[var(--pn-shadow-paper-sm)] transition-transform hover:-translate-y-0.5 cursor-pointer"
            >
              <div
                className="flex h-20 w-full items-end justify-between p-3"
                style={{ backgroundColor: swatch.hex, color: swatch.inkOnSwatch }}
              >
                <span className="font-mono text-xs font-bold">{swatch.hex}</span>
                <Copy size={14} aria-hidden="true" className="opacity-70 group-hover:opacity-100" />
              </div>
              <div className="flex-1 p-3.5">
                <div className="flex items-center justify-between gap-2">
                  <span className="font-display text-sm font-bold text-[var(--pn-ink)]">
                    {swatch.label}
                  </span>
                  <span className="font-mono text-[11px] text-[var(--pn-ink-soft)]">
                    {swatch.cssVar}
                  </span>
                </div>
                <p className="mt-1.5 text-xs leading-relaxed text-[var(--pn-ink-soft)]">
                  {swatch.usage}
                </p>
              </div>
            </button>
          ))}
        </div>
      </SectionBand>

      <SectionBand
        title="Typography Specimens"
        kicker="03. Five-Voice Sketchbook Type System"
        description="Loaded via Google Fonts with font-display: swap so every voice from editorial headings to pixel server counters renders cleanly."
      >
        <div className="grid gap-5 md:grid-cols-2">
          {Object.entries(PETALNODE_FONTS).map(([key, font]) => (
            <SketchCard key={key}>
              <div className="flex items-center justify-between gap-2 border-b border-[var(--pn-paper-line)] pb-3">
                <span className="font-display text-sm font-bold text-[var(--pn-ink)]">
                  {font.name}
                </span>
                <span className="font-mono text-xs text-[var(--pn-blossom-700)]">{font.cssVar}</span>
              </div>
              <p
                className="mt-4 text-2xl font-bold text-[var(--pn-ink)]"
                style={{ fontFamily: font.cssVar }}
              >
                Cozy voxel realms, sketched by hand. 128 GB NVMe
              </p>
              <p className="mt-2 text-xs text-[var(--pn-ink-soft)]">{font.role}</p>
            </SketchCard>
          ))}
        </div>
      </SectionBand>

      <SectionBand
        title="Z-Index Layer Contract"
        kicker="04. Predictable Stacking"
        description="Prevents Paymenter Livewire modals, 2FA prompts, and tooltips from clipping behind sticky headers."
      >
        <div className="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-7">
          {Object.entries(PETALNODE_Z_INDEX).map(([layer, z]) => (
            <div
              key={layer}
              className="rounded-[var(--pn-radius-md)] border border-[var(--pn-paper-line)] bg-[#FFFDF9] p-3.5 text-center"
            >
              <p className="font-mono text-lg font-bold text-[var(--pn-blossom-700)] tabular-nums">
                {z}
              </p>
              <p className="mt-0.5 text-xs font-semibold text-[var(--pn-ink)] capitalize">{layer}</p>
            </div>
          ))}
        </div>
      </SectionBand>
    </div>
  );
};
