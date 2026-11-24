/**
 * Petalnode Paymenter Theme — Typed Design Token Registry
 * Mirrors CSS variables declared in src/theme/tokens.css
 */

export const PETALNODE_COLORS = {
  paper: '#FBF6EE',
  paperShade: '#F1E7D6',
  paperLine: '#E4D6C1',
  ink: '#2B2230',
  inkSoft: '#6B5E6E',
  blossom50: '#FFF1F4',
  blossom200: '#FFC9D6',
  blossom400: '#FF8FAB',
  blossom600: '#E2557B',
  blossom700: '#B8375B',
  moss: '#6FAF5B',
  moss100: '#EAF5E6',
  moss700: '#3B732B',
  bark: '#6B4A3A',
  bark100: '#F4ECE7',
  lantern: '#FFC75A',
  lantern100: '#FFF7E0',
  lantern700: '#8A5A00',
} as const;

export const PETALNODE_FONTS = {
  display: {
    name: 'Bricolage Grotesque',
    cssVar: 'var(--pn-font-display)',
    role: 'Primary headings, product titles, and section anchors',
    weights: [400, 600, 700],
  },
  editorial: {
    name: 'Fraunces',
    cssVar: 'var(--pn-font-editorial)',
    role: 'Editorial hero accents, quotes, and craft stories',
    weights: [400, 600, 700],
  },
  body: {
    name: 'Nunito',
    cssVar: 'var(--pn-font-body)',
    role: 'Body copy, form labels, tables, and billing details',
    weights: [400, 500, 600, 700],
  },
  hand: {
    name: 'Shantell Sans',
    cssVar: 'var(--pn-font-hand)',
    role: 'Sketchbook marginalia, polaroid captions, and ink annotations',
    weights: [400, 600],
  },
  pixel: {
    name: 'Pixelify Sans',
    cssVar: 'var(--pn-font-pixel)',
    role: 'Cozy voxel server specs, RAM/slot counters, and game-world accents',
    weights: [400, 600, 700],
  },
} as const;

export const PETALNODE_SPACING = {
  1: '0.25rem',
  2: '0.5rem',
  3: '0.75rem',
  4: '1rem',
  5: '1.25rem',
  6: '1.5rem',
  8: '2rem',
  10: '2.5rem',
  12: '3rem',
  16: '4rem',
  20: '5rem',
} as const;

export const PETALNODE_Z_INDEX = {
  base: 1,
  raised: 10,
  sticky: 30,
  header: 40,
  dropdown: 50,
  drawer: 60,
  modal: 70,
  toast: 80,
  tooltip: 90,
} as const;

export const PETALNODE_BREAKPOINTS = {
  sm: 640,
  md: 768,
  lg: 1024,
  xl: 1280,
} as const;

export const PETALNODE_MOTION = {
  durations: {
    instant: 0.09,
    fast: 0.16,
    normal: 0.26,
    slow: 0.42,
    draw: 0.75,
  },
  easings: {
    settle: 'power3.out',
    spring: 'back.out(1.45)',
    ink: 'power2.inOut',
  },
} as const;

export type ColorTokenKey = keyof typeof PETALNODE_COLORS;
export type FontTokenKey = keyof typeof PETALNODE_FONTS;

export const COLOR_SWATCH_META: Array<{
  key: ColorTokenKey;
  label: string;
  cssVar: string;
  hex: string;
  usage: string;
  inkOnSwatch: string;
}> = [
  { key: 'paper', label: 'Paper', cssVar: '--pn-paper', hex: PETALNODE_COLORS.paper, usage: 'Primary sketchbook canvas (60% neutral surface)', inkOnSwatch: PETALNODE_COLORS.ink },
  { key: 'paperShade', label: 'Paper Shade', cssVar: '--pn-paper-shade', hex: PETALNODE_COLORS.paperShade, usage: 'Warm elevated panels, table headers, drawers', inkOnSwatch: PETALNODE_COLORS.ink },
  { key: 'paperLine', label: 'Paper Line', cssVar: '--pn-paper-line', hex: PETALNODE_COLORS.paperLine, usage: 'Ruled margins, subtle borders, hairline dividers', inkOnSwatch: PETALNODE_COLORS.ink },
  { key: 'ink', label: 'Ink', cssVar: '--pn-ink', hex: PETALNODE_COLORS.ink, usage: 'Primary typography, rough outlines, stamps', inkOnSwatch: PETALNODE_COLORS.paper },
  { key: 'inkSoft', label: 'Ink Soft', cssVar: '--pn-ink-soft', hex: PETALNODE_COLORS.inkSoft, usage: 'Secondary captions, hints, metadata', inkOnSwatch: PETALNODE_COLORS.paper },
  { key: 'blossom50', label: 'Blossom 50', cssVar: '--pn-blossom-50', hex: PETALNODE_COLORS.blossom50, usage: 'Soft blossom highlight washes & alerts', inkOnSwatch: PETALNODE_COLORS.ink },
  { key: 'blossom200', label: 'Blossom 200', cssVar: '--pn-blossom-200', hex: PETALNODE_COLORS.blossom200, usage: 'Washi tape, focus rings, selection fills', inkOnSwatch: PETALNODE_COLORS.ink },
  { key: 'blossom400', label: 'Blossom 400', cssVar: '--pn-blossom-400', hex: PETALNODE_COLORS.blossom400, usage: 'Illustration petals, underline markers', inkOnSwatch: PETALNODE_COLORS.ink },
  { key: 'blossom600', label: 'Blossom 600', cssVar: '--pn-blossom-600', hex: PETALNODE_COLORS.blossom600, usage: 'Primary brand blossom accent & active markers', inkOnSwatch: '#FFFFFF' },
  { key: 'moss', label: 'Moss', cssVar: '--pn-moss', hex: PETALNODE_COLORS.moss, usage: 'Online services, healthy status, Sprout leaves', inkOnSwatch: PETALNODE_COLORS.ink },
  { key: 'bark', label: 'Bark', cssVar: '--pn-bark', hex: PETALNODE_COLORS.bark, usage: 'Warm wood frames, craft borders, info notes', inkOnSwatch: PETALNODE_COLORS.paper },
  { key: 'lantern', label: 'Lantern', cssVar: '--pn-lantern', hex: PETALNODE_COLORS.lantern, usage: 'Pending warnings, featured stamps, cozy glow', inkOnSwatch: PETALNODE_COLORS.ink },
];
