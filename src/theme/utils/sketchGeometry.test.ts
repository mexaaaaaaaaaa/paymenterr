import test from 'node:test';
import assert from 'node:assert/strict';
import {
  formatCurrencyAmount,
  getPaymenterStatusPresentation,
  hashSeed,
  normalizeSketchBounds,
} from './sketchGeometry';

test('hashSeed produces deterministic positive integer seeds', () => {
  const s1 = hashSeed('petalnode-card-1');
  const s2 = hashSeed('petalnode-card-1');
  const s3 = hashSeed('petalnode-card-2');
  assert.equal(s1, s2);
  assert.notEqual(s1, s3);
  assert.ok(s1 > 0 && Number.isInteger(s1));
});

test('normalizeSketchBounds enforces minimum safe dimensions and padding', () => {
  assert.deepEqual(normalizeSketchBounds(10, 10, 3), { x: 3, y: 3, w: 18, h: 18 });
  assert.deepEqual(normalizeSketchBounds(200, 100, 4), { x: 4, y: 4, w: 192, h: 92 });
});

test('formatCurrencyAmount formats valid and non-finite inputs cleanly', () => {
  assert.equal(formatCurrencyAmount(12.5, 'USD'), '$12.50');
  assert.equal(formatCurrencyAmount(0, 'USD'), '$0.00');
  assert.equal(formatCurrencyAmount(Number.NaN, 'USD'), '$0.00');
});

test('getPaymenterStatusPresentation maps every Paymenter status to paired label + icon', () => {
  const active = getPaymenterStatusPresentation('active');
  assert.equal(active.label, 'Active');
  assert.equal(active.tone, 'success');
  assert.equal(active.symbol, 'check');

  const overdue = getPaymenterStatusPresentation('overdue');
  assert.equal(overdue.label, 'Overdue');
  assert.equal(overdue.tone, 'danger');
  assert.equal(overdue.symbol, 'pause');
});
