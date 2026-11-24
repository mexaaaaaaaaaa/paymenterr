import test from 'node:test';
import assert from 'node:assert/strict';
import {
  COLOR_SWATCH_META,
  PETALNODE_COLORS,
  PETALNODE_FONTS,
  PETALNODE_Z_INDEX,
} from './tokens';
import { getContrastRatio } from './utils/sketchGeometry';

test('art direction hex color tokens match specification', () => {
  assert.equal(PETALNODE_COLORS.paper, '#FBF6EE');
  assert.equal(PETALNODE_COLORS.paperShade, '#F1E7D6');
  assert.equal(PETALNODE_COLORS.paperLine, '#E4D6C1');
  assert.equal(PETALNODE_COLORS.ink, '#2B2230');
  assert.equal(PETALNODE_COLORS.inkSoft, '#6B5E6E');
  assert.equal(PETALNODE_COLORS.blossom50, '#FFF1F4');
  assert.equal(PETALNODE_COLORS.blossom200, '#FFC9D6');
  assert.equal(PETALNODE_COLORS.blossom400, '#FF8FAB');
  assert.equal(PETALNODE_COLORS.blossom600, '#E2557B');
  assert.equal(PETALNODE_COLORS.moss, '#6FAF5B');
  assert.equal(PETALNODE_COLORS.bark, '#6B4A3A');
  assert.equal(PETALNODE_COLORS.lantern, '#FFC75A');
});

test('all 5 required fonts are registered', () => {
  const names = Object.values(PETALNODE_FONTS).map((f) => f.name);
  assert.deepEqual(names.sort(), [
    'Bricolage Grotesque',
    'Fraunces',
    'Nunito',
    'Pixelify Sans',
    'Shantell Sans',
  ]);
});

test('accessible text and button color pairs satisfy WCAG AA contrast', () => {
  assert.ok(getContrastRatio(PETALNODE_COLORS.ink, PETALNODE_COLORS.paper) >= 4.5);
  assert.ok(getContrastRatio(PETALNODE_COLORS.inkSoft, PETALNODE_COLORS.paper) >= 4.5);
  assert.ok(getContrastRatio('#FFFFFF', PETALNODE_COLORS.blossom700) >= 4.5);
  assert.ok(getContrastRatio('#FFFFFF', PETALNODE_COLORS.moss700) >= 4.5);
  assert.ok(getContrastRatio(PETALNODE_COLORS.ink, PETALNODE_COLORS.lantern) >= 4.5);
});

test('z-index layering follows strict ascending hierarchy', () => {
  assert.ok(PETALNODE_Z_INDEX.base < PETALNODE_Z_INDEX.header);
  assert.ok(PETALNODE_Z_INDEX.header < PETALNODE_Z_INDEX.dropdown);
  assert.ok(PETALNODE_Z_INDEX.dropdown < PETALNODE_Z_INDEX.drawer);
  assert.ok(PETALNODE_Z_INDEX.drawer < PETALNODE_Z_INDEX.modal);
  assert.ok(PETALNODE_Z_INDEX.modal < PETALNODE_Z_INDEX.toast);
  assert.ok(PETALNODE_Z_INDEX.toast < PETALNODE_Z_INDEX.tooltip);
});

test('swatch metadata covers all 12 core art direction swatches', () => {
  assert.equal(COLOR_SWATCH_META.length, 12);
});
