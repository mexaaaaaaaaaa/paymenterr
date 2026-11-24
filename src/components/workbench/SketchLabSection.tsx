import React, { useState } from 'react';
import {
  Annotation,
  HandIcon,
  InkArrow,
  NodeyMascot,
  PolaroidFrame,
  RoughBox,
  RoughCircle,
  RoughUnderline,
  SproutMascot,
  Stamp,
  WashiTape,
  type NodeyMascotProps,
  type SproutMascotProps,
} from '../../theme/sketch';
import { SectionBand, SegmentedFilter, SketchButton, SketchCard } from '../../theme/ui';

export interface SketchLabSectionProps {
  paperMode: 'plain' | 'grid' | 'notebook';
  onPaperModeChange: (mode: 'plain' | 'grid' | 'notebook') => void;
}

export const SketchLabSection: React.FC<SketchLabSectionProps> = ({
  paperMode,
  onPaperModeChange,
}) => {
  const [nodeyMood, setNodeyMood] = useState<NonNullable<NodeyMascotProps['mood']>>('happy');
  const [sproutStage, setSproutStage] =
    useState<NonNullable<SproutMascotProps['stage']>>('blooming');
  const [drawIteration, setDrawIteration] = useState(1);

  return (
    <div className="space-y-10">
      <SectionBand
        title="Signature Sketch Components"
        kicker="05. Rough.js + Original SVG Companions"
        description="Interactive laboratory for the 12 signature sketchbook primitives—all deterministic, resizable, and reduced-motion friendly."
        actions={
          <SegmentedFilter
            ariaLabel="Paper canvas texture"
            activeId={paperMode}
            onChange={(id) => onPaperModeChange(id as 'plain' | 'grid' | 'notebook')}
            options={[
              { id: 'plain', label: 'Plain Paper' },
              { id: 'grid', label: 'Sketch Grid' },
              { id: 'notebook', label: 'Ruled Lines' },
            ]}
          />
        }
      >
        {/* Interactive Mascots Row */}
        <div className="grid gap-6 lg:grid-cols-2">
          <SketchCard>
            <div className="flex items-start justify-between gap-4">
              <div>
                <h3 className="font-display text-lg font-bold text-[var(--pn-ink)]">
                  Nodey Mascot States
                </h3>
                <p className="mt-1 text-xs text-[var(--pn-ink-soft)]">
                  Used across hero welcomes, provisioning spinners, and alert banners.
                </p>
              </div>
              <Stamp label={nodeyMood} tone="blossom" rotateDeg={-3} />
            </div>

            <div className="mt-5 flex flex-col items-center justify-center rounded-[var(--pn-radius-md)] border border-[var(--pn-paper-line)] bg-[var(--pn-paper)] py-6">
              <NodeyMascot mood={nodeyMood} size={124} />
              <div className="mt-4 flex flex-wrap justify-center gap-2">
                {(['idle', 'happy', 'working', 'alert'] as const).map((m) => (
                  <SketchButton
                    key={m}
                    size="sm"
                    variant={nodeyMood === m ? 'primary' : 'secondary'}
                    onClick={() => setNodeyMood(m)}
                  >
                    {m}
                  </SketchButton>
                ))}
              </div>
            </div>
          </SketchCard>

          <SketchCard>
            <div className="flex items-start justify-between gap-4">
              <div>
                <h3 className="font-display text-lg font-bold text-[var(--pn-ink)]">
                  Sprout Botanical Stages
                </h3>
                <p className="mt-1 text-xs text-[var(--pn-ink-soft)]">
                  Represents account onboarding, empty states, and growing game communities.
                </p>
              </div>
              <Stamp label={sproutStage} tone="moss" rotateDeg={3} />
            </div>

            <div className="mt-5 flex flex-col items-center justify-center rounded-[var(--pn-radius-md)] border border-[var(--pn-paper-line)] bg-[var(--pn-paper)] py-6">
              <SproutMascot stage={sproutStage} size={116} />
              <div className="mt-4 flex flex-wrap justify-center gap-2">
                {(['seedling', 'growing', 'blooming', 'resting'] as const).map((s) => (
                  <SketchButton
                    key={s}
                    size="sm"
                    variant={sproutStage === s ? 'moss' : 'secondary'}
                    onClick={() => setSproutStage(s)}
                  >
                    {s}
                  </SketchButton>
                ))}
              </div>
            </div>
          </SketchCard>
        </div>

        {/* Rough.js Geometry & Stickers */}
        <div className="mt-6 grid gap-6 lg:grid-cols-3">
          <RoughBox
            seedKey={`demo-box-${drawIteration}`}
            strokeColor="#2B2230"
            fillColor="#FFF1F4"
            fillStyle="hachure"
            animateDraw
            className="bg-[#FFFDF9]"
          >
            <div className="flex items-center justify-between gap-2">
              <h3 className="font-display text-base font-bold text-[var(--pn-ink)]">
                RoughBox + Stroke Draw
              </h3>
              <WashiTape tone="lantern" rotation={2} label="Rough.js" />
            </div>
            <p className="mt-2 text-sm text-[var(--pn-ink-soft)]">
              Uses deterministic FNV-1a seeds and native SVG{' '}
              <RoughUnderline color="#E2557B" seedKey={`ul-${drawIteration}`}>
                strokeDashoffset
              </RoughUnderline>{' '}
              animations.
            </p>
            <div className="mt-4 flex items-center gap-3">
              <SketchButton
                size="sm"
                variant="secondary"
                onClick={() => setDrawIteration((n) => n + 1)}
              >
                Replay ink draw
              </SketchButton>
              <RoughCircle color="#6FAF5B" seedKey="price-circle">
                <span className="font-pixel text-sm font-bold text-[var(--pn-ink)]">Featured</span>
              </RoughCircle>
            </div>
          </RoughBox>

          <PolaroidFrame
            caption="Polaroid Showcase Frame"
            subcaption="Pinned with WashiTape & tilted frame"
            tapeTone="moss"
            tiltDeg={1.4}
          >
            <div className="flex h-36 flex-col items-center justify-center gap-2 bg-[var(--pn-paper-shade)]/55 p-4 text-center">
              <div className="flex items-center gap-3 text-[var(--pn-ink)]">
                <HandIcon name="cube" size={26} />
                <HandIcon name="blossom" size={26} className="text-[var(--pn-blossom-600)]" />
                <HandIcon name="lantern" size={26} className="text-[var(--pn-bark)]" />
              </div>
              <p className="font-pixel text-xs text-[var(--pn-ink-soft)]">
                INK · PAPER · VOXEL CRAFT
              </p>
            </div>
          </PolaroidFrame>

          <SketchCard>
            <h3 className="font-display text-base font-bold text-[var(--pn-ink)]">
              HandIcons, Arrows &amp; Stamps
            </h3>
            <div className="mt-3 flex flex-wrap items-center gap-3 text-[var(--pn-ink)]">
              {(['blossom', 'leaf', 'cube', 'receipt', 'lantern', 'shield', 'quill', 'compass'] as const).map(
                (iconName) => (
                  <span
                    key={iconName}
                    title={iconName}
                    className="inline-flex h-10 w-10 items-center justify-center rounded-[var(--pn-radius-sm)] border border-[var(--pn-paper-line)] bg-[var(--pn-paper)]"
                  >
                    <HandIcon name={iconName} size={20} />
                  </span>
                )
              )}
            </div>
            <div className="mt-4 flex flex-wrap items-center gap-3 border-t border-[var(--pn-paper-line)] pt-4">
              <Stamp label="PAID" tone="moss" rotateDeg={-5} />
              <Stamp label="VERIFIED" tone="bark" rotateDeg={3} />
              <Annotation note="Look here" arrowDirection="right" tone="blossom" />
              <InkArrow direction="down" className="text-[var(--pn-ink-soft)]" />
            </div>
          </SketchCard>
        </div>
      </SectionBand>
    </div>
  );
};
