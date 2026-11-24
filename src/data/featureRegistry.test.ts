import test from 'node:test';
import assert from 'node:assert/strict';
import {
  FEATURE_CATEGORIES,
  FEATURE_CLASSIFICATIONS,
  FEATURE_REGISTRY,
  filterFeatures,
  summarizeFeatureRegistry,
} from './featureRegistry';

test('feature registry preserves all 336 IDs and assigns honest classifications', () => {
  assert.equal(FEATURE_REGISTRY.length, 336, `Expected 336 features, got ${FEATURE_REGISTRY.length}`);
  assert.equal(FEATURE_CATEGORIES.length, 24);

  const ids = new Set(FEATURE_REGISTRY.map((f) => f.id));
  assert.equal(ids.size, FEATURE_REGISTRY.length, 'Every feature ID must be unique');
  assert.ok(ids.has('PN-UIX-001'), 'Original Prompt 01 IDs preserved');
  assert.ok(ids.has('PN-ADM-013'), 'Original Prompt 01 IDs preserved');
  assert.ok(ids.has('PN-STF-001'), 'Storefront IDs preserved');
  assert.ok(ids.has('PN-STF-024'), 'Storefront IDs complete');

  for (const feature of FEATURE_REGISTRY) {
    assert.ok(
      FEATURE_CLASSIFICATIONS.includes(feature.classification),
      `Feature ${feature.id} has invalid classification`
    );
  }

  const summary = summarizeFeatureRegistry(FEATURE_REGISTRY);
  for (const cat of FEATURE_CATEGORIES) {
    assert.ok(summary.byCategory[cat] >= 10, `Category ${cat} should have meaningful coverage`);
  }

  // Honest state guard: without live PHP/Laravel/DB execution in this container, zero features may claim Backend verified or Production tested
  assert.equal(summary.byClassification['Backend verified'], 0);
  assert.equal(summary.byClassification['Production tested'], 0);
  assert.ok(summary.byClassification['Theme UI implemented'] > 0);
  assert.ok(summary.byClassification['Paymenter integration pending'] > 0);
  assert.equal(
    summary.byClassification['Theme UI implemented'] +
      summary.byClassification['Paymenter integration pending'],
    336
  );
});

test('filterFeatures filters accurately by query, category, status, and classification', () => {
  const checkoutMatches = filterFeatures(FEATURE_REGISTRY, {
    query: 'checkout',
    category: 'Checkout',
  });
  assert.ok(checkoutMatches.length >= 1);
  assert.ok(checkoutMatches.every((f) => f.category === 'Checkout'));

  const implementedCount = filterFeatures(FEATURE_REGISTRY, { status: 'implemented' }).length;
  const summary = summarizeFeatureRegistry(FEATURE_REGISTRY);
  assert.equal(implementedCount, summary.byStatus.implemented);

  const pendingIntegration = filterFeatures(FEATURE_REGISTRY, {
    classification: 'Paymenter integration pending',
  });
  assert.equal(pendingIntegration.length, summary.byClassification['Paymenter integration pending']);
});
