export type FeatureCategory =
  | 'UI and UX'
  | 'Navigation'
  | 'Homepage'
  | 'Product catalog'
  | 'Product configuration'
  | 'Cart'
  | 'Checkout'
  | 'Customer dashboard'
  | 'Services'
  | 'Billing'
  | 'Invoices'
  | 'Payments'
  | 'Authentication'
  | 'Profile'
  | 'Support'
  | 'Notifications'
  | 'Knowledge base'
  | 'Search'
  | 'Accessibility'
  | 'Animations'
  | 'Performance'
  | 'Mobile responsiveness'
  | 'Security'
  | 'Admin compatibility';

export const FEATURE_CATEGORIES: FeatureCategory[] = [
  'UI and UX',
  'Navigation',
  'Homepage',
  'Product catalog',
  'Product configuration',
  'Cart',
  'Checkout',
  'Customer dashboard',
  'Services',
  'Billing',
  'Invoices',
  'Payments',
  'Authentication',
  'Profile',
  'Support',
  'Notifications',
  'Knowledge base',
  'Search',
  'Accessibility',
  'Animations',
  'Performance',
  'Mobile responsiveness',
  'Security',
  'Admin compatibility',
];

export type FeaturePriority = 'P0' | 'P1' | 'P2';
export type FeatureStatus = 'implemented' | 'foundation-ready' | 'planned';
export type FeatureTestingStatus = 'unit-tested' | 'browser-verified' | 'pending';
export type FeatureClassification =
  | 'Theme UI implemented'
  | 'Paymenter integration pending'
  | 'Backend verified'
  | 'Production tested';

export const FEATURE_CLASSIFICATIONS: FeatureClassification[] = [
  'Theme UI implemented',
  'Paymenter integration pending',
  'Backend verified',
  'Production tested',
];

export interface ThemeFeature {
  id: string;
  name: string;
  description: string;
  category: FeatureCategory;
  priority: FeaturePriority;
  status: FeatureStatus;
  classification: FeatureClassification;
  dependencies: string[];
  testingStatus: FeatureTestingStatus;
}

type CompactFeatureTuple = [
  name: string,
  description: string,
  priority: FeaturePriority,
  status: FeatureStatus,
  classification: FeatureClassification,
  dependencies: string[],
  testingStatus: FeatureTestingStatus,
];

function buildCategoryFeatures(
  category: FeatureCategory,
  code: string,
  items: CompactFeatureTuple[]
): ThemeFeature[] {
  return items.map(
    ([name, description, priority, status, classification, dependencies, testingStatus], index) => ({
      id: `PN-${code}-${String(index + 1).padStart(3, '0')}`,
      name,
      description,
      category,
      priority,
      status,
      classification,
      dependencies,
      testingStatus,
    })
  );
}

export const FEATURE_REGISTRY: ThemeFeature[] = [
  ...buildCategoryFeatures('UI and UX', 'UIX', [
    ['Sketchbook Paper Canvas', 'Warm #FBF6EE paper grain surface with optional graph and ruled notebook presets.', 'P0', 'implemented', 'Theme UI implemented', ['tokens.css'], 'browser-verified'],
    ['Centralized CSS Token System', 'Exposes all Petalnode colors, fonts, spacing, shadows, z-index, and motion variables on :root.', 'P0', 'implemented', 'Theme UI implemented', [], 'unit-tested'],
    ['RoughBox Deterministic Frames', 'Rough.js SVG borders with FNV-1a deterministic seeds and ResizeObserver layout sync.', 'P0', 'implemented', 'Theme UI implemented', ['roughjs', 'sketchGeometry'], 'browser-verified'],
    ['RoughUnderline Emphasis', 'Two-stroke hand-drawn underline accent for hero headlines and key terms.', 'P1', 'implemented', 'Theme UI implemented', ['roughjs'], 'browser-verified'],
    ['RoughCircle Callout Ring', 'Hand-drawn oval ink loop highlighting featured prices or selected billing cycles.', 'P1', 'implemented', 'Theme UI implemented', ['roughjs'], 'browser-verified'],
    ['WashiTape Sticker Strips', 'Translucent masking-tape corner accents in Blossom, Moss, Lantern, and Bark tones.', 'P1', 'implemented', 'Theme UI implemented', ['tokens.css'], 'browser-verified'],
    ['PolaroidFrame Showcase', 'Tactile photo/preview frame with rotated tape and Shantell Sans handwritten caption.', 'P1', 'implemented', 'Theme UI implemented', ['WashiTape'], 'browser-verified'],
    ['Marginalia Annotation Callouts', 'Handwritten Shantell Sans notes paired with curved ink arrows.', 'P1', 'implemented', 'Theme UI implemented', ['InkArrow'], 'browser-verified'],
    ['Rubber Stamp Seals', 'Dashed rotated stamps for invoice status, verification labels, and featured badges.', 'P1', 'implemented', 'Theme UI implemented', ['Pixelify Sans'], 'browser-verified'],
    ['Nodey Mascot State System', 'Original cozy paper-craft voxel companion with idle, happy, working, and alert expressions.', 'P0', 'implemented', 'Theme UI implemented', ['tokens.css'], 'browser-verified'],
    ['Sprout Mascot Growth Stages', 'Original botanical pot companion reflecting seedling, growing, blooming, and resting states.', 'P0', 'implemented', 'Theme UI implemented', ['tokens.css'], 'browser-verified'],
    ['Consistent Modal & Drawer Layering', 'Unified backdrop blur, Escape dismissal, and z-index hierarchy across dialogs and drawers.', 'P0', 'implemented', 'Theme UI implemented', ['tokens.css'], 'browser-verified'],
    ['Empty, Loading & Error Mascot States', 'Friendly illustrated states with actionable retry and guidance CTAs when backend data is absent.', 'P0', 'implemented', 'Theme UI implemented', ['NodeyMascot', 'SproutMascot'], 'browser-verified'],
  ]),

  ...buildCategoryFeatures('Navigation', 'NAV', [
    ['Three-Zone Sticky Header', 'Clean single-row header with wordmark, primary nav links, and cart drawer trigger.', 'P0', 'implemented', 'Theme UI implemented', ['Layout'], 'browser-verified'],
    ['Mobile Drawer Navigation', 'Accessible slide-down mobile navigation with 44px touch targets and active highlights.', 'P0', 'implemented', 'Theme UI implemented', ['ThemeHeader'], 'browser-verified'],
    ['SPA Route Transition Lifecycle Hook', 'Re-initializes Rough.js frames and GSAP contexts on livewire:navigated & morph.updated hooks in themes/petalnode/js/app.js.', 'P0', 'implemented', 'Theme UI implemented', ['themes/petalnode/js/app.js'], 'unit-tested'],
    ['Dynamic Breadcrumb Trail', 'Contextual page hierarchy trail in themes/petalnode/views/components/navigation/breadcrumb.blade.php.', 'P0', 'implemented', 'Theme UI implemented', ['breadcrumb.blade.php'], 'unit-tested'],
    ['Account Quick-Switch Menu', 'Dropdown menu wired to Navigation::getAccountDropdownLinks() and livewire:auth.logout in navigation/index.blade.php.', 'P0', 'implemented', 'Theme UI implemented', ['navigation/index.blade.php'], 'unit-tested'],
    ['Cart Item Counter Badge', 'Numeric badge and slide-over preview drawer driven by livewire:components.cart.', 'P1', 'implemented', 'Theme UI implemented', ['components/cart.blade.php'], 'unit-tested'],
    ['Currency Switcher Control', 'Preserves Paymenter multi-currency switcher via livewire:components.locale-switch.', 'P1', 'implemented', 'Theme UI implemented', ['locale-switch.blade.php'], 'unit-tested'],
    ['Locale & Language Selector', 'Accessible dropdown switching Paymenter translation locales cleanly inside navigation.', 'P2', 'implemented', 'Theme UI implemented', ['locale-switch.blade.php'], 'unit-tested'],
    ['Keyboard Skip-to-Content Link', 'Hidden focusable skip link jumping directly past the header to #main-content.', 'P0', 'implemented', 'Theme UI implemented', ['layouts/app.blade.php'], 'browser-verified'],
    ['Active Route Underline Marker', 'Clear visual Blossom highlight indicating the active storefront or portal link.', 'P1', 'implemented', 'Theme UI implemented', ['navigation/link.blade.php'], 'browser-verified'],
    ['Contextual Sub-Navigation Tabs', 'Segmented tab navigation for workbench inspection and catalog filters.', 'P1', 'implemented', 'Theme UI implemented', ['SegmentedFilter'], 'browser-verified'],
    ['Sticky Scroll Progress Hairline', 'Subtle 3px Blossom scroll progress bar pinned above the viewport in layouts/app.blade.php.', 'P2', 'implemented', 'Theme UI implemented', ['motion.js'], 'browser-verified'],
    ['Clean Editorial Footer Links', 'Structured multi-link footer routing cleanly across Paymenter links and theme paper presets.', 'P0', 'implemented', 'Theme UI implemented', ['navigation/footer.blade.php'], 'browser-verified'],
  ]),

  ...buildCategoryFeatures('Homepage', 'HOM', [
    ['Sketchbook Hero Split Layout', 'Editorial headline with RoughUnderline, primary CTA, and Nodey/Sprout companion showcase.', 'P0', 'implemented', 'Theme UI implemented', ['home.blade.php', 'rough-underline.blade.php'], 'browser-verified'],
    ['Announcement Ribbon Integration', 'Preserves Paymenter extension pages.home hook cleanly after the hero section.', 'P1', 'implemented', 'Theme UI implemented', ['home.blade.php'], 'unit-tested'],
    ['Featured Game Server Showcase', 'Presentation grid displaying real Paymenter categories or honest empty state when empty.', 'P0', 'implemented', 'Theme UI implemented', ['home.blade.php'], 'browser-verified'],
    ['Hardware & Node Craft Story', 'Polaroid-framed sketchbook craft section explaining real Paymenter billing flow without fake server metrics.', 'P1', 'implemented', 'Theme UI implemented', ['home.blade.php', 'polaroid-frame.blade.php'], 'browser-verified'],
    ['Trust & Uptime Proof Strip', 'Presentation strip reserved for verified backend metrics with zero hardcoded fake TPS or uptime numbers.', 'P1', 'foundation-ready', 'Paymenter integration pending', ['HomePage'], 'browser-verified'],
    ['Plan Comparison Preview Grid', 'Featured plans band showing live Paymenter spotlight products when returned by Home Livewire component.', 'P1', 'implemented', 'Theme UI implemented', ['home.blade.php', 'plan-card.blade.php'], 'browser-verified'],
    ['Customer Testimonials Scrapbook', 'Scrapbook layout slot awaiting verified CMS/customer entries with zero fabricated quotes.', 'P2', 'foundation-ready', 'Paymenter integration pending', ['WashiTape', 'HomePage'], 'browser-verified'],
    ['Interactive Location Ping Preview', 'Regional node preview displaying real $previewNodes telemetry when returned by panel API.', 'P2', 'implemented', 'Theme UI implemented', ['home.blade.php', 'network.blade.php'], 'browser-verified'],
    ['FAQ Sketch Accordion', 'Accessible Alpine disclosure accordion in sketch-accordion.blade.php covering Paymenter ordering.', 'P1', 'implemented', 'Theme UI implemented', ['sketch-accordion.blade.php'], 'browser-verified'],
    ['Instant Deploy Call-to-Action Band', 'Bottom RoughBox conversion band routing visitors cleanly to live categories or cart.', 'P1', 'implemented', 'Theme UI implemented', ['home.blade.php', 'rough-box.blade.php'], 'browser-verified'],
    ['Seasonal Blossom Theme Toggle', 'Paper surface preset switcher across Paper Grain, Sketch Grid, and Ruled Notebook styles.', 'P2', 'implemented', 'Theme UI implemented', ['layouts/app.blade.php', 'footer.blade.php'], 'browser-verified'],
    ['Knowledge Base Spotlight Links', 'Quick-link slots reserved for Paymenter Knowledge Base routes in Prompt 05.', 'P2', 'planned', 'Paymenter integration pending', ['KB Route'], 'pending'],
    ['Zero-Broken-Media Fallbacks', 'Guaranteed styled SVG/paper fallback containers if custom product or hero artwork is omitted.', 'P0', 'implemented', 'Theme UI implemented', ['home.blade.php', 'SketchIllustration'], 'browser-verified'],
  ]),

  ...buildCategoryFeatures('Product catalog', 'CAT', [
    ['Category Tree Sidebar & Filter', 'Sidebar category switcher built from Paymenter $categories in products/index.blade.php.', 'P0', 'implemented', 'Theme UI implemented', ['products/index.blade.php'], 'browser-verified'],
    ['Consistent 3-Column Product Grid', 'Uniform paper product cards with aligned pricing baselines and zero height mismatch.', 'P0', 'implemented', 'Theme UI implemented', ['plan-card.blade.php'], 'browser-verified'],
    ['Billing Cycle Price Preview', 'Displays backend-supplied formatted prices directly without client calculation.', 'P0', 'implemented', 'Theme UI implemented', ['plan-card.blade.php'], 'browser-verified'],
    ['Stock Availability Indicator', 'Honest out-of-stock / remaining stock stamps read from Paymenter $product->stock.', 'P0', 'implemented', 'Theme UI implemented', ['plan-card.blade.php', 'products/show.blade.php'], 'browser-verified'],
    ['Hardware Spec Breakdown Rows', 'Tabular display rendering Paymenter-supplied product descriptions and billing plans dynamically.', 'P1', 'implemented', 'Theme UI implemented', ['products/show.blade.php'], 'browser-verified'],
    ['Category Description Markdown Render', 'Safe typography styling for Paymenter category descriptions.', 'P1', 'implemented', 'Theme UI implemented', ['products/index.blade.php'], 'unit-tested'],
    ['Featured Plan RoughCircle Highlight', 'Hand-drawn RoughCircle highlight framing live product price on detail pages.', 'P1', 'implemented', 'Theme UI implemented', ['rough-circle.blade.php', 'products/show.blade.php'], 'browser-verified'],
    ['Product Tag & Game Type Filtering', 'Client-side instant name search and price/name sorting in products/index.blade.php bypassing upstream SQL sort bug.', 'P1', 'implemented', 'Theme UI implemented', ['products/index.blade.php'], 'browser-verified'],
    ['Empty Category Friendly Illustration', 'SproutMascot empty state shown whenever a category has zero visible plans.', 'P1', 'implemented', 'Theme UI implemented', ['empty-state.blade.php'], 'browser-verified'],
    ['Multi-Currency Tabular Alignment', 'Aligned price typography using tabular-nums and server-formatted currency strings.', 'P0', 'implemented', 'Theme UI implemented', ['app.css'], 'browser-verified'],
    ['Direct Configure Plan Entry', 'Single clear primary button per card entering products.show or products.checkout.', 'P0', 'implemented', 'Theme UI implemented', ['plan-card.blade.php'], 'browser-verified'],
    ['Subcategory Card Grid', 'Navigation cards for nested Paymenter $childCategories in products/index.blade.php.', 'P1', 'implemented', 'Theme UI implemented', ['products/index.blade.php'], 'unit-tested'],
    ['Setup Fee Transparency Note', 'Displays $priceObj->formatted->setup_fee cleanly alongside recurring plan price.', 'P0', 'implemented', 'Theme UI implemented', ['plan-card.blade.php', 'products/show.blade.php'], 'browser-verified'],
  ]),

  ...buildCategoryFeatures('Product configuration', 'CFG', [
    ['Backend Option Binding Preservation', 'Preserves wire:model.live="configOptions.*" and "checkoutConfig.*" in products/checkout.blade.php.', 'P0', 'implemented', 'Theme UI implemented', ['products/checkout.blade.php'], 'unit-tested'],
    ['Billing Cycle Selector Cards', 'Plan selector dropdown wired to wire:model.live="plan_id" with setup fee transparency.', 'P0', 'implemented', 'Theme UI implemented', ['products/checkout.blade.php'], 'browser-verified'],
    ['Custom Config Option Inputs', 'Dynamic rendering of select, slider, radio, checkbox, and text controls in form/configoption.blade.php.', 'P0', 'implemented', 'Theme UI implemented', ['configoption.blade.php'], 'browser-verified'],
    ['Option Choice Availability State', 'Disables unavailable Paymenter option choices clearly with explicit unavailable labels.', 'P1', 'implemented', 'Theme UI implemented', ['ProductDetailPage', 'pricing.ts'], 'unit-tested'],
    ['Sticky Order Summary Sheet', 'RoughBox sticky summary sheet displaying $plan, tax, total today, and recurring renewal.', 'P0', 'implemented', 'Theme UI implemented', ['products/checkout.blade.php', 'rough-box.blade.php'], 'browser-verified'],
    ['Real-Time Setup + Recurring Breakdown', 'Renders Livewire-computed $total and setup renewal line without client tax math.', 'P0', 'implemented', 'Theme UI implemented', ['products/checkout.blade.php'], 'unit-tested'],
    ['Validation Error Inline Feedback', 'Accessible validation alert when required options are empty or unavailable choices are selected.', 'P0', 'implemented', 'Theme UI implemented', ['form/input.blade.php', 'pricing.ts'], 'unit-tested'],
    ['Quantity Stepper Control', 'Accessible minus/plus stepper wired to wire:click="updateQuantity" obeying allow_quantity rules.', 'P1', 'implemented', 'Theme UI implemented', ['cart.blade.php'], 'browser-verified'],
    ['Game Extension Option Pre-Selection', 'Styled server extension option slots mapped dynamically via $this->getCheckoutConfig().', 'P1', 'implemented', 'Theme UI implemented', ['products/checkout.blade.php'], 'unit-tested'],
    ['Hostname & Subdomain Helper Hints', 'Inline helper hints and extension documentation links inside form/configoption.blade.php.', 'P1', 'implemented', 'Theme UI implemented', ['configoption.blade.php'], 'unit-tested'],
    ['Mobile Sticky Bottom Summary Bar', 'Responsive summary layout staying legible on narrow 320px–390px viewports.', 'P1', 'implemented', 'Theme UI implemented', ['products/checkout.blade.php'], 'browser-verified'],
    ['Add-to-Cart Action Delegation', 'Checkout action wired directly to Livewire wire:click="checkout" with loading indicator.', 'P0', 'implemented', 'Theme UI implemented', ['products/checkout.blade.php'], 'browser-verified'],
    ['Post-Add Cart Confirmation Toast', 'Notification banner store bridged to Livewire.on("notify") inside themes/petalnode/js/app.js.', 'P1', 'implemented', 'Theme UI implemented', ['notification.blade.php'], 'unit-tested'],
  ]),

  ...buildCategoryFeatures('Cart', 'CRT', [
    ['Slide-Over Cart Drawer', 'Quick-inspect cart drawer in components/cart.blade.php listing real Cart::items().', 'P1', 'implemented', 'Theme UI implemented', ['components/cart.blade.php'], 'browser-verified'],
    ['Full Cart Page Ledger View', 'Itemized ledger in cart.blade.php displaying items, config options, and backend totals.', 'P0', 'implemented', 'Theme UI implemented', ['cart.blade.php'], 'browser-verified'],
    ['Cart Item Quantity Update', 'Quantity increment/decrement controls wired to Livewire updateQuantity.', 'P0', 'implemented', 'Theme UI implemented', ['cart.blade.php'], 'unit-tested'],
    ['Remove Cart Item Confirmation', 'Accessible confirmation modal delegating deletion to $wire.removeProduct(id).', 'P0', 'implemented', 'Theme UI implemented', ['cart.blade.php', 'confirmation.blade.php'], 'browser-verified'],
    ['Coupon / Promo Code Input', 'Input + Apply button wired directly to wire:model="coupon" and wire:click="applyCoupon".', 'P0', 'implemented', 'Theme UI implemented', ['cart.blade.php'], 'browser-verified'],
    ['Applied Coupon Tag & Removal', 'Displays applied $coupon->code Moss tag and delegates removal to wire:click="removeCoupon".', 'P1', 'implemented', 'Theme UI implemented', ['cart.blade.php'], 'unit-tested'],
    ['Tax & VAT Estimate Line', 'Displays Settings::tax() label and backend-computed $total->format($total->tax) cleanly.', 'P0', 'implemented', 'Theme UI implemented', ['cart.blade.php'], 'unit-tested'],
    ['Account Credit Balance Preview', 'Reserved display slot for customer credit eligible for checkout deduction in Prompt 05.', 'P1', 'planned', 'Paymenter integration pending', ['User Credits'], 'pending'],
    ['Empty Cart Mascot Illustration', 'Cozy SproutMascot empty state shown in cart.blade.php when Cart::items() is empty.', 'P0', 'implemented', 'Theme UI implemented', ['empty-state.blade.php', 'cart.blade.php'], 'browser-verified'],
    ['Cart Option Summary Disclosure', 'Displays selected config_options cleanly per cart row.', 'P1', 'implemented', 'Theme UI implemented', ['cart.blade.php'], 'browser-verified'],
    ['Session Persistence Across Auth', 'Preserves server-side session cart across login or registration via Livewire redirect flow.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['Auth Route'], 'pending'],
    ['Coupon Rejection Inline Alert', 'Surfaces authentic Livewire notify alerts from Cart::applyCoupon exceptions.', 'P1', 'implemented', 'Theme UI implemented', ['notification.blade.php'], 'unit-tested'],
    ['Proceed to Checkout Primary CTA', 'Primary wire:click="checkout" CTA with wire:loading state.', 'P0', 'implemented', 'Theme UI implemented', ['cart.blade.php'], 'browser-verified'],
  ]),

  ...buildCategoryFeatures('Checkout', 'CHK', [
    ['Two-Column Checkout Ledger', 'Left 7-step presentation flow paired with right RoughBox summary reading backend totals.', 'P0', 'implemented', 'Theme UI implemented', ['CheckoutPage', 'RoughBox'], 'browser-verified'],
    ['Guest Account Creation / Login Gate', 'Inline segmented toggle for customer registration or sign-in intent during checkout Step 3.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['CheckoutPage', 'SegmentedFilter'], 'browser-verified'],
    ['Billing Address & Tax Fields', 'Accessible draft inputs for name, country, city, postal code, and optional company VAT.', 'P0', 'implemented', 'Theme UI implemented', ['SketchForms', 'CheckoutPage'], 'browser-verified'],
    ['Payment Gateway Selection Cards', 'Dynamic radio list rendering only Paymenter-injected active gateways or honest EmptyState.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['contracts.ts', 'CheckoutPage'], 'browser-verified'],
    ['Account Credit Toggle Option', 'Checkbox slot prepared for applying Paymenter account credit when supported by backend.', 'P1', 'planned', 'Paymenter integration pending', ['SketchCheckbox'], 'pending'],
    ['Terms of Service Agreement Check', 'Required ToS checkbox validated in checkout draft rules before submission.', 'P0', 'implemented', 'Theme UI implemented', ['SketchCheckbox', 'cartReducer'], 'unit-tested'],
    ['Real Backend Gateway Redirect', 'Delegates checkout submission via submitCheckout adapter; zero simulated orders or redirects.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['adapters.ts'], 'pending'],
    ['Double-Submission Lock', 'Submission lock and disabled guard preventing duplicate submit calls.', 'P0', 'implemented', 'Theme UI implemented', ['SketchButton', 'CheckoutPage'], 'browser-verified'],
    ['Checkout Error Banner', 'Accessible alert and per-field errors surfacing missing or invalid checkout draft fields.', 'P0', 'implemented', 'Theme UI implemented', ['validateCheckoutDraftStep', 'CheckoutPage'], 'unit-tested'],
    ['Zero-Total Credit Order Flow', 'Presentation flow prepared for backend-verified $0 invoices covered by credits or discounts.', 'P1', 'planned', 'Paymenter integration pending', ['Checkout Flow'], 'pending'],
    ['Order Confirmation Sketch Receipt', 'Confirmation receipt view reserved strictly for verified backend payment completion.', 'P0', 'planned', 'Paymenter integration pending', ['NodeyMascot', 'Stamp'], 'pending'],
    ['Saved Billing Details Prefill', 'Populates customer fields from verified Paymenter authenticated customer session.', 'P1', 'planned', 'Paymenter integration pending', ['Profile API'], 'pending'],
    ['Server Token & Rate-Limit Preservation', 'Ensures theme templates preserve native Paymenter request verification and throttling.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['compatibility.ts'], 'pending'],
  ]),

  ...buildCategoryFeatures('Customer dashboard', 'DSH', [
    ['Cozy Portal Welcome Header', 'Personalized greeting layout prepared for active Paymenter customer session.', 'P0', 'planned', 'Paymenter integration pending', ['NodeyMascot'], 'pending'],
    ['Service Summary KPI Strip', 'Tabular-nums counters prepared for Active Services, Due Invoices, and Open Tickets.', 'P0', 'planned', 'Paymenter integration pending', ['useCountUp'], 'pending'],
    ['Active Services Quick Grid', 'Card layout prepared for Paymenter customer servers with status indicators.', 'P0', 'planned', 'Paymenter integration pending', ['StatusIndicator'], 'pending'],
    ['Unpaid Invoice Attention Alert', 'SketchAlert component pattern ready for binding to real unpaid Paymenter invoices.', 'P0', 'implemented', 'Theme UI implemented', ['SketchAlert'], 'browser-verified'],
    ['Recent Support Tickets List', 'Table layout prepared for customer support tickets once Paymenter ticket data connects.', 'P1', 'planned', 'Paymenter integration pending', ['SketchTable'], 'pending'],
    ['Account Credit Balance Card', 'Displays verified Paymenter customer credit balance when enabled by server.', 'P1', 'planned', 'Paymenter integration pending', ['contracts.ts'], 'pending'],
    ['New User Onboarding Empty State', 'Friendly SproutMascot welcome guiding first-time users when zero services exist.', 'P1', 'implemented', 'Theme UI implemented', ['EmptyState'], 'browser-verified'],
    ['Quick Order & Ticket Actions', 'Shortcut controls routing to Catalog or New Support Ticket once portal is mounted.', 'P1', 'planned', 'Paymenter integration pending', ['SketchButton'], 'pending'],
    ['Portal Sidebar / Top-Tab Shell', 'Client-area navigation layout planned for Prompt 03 once Paymenter repo is inspected.', 'P0', 'planned', 'Paymenter integration pending', ['Layout'], 'pending'],
    ['Recent Activity Feed', 'Chronological activity list bound strictly to real backend customer events.', 'P2', 'planned', 'Paymenter integration pending', ['Timeline'], 'pending'],
    ['Email Verification Reminder Banner', 'Verification reminder banner wired to Paymenter customer email verification status.', 'P1', 'planned', 'Paymenter integration pending', ['Auth'], 'pending'],
    ['Responsive Mobile Portal Summary', 'Mobile single-column portal stack layout planned for Prompt 03.', 'P0', 'planned', 'Paymenter integration pending', ['Layout'], 'pending'],
    ['Live Server State Refresh Safety', 'Ensures DOM updates preserve Rough.js borders during Paymenter status refreshes.', 'P1', 'foundation-ready', 'Paymenter integration pending', ['compatibility.ts'], 'pending'],
  ]),

  ...buildCategoryFeatures('Services', 'SRV', [
    ['Services Directory Table & Filter', 'SketchTable & SegmentedFilter UI primitives ready for customer service data.', 'P0', 'implemented', 'Theme UI implemented', ['SketchTable', 'SegmentedFilter'], 'browser-verified'],
    ['Service Detail Overview Sheet', 'Polaroid/Sketchbook service view planned for Prompt 03 Paymenter service routes.', 'P0', 'planned', 'Paymenter integration pending', ['RoughBox'], 'pending'],
    ['Extension Action Toolbar', 'Preserves Paymenter server extension action controls without replacing backend handlers.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['compatibility.ts'], 'pending'],
    ['Service Upgrade / Downgrade Modal', 'Modal shell prepared for Paymenter server-calculated plan upgrade flows.', 'P1', 'planned', 'Paymenter integration pending', ['SketchModal'], 'pending'],
    ['Cancellation Request Flow', 'Accessible cancellation confirmation modal submitting directly to Paymenter backend.', 'P0', 'planned', 'Paymenter integration pending', ['SketchModal', 'SketchForms'], 'pending'],
    ['Pending Provisioning Progress View', 'Nodey working state UI primitive ready for pending server provisioning.', 'P1', 'implemented', 'Theme UI implemented', ['LoadingState'], 'browser-verified'],
    ['Suspended Service Notice', 'SketchAlert pattern ready for displaying backend suspension reason and pay action.', 'P0', 'implemented', 'Theme UI implemented', ['SketchAlert'], 'browser-verified'],
    ['Service Config Options Readonly List', 'Card layout prepared to list configured service options from Paymenter.', 'P1', 'planned', 'Paymenter integration pending', ['SketchCard'], 'pending'],
    ['Associated Invoices Tab', 'Filtered table slot displaying Paymenter invoices associated with a service.', 'P1', 'planned', 'Paymenter integration pending', ['SketchTable'], 'pending'],
    ['Copyable Server Address Control', 'Copy-action UI pattern paired with ToastProvider feedback.', 'P2', 'implemented', 'Theme UI implemented', ['ToastProvider'], 'browser-verified'],
    ['Renewal Date Countdown Display', 'Displays backend-supplied next due timestamp cleanly in tabular numerals.', 'P1', 'planned', 'Paymenter integration pending', ['contracts.ts'], 'pending'],
    ['Custom Service Label Rename', 'Inline input form enabled only when supported by the connected Paymenter backend.', 'P2', 'planned', 'Paymenter integration pending', ['SketchInput'], 'pending'],
    ['Addon Management Section', 'Displays active add-ons and available upgrade add-ons from Paymenter.', 'P2', 'planned', 'Paymenter integration pending', ['SketchCard'], 'pending'],
  ]),

  ...buildCategoryFeatures('Billing', 'BIL', [
    ['Billing Cycle Overview Cards', 'Card grid summarizing recurring commitments from Paymenter billing records.', 'P1', 'planned', 'Paymenter integration pending', ['SketchCard'], 'pending'],
    ['Add Account Credit Flow', 'Credit deposit form routed strictly through Paymenter backend gateways.', 'P1', 'planned', 'Paymenter integration pending', ['SketchForms'], 'pending'],
    ['Credit Transaction Ledger', 'Chronological ledger displaying verified credit deposits, deductions, and refunds.', 'P1', 'planned', 'Paymenter integration pending', ['SketchTable'], 'pending'],
    ['Saved Payment Methods Wrapper', 'Container styling for gateway-hosted saved payment tokens.', 'P1', 'planned', 'Paymenter integration pending', ['SketchCard'], 'pending'],
    ['Auto-Renewal Preference Toggle', 'Accessible checkbox preserving Paymenter billing agreement behavior.', 'P2', 'planned', 'Paymenter integration pending', ['SketchCheckbox'], 'pending'],
    ['Billing Contact Details Sync', 'Editable billing name, address, and tax ID persisted via Paymenter backend.', 'P1', 'planned', 'Paymenter integration pending', ['SketchForms'], 'pending'],
    ['Currency Display Consistency', 'Tabular-nums display formatting ready for server-formatted currency strings.', 'P0', 'implemented', 'Theme UI implemented', ['tokens.css'], 'browser-verified'],
    ['Tax Exemption / VAT Validation Slot', 'Input and server validation feedback slot for regional VAT IDs.', 'P2', 'planned', 'Paymenter integration pending', ['SketchInput'], 'pending'],
    ['Billing Summary Export View', 'Print-friendly paper layout for monthly expense summaries.', 'P2', 'planned', 'Paymenter integration pending', ['PaperBackground'], 'pending'],
    ['Pending Credit Clear Alert', 'Informs users when an async credit deposit awaits gateway webhook verification.', 'P1', 'planned', 'Paymenter integration pending', ['SketchAlert'], 'pending'],
    ['Refunded Item Indicator', 'Paired StatusIndicator displaying refunded state and backend timestamp.', 'P1', 'planned', 'Paymenter integration pending', ['StatusIndicator'], 'pending'],
    ['Minimum Deposit Validation', 'Validation aligned with Paymenter server-configured credit deposit bounds.', 'P1', 'planned', 'Paymenter integration pending', ['FormField'], 'pending'],
    ['Overdue Balance Warning Banner', 'SketchAlert warning banner primitive for unpaid invoice alerts.', 'P0', 'implemented', 'Theme UI implemented', ['SketchAlert'], 'browser-verified'],
  ]),

  ...buildCategoryFeatures('Invoices', 'INV', [
    ['Invoices Index Ledger', 'SketchTable & StatusIndicator UI primitives verified in UI Kit for invoice display.', 'P0', 'implemented', 'Theme UI implemented', ['SketchTable'], 'browser-verified'],
    ['Sketchbook Invoice Detail Sheet', 'Paper-crafted invoice sheet planned for Prompt 03 using Paymenter invoice data.', 'P0', 'planned', 'Paymenter integration pending', ['Stamp', 'SketchCard'], 'pending'],
    ['PAID Rubber Stamp Seal', 'Moss-green rotated Stamp primitive ready for settled Paymenter invoices.', 'P0', 'implemented', 'Theme UI implemented', ['Stamp'], 'browser-verified'],
    ['Unpaid Invoice Payment Selector', 'Gateway picker and pay action bound to Paymenter invoice payment endpoints.', 'P0', 'planned', 'Paymenter integration pending', ['SketchSelect', 'SketchButton'], 'pending'],
    ['PDF Invoice Download Action', 'Styled download trigger invoking Paymenter native PDF invoice download.', 'P0', 'planned', 'Paymenter integration pending', ['SketchButton'], 'pending'],
    ['Print-Optimized Invoice CSS', 'Clean @media print rules preserving legible Ink typography on paper.', 'P1', 'foundation-ready', 'Theme UI implemented', ['tokens.css'], 'pending'],
    ['Transaction History Subtable', 'Table displaying real Paymenter gateway transaction IDs and timestamps.', 'P1', 'planned', 'Paymenter integration pending', ['SketchTable'], 'pending'],
    ['Partial Credit Deduction Line', 'Line item displaying backend-computed credit deductions cleanly.', 'P1', 'planned', 'Paymenter integration pending', ['contracts.ts'], 'pending'],
    ['Invoice Overdue Highlighting', 'Pairs danger text, icon, and label in StatusIndicator without color-only cues.', 'P0', 'implemented', 'Theme UI implemented', ['StatusIndicator'], 'browser-verified'],
    ['Coupon Discount Line Item', 'Displays server-applied promo code and discount string in tabular alignment.', 'P1', 'planned', 'Paymenter integration pending', ['contracts.ts'], 'pending'],
    ['Invoice Pagination Controls', 'Accessible Pagination component ready for Paymenter paginated invoice lists.', 'P1', 'implemented', 'Theme UI implemented', ['Pagination'], 'browser-verified'],
    ['Direct Service Cross-Links', 'Navigation links connecting invoice line items to the related service.', 'P2', 'planned', 'Paymenter integration pending', ['Breadcrumbs'], 'pending'],
    ['Webhook Processing Pending State', 'Polite LoadingState notice UI while awaiting external gateway confirmation.', 'P1', 'implemented', 'Theme UI implemented', ['LoadingState'], 'browser-verified'],
  ]),

  ...buildCategoryFeatures('Payments', 'PAY', [
    ['Gateway Card Radio Group', 'Accessible radio group rendering real Paymenter payment extensions.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['CheckoutPage', 'contracts.ts'], 'browser-verified'],
    ['Gateway Embed Container Polish', 'Paper-matched border and focus ring container compatible with gateway embeds.', 'P0', 'planned', 'Paymenter integration pending', ['tokens.css'], 'pending'],
    ['External Gateway Redirect Handshake', 'Clear redirect state while handing off to external Paymenter gateway extensions.', 'P0', 'planned', 'Paymenter integration pending', ['SketchButton'], 'pending'],
    ['Real Payment Return Verification', 'Renders authentic Paymenter callback status (success, pending, cancelled, failed).', 'P0', 'foundation-ready', 'Paymenter integration pending', ['compatibility.ts'], 'pending'],
    ['Gateway Fee Transparency Row', 'Displays any Paymenter-supplied gateway surcharge before checkout confirmation.', 'P1', 'planned', 'Paymenter integration pending', ['contracts.ts'], 'pending'],
    ['Payment Declined Recovery Alert', 'ErrorState UI primitive ready for actionable gateway retry handling.', 'P0', 'implemented', 'Theme UI implemented', ['ErrorState'], 'browser-verified'],
    ['Webhook Async Wait Indicator', 'Guides user cleanly while Paymenter awaits payment webhook confirmation.', 'P1', 'foundation-ready', 'Paymenter integration pending', ['compatibility.ts'], 'pending'],
    ['One-Click Credit Payment Button', 'Button flow enabled when Paymenter confirms credit covers 100% of invoice.', 'P1', 'planned', 'Paymenter integration pending', ['SketchButton'], 'pending'],
    ['Gateway Icon Fallbacks', 'Crisp HandIcon fallbacks for card, bank, wallet, and credit gateways.', 'P1', 'planned', 'Paymenter integration pending', ['HandIcon'], 'pending'],
    ['Currency Conversion Note', 'Notifies user if a Paymenter gateway settles in a different configured currency.', 'P2', 'planned', 'Paymenter integration pending', ['SketchAlert'], 'pending'],
    ['Subscription Mandate Notice', 'Clear authorization notice when setting up recurring gateway mandates.', 'P2', 'planned', 'Paymenter integration pending', ['SketchCheckbox'], 'pending'],
    ['Zero Hardcoded Success Guard', 'Architecture rule ensuring no client script ever simulates payment completion.', 'P0', 'implemented', 'Theme UI implemented', ['compatibility.ts', 'adapters.ts'], 'unit-tested'],
    ['Accessible Gateway Error Focus', 'FormField accessible error announcements when gateway or field validation fails.', 'P0', 'implemented', 'Theme UI implemented', ['FormField'], 'browser-verified'],
  ]),

  ...buildCategoryFeatures('Authentication', 'AUT', [
    ['Sketchbook Auth Split Card', 'Welcoming login screen design planned for Prompt 03 against Paymenter auth routes.', 'P0', 'planned', 'Paymenter integration pending', ['NodeyMascot', 'SketchForms'], 'pending'],
    ['Registration Form Layout', 'Accessible registration form preserving Paymenter fields and validation rules.', 'P0', 'planned', 'Paymenter integration pending', ['SketchForms'], 'pending'],
    ['Password Recovery Flow', 'Forgot-password and reset-password layouts aligned with Paymenter auth controllers.', 'P0', 'planned', 'Paymenter integration pending', ['SketchForms'], 'pending'],
    ['2FA / TOTP Challenge Screen', 'Dedicated 6-digit tabular code input and recovery-code fallback view.', 'P0', 'planned', 'Paymenter integration pending', ['SketchInput'], 'pending'],
    ['OAuth Provider Buttons', 'Secondary buttons dynamically reflecting active Paymenter SSO extensions.', 'P1', 'planned', 'Paymenter integration pending', ['SketchButton'], 'pending'],
    ['Email Verification Notice Page', 'Friendly SproutMascot notice paired with Paymenter resend-verification action.', 'P1', 'planned', 'Paymenter integration pending', ['SproutMascot'], 'pending'],
    ['Remember Me Checkbox', 'Accessible SketchCheckbox primitive ready for Paymenter login forms.', 'P1', 'implemented', 'Theme UI implemented', ['SketchCheckbox'], 'browser-verified'],
    ['Show / Hide Password Toggle', 'Accessible password visibility toggle for auth forms.', 'P1', 'planned', 'Paymenter integration pending', ['SketchInput'], 'pending'],
    ['Captcha / Turnstile Mount Slot', 'Layout-stable container preserving third-party captcha widgets configured in Paymenter.', 'P0', 'planned', 'Paymenter integration pending', ['compatibility.ts'], 'pending'],
    ['Rate-Limit Throttle Alert', 'SketchAlert notice ready for Paymenter auth throttling feedback.', 'P0', 'implemented', 'Theme UI implemented', ['SketchAlert'], 'browser-verified'],
    ['Session Expired Recovery Prompt', 'SketchModal prompt guiding clean re-authentication.', 'P1', 'planned', 'Paymenter integration pending', ['SketchModal'], 'pending'],
    ['Password Confirmation Modal', 'Confirmation dialog styled for sensitive Paymenter security actions.', 'P1', 'planned', 'Paymenter integration pending', ['SketchModal'], 'pending'],
    ['Post-Login Intended Redirect', 'Preserves Paymenter server redirect flow back to checkout or invoice after login.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['compatibility.ts'], 'pending'],
  ]),

  ...buildCategoryFeatures('Profile', 'PRF', [
    ['Account Details Form', 'Form layout for updating verified customer name, email, and billing address.', 'P0', 'planned', 'Paymenter integration pending', ['SketchForms'], 'pending'],
    ['Password Change Section', 'Current password, new password, and confirmation fields with backend validation errors.', 'P0', 'planned', 'Paymenter integration pending', ['SketchForms'], 'pending'],
    ['Two-Factor Authentication Setup', 'QR code Polaroid frame, secret copy button, and recovery codes modal.', 'P0', 'planned', 'Paymenter integration pending', ['PolaroidFrame', 'SketchModal'], 'pending'],
    ['Recovery Codes Print & Copy', 'Monospace box with Copy All and Regenerate confirmation modal.', 'P1', 'planned', 'Paymenter integration pending', ['SketchModal'], 'pending'],
    ['Active Sessions List', 'Table showing authenticated browser sessions from Paymenter.', 'P1', 'planned', 'Paymenter integration pending', ['SketchTable'], 'pending'],
    ['API Key Management View', 'Token management view enabled when Paymenter client API keys exist.', 'P2', 'planned', 'Paymenter integration pending', ['SketchTable'], 'pending'],
    ['Notification Preferences Panel', 'Toggle controls for billing, service, and ticket email preferences.', 'P2', 'planned', 'Paymenter integration pending', ['SketchCheckbox'], 'pending'],
    ['Connected OAuth Accounts', 'Displays linked SSO accounts with Paymenter unlink controls.', 'P2', 'planned', 'Paymenter integration pending', ['SketchCard'], 'pending'],
    ['Profile Save Toast Feedback', 'ToastProvider confirmation UI ready for profile save responses.', 'P0', 'implemented', 'Theme UI implemented', ['ToastProvider'], 'browser-verified'],
    ['Avatar / Initials Sketch Badge', 'Ink-bordered initials circle badge for authenticated customer header.', 'P1', 'planned', 'Paymenter integration pending', ['RoughCircle'], 'pending'],
    ['Email Change Verification Alert', 'Notifies user when pending email change requires verification.', 'P1', 'planned', 'Paymenter integration pending', ['SketchAlert'], 'pending'],
    ['Timezone & Locale Preference', 'Select controls syncing display preferences supported by Paymenter.', 'P2', 'planned', 'Paymenter integration pending', ['SketchSelect'], 'pending'],
    ['Account Deletion Safeguard Modal', 'Explicit confirmation dialog if account self-deletion is enabled in Paymenter.', 'P2', 'planned', 'Paymenter integration pending', ['SketchModal'], 'pending'],
  ]),

  ...buildCategoryFeatures('Support', 'SUP', [
    ['Support Tickets Index Table', 'SketchTable & SegmentedFilter UI primitives ready for ticket lists.', 'P0', 'implemented', 'Theme UI implemented', ['SketchTable', 'SegmentedFilter'], 'browser-verified'],
    ['Create New Ticket Form', 'Department, priority, optional related service, and message fields mapped to Paymenter.', 'P0', 'planned', 'Paymenter integration pending', ['SketchForms'], 'pending'],
    ['Ticket Conversation Thread', 'Paper-card conversation bubbles separating customer and staff replies.', 'P0', 'planned', 'Paymenter integration pending', ['SketchCard'], 'pending'],
    ['Staff Reply Marker & Timestamp', 'Editorial badge highlighting verified support staff responses.', 'P1', 'planned', 'Paymenter integration pending', ['InlineBadge'], 'pending'],
    ['File & Screenshot Attachment Dropzone', 'Accessible file picker obeying Paymenter server upload limits.', 'P1', 'planned', 'Paymenter integration pending', ['SketchForms'], 'pending'],
    ['Close / Reopen Ticket Controls', 'Clear action controls submitting ticket status transitions to backend.', 'P1', 'planned', 'Paymenter integration pending', ['SketchButton'], 'pending'],
    ['Related Service Quick Card', 'Displays linked Paymenter service details beside the ticket thread.', 'P1', 'planned', 'Paymenter integration pending', ['SketchCard'], 'pending'],
    ['Ticket Priority Indicator', 'Paired icon + text priority display without color-only badges.', 'P1', 'planned', 'Paymenter integration pending', ['StatusIndicator'], 'pending'],
    ['Empty Ticket Inbox State', 'Friendly EmptyState primitive ready for accounts with zero tickets.', 'P0', 'implemented', 'Theme UI implemented', ['EmptyState'], 'browser-verified'],
    ['Department SLA Guidance Note', 'Contextual helper alert displaying configured department response notes.', 'P2', 'planned', 'Paymenter integration pending', ['SketchAlert'], 'pending'],
    ['Markdown Formatting Toolbar', 'Accessible quick-insert toolbar for ticket reply formatting.', 'P2', 'planned', 'Paymenter integration pending', ['SketchTextarea'], 'pending'],
    ['Ticket Search & Status Filter', 'SearchInput & SegmentedFilter UI primitives ready for ticket filtering.', 'P1', 'implemented', 'Theme UI implemented', ['SearchInput', 'SegmentedFilter'], 'browser-verified'],
    ['Reply Submit Loading Lock', 'SketchButton loading state primitive preventing duplicate submissions.', 'P0', 'implemented', 'Theme UI implemented', ['SketchButton'], 'browser-verified'],
  ]),

  ...buildCategoryFeatures('Notifications', 'NTF', [
    ['Toast Notification Queue', 'Accessible aria-live toast stack supporting success, warning, danger, and info tones.', 'P0', 'implemented', 'Theme UI implemented', ['ToastProvider'], 'browser-verified'],
    ['Server Event Toast Bridge', 'Connects Paymenter backend flash/event notifications to SketchToasts once repo is mounted.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['compatibility.ts'], 'pending'],
    ['Inline Contextual Alert Box', 'Reusable SketchAlert with semantic icon, bold title, and body copy.', 'P0', 'implemented', 'Theme UI implemented', ['SketchAlert'], 'browser-verified'],
    ['Auto-Dismiss Timer with Manual Close', '4.5s auto-dismiss paired with explicit accessible close button.', 'P0', 'implemented', 'Theme UI implemented', ['ToastProvider'], 'browser-verified'],
    ['Notification Center Drawer', 'Slide-over SketchDrawer primitive ready for future alert center bindings.', 'P2', 'implemented', 'Theme UI implemented', ['SketchDrawer'], 'browser-verified'],
    ['Unread Alert Counter', 'Numeric unread counter layout planned for authenticated portal header.', 'P2', 'planned', 'Paymenter integration pending', ['Layout'], 'pending'],
    ['Mark All as Read Action', 'Batch clear action wired once backend notification persistence is inspected.', 'P2', 'planned', 'Paymenter integration pending', ['SketchButton'], 'pending'],
    ['Maintenance Window Banner', 'Scheduled maintenance notice bound to real Paymenter announcements.', 'P1', 'planned', 'Paymenter integration pending', ['SketchAlert'], 'pending'],
    ['Payment Confirmation Toast', 'Toast feedback UI triggered only after confirmed Paymenter server settlement.', 'P1', 'foundation-ready', 'Paymenter integration pending', ['ToastProvider'], 'pending'],
    ['Validation Summary Alert', 'Top-of-form SketchAlert summarizing missing or invalid fields.', 'P1', 'implemented', 'Theme UI implemented', ['SketchAlert'], 'browser-verified'],
    ['Copy-to-Clipboard Feedback', 'ToastProvider feedback pattern for copied references.', 'P1', 'implemented', 'Theme UI implemented', ['ToastProvider'], 'browser-verified'],
    ['Reduced-Motion Toast Entrance', 'Respects prefers-reduced-motion by disabling slide/bounce animations.', 'P0', 'implemented', 'Theme UI implemented', ['tokens.css'], 'browser-verified'],
    ['High-Contrast Alert Borders', 'Ensures alert containers meet WCAG AA text and border contrast on paper.', 'P0', 'implemented', 'Theme UI implemented', ['getContrastRatio'], 'unit-tested'],
  ]),

  ...buildCategoryFeatures('Knowledge base', 'KWB', [
    ['Knowledge Base Home Grid', 'Categorized guide cards consuming Paymenter Knowledge Base entries.', 'P1', 'planned', 'Paymenter integration pending', ['SketchCard', 'HandIcon'], 'pending'],
    ['Article Editorial Typography', 'Comfortable reading measure using Fraunces headings and Nunito body prose.', 'P1', 'planned', 'Paymenter integration pending', ['tokens.css'], 'pending'],
    ['Code & Command Block Styling', 'Ink-framed monospace code snippets with copy control.', 'P1', 'planned', 'Paymenter integration pending', ['SketchButton'], 'pending'],
    ['Article Table of Contents', 'Sticky outline highlighting current reading section.', 'P2', 'planned', 'Paymenter integration pending', ['Layout'], 'pending'],
    ['Helpful Feedback Prompt', 'Simple article helpfulness control wired if supported by backend.', 'P2', 'planned', 'Paymenter integration pending', ['SketchButton'], 'pending'],
    ['Related Guides Footer', 'Displays related Knowledge Base guides returned by Paymenter.', 'P2', 'planned', 'Paymenter integration pending', ['SketchCard'], 'pending'],
    ['Announcement Post Archive', 'Chronological announcement cards bound to runtime.announcements.', 'P1', 'foundation-ready', 'Paymenter integration pending', ['HomePage', 'PaymenterProvider'], 'pending'],
    ['Announcement Detail Page', 'Full announcement reader view connected to Paymenter announcement routes.', 'P1', 'planned', 'Paymenter integration pending', ['Breadcrumbs'], 'pending'],
    ['Guide Search Results Highlight', 'SearchInput primitive ready for Knowledge Base filtering.', 'P1', 'implemented', 'Theme UI implemented', ['SearchInput'], 'browser-verified'],
    ['Empty Search Guides Fallback', 'EmptyState primitive guiding visitors back or to support.', 'P1', 'implemented', 'Theme UI implemented', ['EmptyState'], 'browser-verified'],
    ['Breadcrumb Category Path', 'Breadcrumbs primitive supporting multi-level guide paths.', 'P1', 'implemented', 'Theme UI implemented', ['Breadcrumbs'], 'browser-verified'],
    ['Callout Note Boxes', 'Tip, Warning, and Info callouts powered by SketchAlert.', 'P1', 'implemented', 'Theme UI implemented', ['SketchAlert'], 'browser-verified'],
    ['Image Lightbox Preview', 'SketchModal + PolaroidFrame preview pattern for documentation imagery.', 'P2', 'implemented', 'Theme UI implemented', ['SketchModal', 'PolaroidFrame'], 'browser-verified'],
  ]),

  ...buildCategoryFeatures('Search', 'SRC', [
    ['Reusable SearchInput Primitive', 'Accessible search input with leading icon, clear button, and focus border.', 'P0', 'implemented', 'Theme UI implemented', ['NavigationAndData'], 'browser-verified'],
    ['Catalog Plan Live Filter', 'Filters visible Paymenter products dynamically by name, spec, or description.', 'P1', 'foundation-ready', 'Paymenter integration pending', ['SearchInput', 'CatalogPage'], 'browser-verified'],
    ['Invoices & Services Search', 'SearchInput + SketchTable UI primitives ready for client portal filtering.', 'P0', 'implemented', 'Theme UI implemented', ['SearchInput', 'SketchTable'], 'browser-verified'],
    ['Feature Registry Live Search', 'Instant multi-field search across ID, name, description, and category.', 'P0', 'implemented', 'Theme UI implemented', ['featureRegistry'], 'browser-verified'],
    ['Debounced Input Handling', 'Prevents excessive backend query roundtrips while typing search queries.', 'P1', 'foundation-ready', 'Paymenter integration pending', ['Performance'], 'pending'],
    ['Clear Search Reset Button', 'One-click X button restoring full unfiltered results and returning focus.', 'P0', 'implemented', 'Theme UI implemented', ['SearchInput'], 'browser-verified'],
    ['Result Count Announcement', 'Live count readout with tabular-nums showing matched entries.', 'P1', 'implemented', 'Theme UI implemented', ['SearchInput'], 'browser-verified'],
    ['Zero-Results Friendly State', 'Displays SproutMascot empty state with Reset Filters CTA.', 'P0', 'implemented', 'Theme UI implemented', ['EmptyState'], 'browser-verified'],
    ['Keyboard Shortcut Focus (/)', 'Optional slash shortcut focusing search when not typing in inputs.', 'P2', 'planned', 'Theme UI implemented', ['Accessibility'], 'pending'],
    ['Combined Search + Category Filter', 'Composes text query cleanly with SegmentedFilter category tabs.', 'P0', 'implemented', 'Theme UI implemented', ['featureRegistry'], 'unit-tested'],
    ['Knowledge Base Instant Filter', 'Filters Paymenter Knowledge Base categories and articles once connected.', 'P1', 'planned', 'Paymenter integration pending', ['SearchInput'], 'pending'],
    ['Ticket History Search', 'Locates customer support threads via backend/UI query once mounted.', 'P1', 'planned', 'Paymenter integration pending', ['SearchInput'], 'pending'],
    ['URL Query Parameter Sync', 'Keeps search/filter state shareable on browser navigation once routes bind.', 'P2', 'planned', 'Paymenter integration pending', ['Router'], 'pending'],
  ]),

  ...buildCategoryFeatures('Accessibility', 'ACC', [
    ['WCAG AA Contrast Verification', 'Automated contrast validation on Ink/Paper, Blossom-700/White, and Moss-700/White pairs.', 'P0', 'implemented', 'Theme UI implemented', ['getContrastRatio'], 'unit-tested'],
    ['Semantic Landmark Regions', 'Proper header, nav, main, footer, and dialog landmarks across all views.', 'P0', 'implemented', 'Theme UI implemented', ['Layout'], 'browser-verified'],
    ['Visible Focus-Visible Rings', 'Distinct 2px Blossom-600 focus outlines on every interactive control.', 'P0', 'implemented', 'Theme UI implemented', ['tokens.css'], 'browser-verified'],
    ['40px+ Touch Target Floor', 'All buttons, tabs, pagination, and icon triggers meet >= 40px target sizing.', 'P0', 'implemented', 'Theme UI implemented', ['SketchButton'], 'browser-verified'],
    ['Reduced-Motion Media Support', 'Global CSS and GSAP matchMedia disabling motion when prefers-reduced-motion is active.', 'P0', 'implemented', 'Theme UI implemented', ['motionPresets'], 'browser-verified'],
    ['Native Label Association', 'Every input, select, and textarea pairs programmatically with a visible <label>.', 'P0', 'implemented', 'Theme UI implemented', ['SketchForms'], 'browser-verified'],
    ['Keyboard Escape Dismissal', 'Modals, drawers, and dropdown menus close reliably on Escape key press.', 'P0', 'implemented', 'Theme UI implemented', ['SketchOverlay'], 'browser-verified'],
    ['Non-Color Status Signaling', 'StatusIndicator always pairs semantic color with icon and explicit text label.', 'P0', 'implemented', 'Theme UI implemented', ['StatusIndicator'], 'browser-verified'],
    ['ARIA Live Toast Announcements', 'Screen-reader polite live region for dynamic toast notifications.', 'P0', 'implemented', 'Theme UI implemented', ['ToastProvider'], 'browser-verified'],
    ['Accessible Table Headers & Sort State', 'Semantic thead/th scope and screen-reader sort direction labels.', 'P1', 'implemented', 'Theme UI implemented', ['SketchTable'], 'browser-verified'],
    ['Decorative SVG Aria-Hidden', 'Purely ornamental Rough.js borders, ink arrows, and tape marked aria-hidden="true".', 'P0', 'implemented', 'Theme UI implemented', ['RoughBox'], 'browser-verified'],
    ['Tablist & Tab ARIA Roles', 'SegmentedFilter uses role="tablist", role="tab", and aria-selected accurately.', 'P1', 'implemented', 'Theme UI implemented', ['SegmentedFilter'], 'browser-verified'],
    ['Balanced Headline Wrapping', 'Applies text-wrap: balance to headings to improve scan readability.', 'P1', 'implemented', 'Theme UI implemented', ['index.css'], 'browser-verified'],
  ]),

  ...buildCategoryFeatures('Animations', 'ANM', [
    ['Centralized GSAP Context Registry', 'Manages GSAP + ScrollTrigger registration with guaranteed revert() cleanup.', 'P0', 'implemented', 'Theme UI implemented', ['gsap'], 'browser-verified'],
    ['Native SVG Stroke Draw Reveals', 'DrawSVG-style stroke-dashoffset reveals using SVG getTotalLength() without paid plugins.', 'P0', 'implemented', 'Theme UI implemented', ['animateSketchPaths'], 'browser-verified'],
    ['ScrollTrigger Staggered Entrances', 'Subtle 16px upward paper reveal firing once per element at 90% viewport.', 'P0', 'implemented', 'Theme UI implemented', ['createScrollRevealContext'], 'browser-verified'],
    ['Tactile Button Press Response', 'Sub-200ms hover shadow and 1px press settle using compositor properties.', 'P0', 'implemented', 'Theme UI implemented', ['SketchButton'], 'browser-verified'],
    ['Interactive Card Paper Tilt', 'Pointer-reactive 2.5deg perspective tilt on desktop with automatic pointerleave reset.', 'P1', 'implemented', 'Theme UI implemented', ['useCardTilt'], 'browser-verified'],
    ['Tabular Number Count-Up', 'Smooth numeric counter animation for verified workbench and portal counts.', 'P1', 'implemented', 'Theme UI implemented', ['useCountUp'], 'browser-verified'],
    ['Mascot Ambient Float & Sway', 'Gentle CSS keyframe breathing on Nodey and leaf sway on Sprout.', 'P1', 'implemented', 'Theme UI implemented', ['index.css'], 'browser-verified'],
    ['Scroll Progress Hairline Tracker', 'RAF-throttled scroll progress indicator scaling cleanly via transform: scaleX().', 'P1', 'implemented', 'Theme UI implemented', ['useScrollProgress'], 'browser-verified'],
    ['Washi Sticker Hover Straighten', 'PolaroidFrames gently level from tilted rotation on pointer hover.', 'P1', 'implemented', 'Theme UI implemented', ['PolaroidFrame'], 'browser-verified'],
    ['Modal & Drawer Entrance Curves', 'Fast 160-260ms transitions using settle easing without blocking form focus.', 'P1', 'implemented', 'Theme UI implemented', ['tokens.css'], 'browser-verified'],
    ['Dropdown Menu Expansion', 'Crisp chevron rotation and menu reveal with instant keyboard responsiveness.', 'P1', 'implemented', 'Theme UI implemented', ['SketchDropdown'], 'browser-verified'],
    ['Loading Spinner & Skeleton Shimmer', 'Consistent loading feedback UI while external backend requests execute.', 'P0', 'implemented', 'Theme UI implemented', ['FeedbackStates'], 'browser-verified'],
    ['Checkout Zero-Interference Guard', 'Never delays or blocks checkout button clicks, form typing, or route links.', 'P0', 'implemented', 'Theme UI implemented', ['compatibility.ts'], 'browser-verified'],
  ]),

  ...buildCategoryFeatures('Performance', 'PRF2', [
    ['Font Display Swap Pipeline', 'All 5 Google Fonts load with font-display: swap and preconnect hints.', 'P0', 'implemented', 'Theme UI implemented', ['index.html'], 'browser-verified'],
    ['Zero Layout-Shift Rough Borders', 'Absolute SVG overlay prevents Rough.js frames from shifting text flow.', 'P0', 'implemented', 'Theme UI implemented', ['RoughBox'], 'browser-verified'],
    ['RAF-Debounced ResizeObservers', 'Throttles Rough.js SVG redraws to animation frames during window resize.', 'P0', 'implemented', 'Theme UI implemented', ['RoughBox'], 'browser-verified'],
    ['Compositor-Only Motion Budget', 'Animates transform and opacity exclusively; avoids width/height/margin animations.', 'P0', 'implemented', 'Theme UI implemented', ['motionPresets'], 'browser-verified'],
    ['GSAP Context Teardown', 'Reverts ScrollTriggers and kills active tweens on component unmount.', 'P0', 'implemented', 'Theme UI implemented', ['hooks.ts'], 'browser-verified'],
    ['Lazy-Loaded Heavy Showcase Panels', 'Supports dynamic Vite code-splitting for secondary workbench views.', 'P1', 'foundation-ready', 'Theme UI implemented', ['Vite'], 'pending'],
    ['SVG Asset Inline Optimization', 'Lightweight hand-crafted mascot and icon SVGs under 3KB each with zero network bloat.', 'P0', 'implemented', 'Theme UI implemented', ['NodeyMascot'], 'browser-verified'],
    ['Tree-Shakeable Theme Barrels', 'Modular exports under src/theme/sketch and src/theme/ui for minimal bundle size.', 'P0', 'implemented', 'Theme UI implemented', ['index.ts'], 'unit-tested'],
    ['Passive Scroll Event Listeners', 'Registers scroll listeners with { passive: true } to preserve 60fps scrolling.', 'P0', 'implemented', 'Theme UI implemented', ['hooks.ts'], 'browser-verified'],
    ['Single-Elevation Shadow Budget', 'Avoids heavy multi-layer blur filters on large scrolling grids.', 'P1', 'implemented', 'Theme UI implemented', ['SketchCard'], 'browser-verified'],
    ['Server-Side Rendering Safe Guards', 'Guards window/matchMedia/document checks for SSR hydration safety.', 'P0', 'implemented', 'Theme UI implemented', ['motionPresets'], 'unit-tested'],
    ['Minimal Dependency Footprint', 'Only gsap and roughjs added beyond core React/Tailwind/Lucide runtime.', 'P0', 'implemented', 'Theme UI implemented', ['package.json'], 'unit-tested'],
    ['Deterministic Stroke Caching', 'FNV-1a seed stability avoids unnecessary SVG regeneration across unchanged renders.', 'P1', 'implemented', 'Theme UI implemented', ['hashSeed'], 'unit-tested'],
  ]),

  ...buildCategoryFeatures('Mobile responsiveness', 'MOB', [
    ['Mobile-First Responsive Grid', 'Reflows cleanly from 1 column (320px/390px) to 2-3 columns (768px-1920px).', 'P0', 'implemented', 'Theme UI implemented', ['Layout'], 'browser-verified'],
    ['Zero Horizontal Overflow Guard', 'Enforces overflow-x: hidden and responsive table wrappers across narrow viewports.', 'P0', 'implemented', 'Theme UI implemented', ['index.css', 'SketchTable'], 'browser-verified'],
    ['44px Mobile Tap Targets', 'Buttons, inputs, and mobile nav rows sized comfortably for thumb interaction.', 'P0', 'implemented', 'Theme UI implemented', ['SketchButton', 'ThemeHeader'], 'browser-verified'],
    ['Responsive Table Scroll Wrapper', 'Wide tables scroll smoothly inside bordered containers without page overflow.', 'P0', 'implemented', 'Theme UI implemented', ['SketchTable'], 'browser-verified'],
    ['15% Sticky Header Height Cap', 'Compact single-row mobile header leaving >=85% viewport for content.', 'P0', 'implemented', 'Theme UI implemented', ['ThemeHeader'], 'browser-verified'],
    ['Full-Height Slide-Over Drawers', 'Drawers adapt smoothly to mobile screen width with internal scroll regions.', 'P1', 'implemented', 'Theme UI implemented', ['SketchDrawer'], 'browser-verified'],
    ['Stacked Action Footer Controls', 'Modal and form footers wrap cleanly without clipping buttons on 320px/390px screens.', 'P1', 'implemented', 'Theme UI implemented', ['SketchModal'], 'browser-verified'],
    ['Touch-Safe Card Tilt Suppression', 'Skips pointer-tilt transforms on touch devices to avoid sticky hover states.', 'P1', 'implemented', 'Theme UI implemented', ['useCardTilt'], 'browser-verified'],
    ['Fluid Hero Typography Clamp', 'Scales hero headline smoothly via clamp(2.25rem, 4.2vw, 3.5rem) without overflow.', 'P0', 'implemented', 'Theme UI implemented', ['tokens.css'], 'browser-verified'],
    ['Accessible Mobile Filter Wrapping', 'SegmentedFilter wraps tabs cleanly without squeezing labels.', 'P1', 'implemented', 'Theme UI implemented', ['SegmentedFilter'], 'browser-verified'],
    ['Mobile Checkout Order Toggle', 'Responsive checkout column stacking keeps form and summary reachable on mobile.', 'P1', 'implemented', 'Theme UI implemented', ['CheckoutPage'], 'browser-verified'],
    ['Safe-Area Bottom Toast Padding', 'Positions mobile toasts cleanly above bottom browser chrome.', 'P1', 'implemented', 'Theme UI implemented', ['ToastProvider'], 'browser-verified'],
    ['Single-Line Button Truncation', 'Prevents awkward button overflow on compact 320px mobile containers.', 'P0', 'implemented', 'Theme UI implemented', ['SketchButton'], 'browser-verified'],
  ]),

  ...buildCategoryFeatures('Security', 'SEC', [
    ['Zero Hardcoded Secrets Policy', 'No API keys, tokens, or payment secrets stored anywhere in theme client bundles.', 'P0', 'implemented', 'Theme UI implemented', ['compatibility.ts'], 'unit-tested'],
    ['Server Request Token Preservation', 'Theme templates will preserve Paymenter server request verification tokens once repo is inspected.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['compatibility.ts'], 'pending'],
    ['XSS-Safe React Bindings', 'Renders strings via escaped JSX bindings; avoids raw HTML injection.', 'P0', 'implemented', 'Theme UI implemented', ['React JSX'], 'browser-verified'],
    ['Strict Rel=Noopener External Links', 'Enforces rel="noopener noreferrer" on external documentation or panel links.', 'P1', 'foundation-ready', 'Theme UI implemented', ['Layout'], 'pending'],
    ['Real Backend Auth Gate Preservation', 'Never bypasses server authentication, email verification, or 2FA checks.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['compatibility.ts'], 'pending'],
    ['No Client-Side Price Trust', 'Zero client-side pricing, tax, or discount formulas; displays server-computed strings only.', 'P0', 'implemented', 'Theme UI implemented', ['contracts.ts', 'pricing.ts'], 'unit-tested'],
    ['Safe SVG Rendering', 'Uses programmatic Rough.js and static React JSX SVGs without untrusted SVG strings.', 'P0', 'implemented', 'Theme UI implemented', ['RoughBox'], 'browser-verified'],
    ['2FA Secret Masking & Modal Isolation', 'Protects sensitive TOTP secrets behind confirmation states in Prompt 03.', 'P1', 'planned', 'Paymenter integration pending', ['Profile'], 'pending'],
    ['Form Validation Without Silent Fallback', 'Surfaces explicit validation errors rather than substituting fake products or fallback values.', 'P0', 'implemented', 'Theme UI implemented', ['SketchForms', 'PaymenterProvider'], 'unit-tested'],
    ['Payment Gateway Redirect Integrity', 'Preserves authentic backend webhook/signature checks without mock success shortcuts.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['compatibility.ts'], 'pending'],
    ['CSP-Compatible Inline Style Restraint', 'Minimizes dynamic inline scripts; compatible with strict Content Security Policy.', 'P1', 'foundation-ready', 'Theme UI implemented', ['tokens.css'], 'pending'],
    ['Secure File Upload Extension Display', 'Displays allowed attachment MIME/size hints matching Paymenter server rules.', 'P1', 'planned', 'Paymenter integration pending', ['Support'], 'pending'],
    ['Authenticated Logout Method Preservation', 'Preserves authentic Paymenter sign-out route/method once repository is inspected.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['compatibility.ts'], 'pending'],
  ]),

  ...buildCategoryFeatures('Admin compatibility', 'ADM', [
    ['Theme Manifest Contract', 'Registers native theme metadata, HSL palette tokens, and storefront toggles in themes/petalnode/theme.php.', 'P0', 'implemented', 'Theme UI implemented', ['themes/petalnode/theme.php'], 'unit-tested'],
    ['Default Theme Fallback Inheritance', 'Inherits unoverridden portal/auth/invoice views cleanly from themes/default via qirolab/laravel-themer.', 'P0', 'implemented', 'Theme UI implemented', ['layouts/app.blade.php'], 'unit-tested'],
    ['Admin Theme Settings Bindings', 'Reads theme("home_page_text"), theme("small_images"), theme("direct_checkout"), and color HSL settings.', 'P0', 'implemented', 'Theme UI implemented', ['theme.php', 'colors.blade.php'], 'unit-tested'],
    ['Admin Panel Isolation', 'Presentation theme leaves Filament admin panel and backend routes untouched.', 'P0', 'implemented', 'Theme UI implemented', ['themes/petalnode'], 'unit-tested'],
    ['Extension View Hook Compatibility', 'Preserves {!! hook("head") !!}, hook("body"), hook("footer"), and hook("pages.home") slots cleanly.', 'P0', 'implemented', 'Theme UI implemented', ['layouts/app.blade.php', 'home.blade.php'], 'unit-tested'],
    ['Custom CSS Override Hook', 'Exposes :root CSS custom properties and dynamic layouts/colors.blade.php HSL overrides.', 'P1', 'implemented', 'Theme UI implemented', ['colors.blade.php', 'app.css'], 'unit-tested'],
    ['Vite Production Asset Build', 'Configures Paymenter Vite manifest output targeting public/petalnode/.', 'P0', 'implemented', 'Theme UI implemented', ['themes/petalnode/vite.config.js'], 'unit-tested'],
    ['Color Setting Overrides', 'Maps admin color settings dynamically into :root and .dark HSL custom properties.', 'P1', 'implemented', 'Theme UI implemented', ['colors.blade.php'], 'unit-tested'],
    ['Multi-Extension Gateway Support', 'Preserves extension checkoutConfig and billing gateway flows without hardcoding brands.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['products/checkout.blade.php'], 'browser-verified'],
    ['Server Panel Extension Styling', 'Restyling of service action toolbars scheduled for Prompt 05 portal phase.', 'P0', 'planned', 'Paymenter integration pending', ['Services'], 'pending'],
    ['Announcement & Legal Page Templates', 'Clean editorial prose containers ready for admin-authored Terms and Pages.', 'P1', 'foundation-ready', 'Paymenter integration pending', ['Editorial Prose'], 'pending'],
    ['Extracted Source Boundary Mapping', 'Maps all theme templates directly against extracted Paymenter 1.5.9 Livewire classes without modifying backend logic.', 'P0', 'implemented', 'Theme UI implemented', ['themes/petalnode/theme.php'], 'unit-tested'],
    ['Namespace-Safe Theme Partials', 'Namespaces all theme CSS variables and components under pn-/Petalnode to avoid collisions.', 'P0', 'implemented', 'Theme UI implemented', ['css/app.css'], 'browser-verified'],
  ]),

  // Storefront Presentation & Adapter Boundary Additions (PN-STF-001 to PN-STF-024)
  ...buildCategoryFeatures('Homepage', 'STF', [
    ['Generated Island Sketch Artwork', 'High-fidelity cozy watercolor-and-ink floating island illustration inside PolaroidFrame.', 'P0', 'implemented', 'Theme UI implemented', ['SketchIllustration'], 'browser-verified'],
    ['Animated Floating Petals Overlay', 'Reduced-motion safe SVG cherry blossom petals drifting across the hero showcase.', 'P1', 'implemented', 'Theme UI implemented', ['FloatingPetalsOverlay'], 'browser-verified'],
    ['Server Craft Workbench Illustration', 'Original ink & watercolor workbench illustration available for theme product & craft sections.', 'P1', 'implemented', 'Theme UI implemented', ['SketchIllustration'], 'browser-verified'],
    ['Network Lantern Globe Illustration', 'Vintage cartography globe with blossom thread lines for regional showcase slots.', 'P1', 'implemented', 'Theme UI implemented', ['SketchIllustration'], 'browser-verified'],
    ['Sketchbook Editorial Storefront Flow', 'Clean sketchbook presentation flow preserving hero, catalog preview, specs/craft, FAQ, and CTA without fake data.', 'P0', 'implemented', 'Theme UI implemented', ['HomePage'], 'browser-verified'],
    ['Catalog Plan Comparison Layout', 'Presentation structure prepared to compare Paymenter products once connected.', 'P1', 'foundation-ready', 'Paymenter integration pending', ['CatalogPage'], 'browser-verified'],
    ['Catalog Price & Featured Sorting', 'Sorts visible Paymenter products by sortOrder or numericPrice when data is injected.', 'P1', 'foundation-ready', 'Paymenter integration pending', ['CatalogPage', 'adapters.ts'], 'browser-verified'],
    ['Honest Unconnected Catalog Empty State', 'Displays SproutMascot EmptyState explaining missing Paymenter backend instead of fake demo plans.', 'P0', 'implemented', 'Theme UI implemented', ['CatalogPage', 'EmptyState'], 'browser-verified'],
    ['Product Details Dynamic Spec Sheet', 'Renders specs dynamically from injected PaymenterProduct without fabricated hardware claims.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['ProductDetailPage'], 'browser-verified'],
    ['Backend Announcements Presentation Slot', 'Renders Paymenter announcements dynamically or honest awaiting-backend notice.', 'P1', 'foundation-ready', 'Paymenter integration pending', ['HomePage', 'PaymenterProvider'], 'browser-verified'],
    ['Product FAQ Accordion Section', 'Renders Paymenter product FAQs dynamically via SketchAccordion when provided.', 'P1', 'foundation-ready', 'Paymenter integration pending', ['ProductDetailPage', 'SketchAccordion'], 'browser-verified'],
    ['Configurator Reset Action', 'Single-click reset restoring default Paymenter plan and available option selections.', 'P1', 'implemented', 'Theme UI implemented', ['ProductDetailPage', 'pricing.ts'], 'browser-verified'],
    ['Unavailable Option Disabled State', 'Explicitly disables unavailable Paymenter option choices and validates selection.', 'P0', 'implemented', 'Theme UI implemented', ['ProductDetailPage', 'validateProductSelection'], 'unit-tested'],
    ['Billing Plan Cycle Switcher', 'Renders Paymenter-supplied billing plans and formatted prices directly with zero client discount math.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['ProductDetailPage', 'contracts.ts'], 'browser-verified'],
    ['Zero Independent Cart Math Boundary', 'Removes duplicate client cart pricing/discount/tax reducers in favor of PaymenterCartSummary.', 'P0', 'implemented', 'Theme UI implemented', ['contracts.ts', 'CartPage'], 'unit-tested'],
    ['Cart Drawer Backend Delegation', 'Slide-over CartDrawer delegates quantity updates and item removal to PaymenterThemeActions.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['CartDrawer', 'adapters.ts'], 'browser-verified'],
    ['Full Cart Remove Confirmation Modal', 'Accessible SketchModal confirmation before invoking PaymenterThemeActions.removeCartItem.', 'P1', 'foundation-ready', 'Paymenter integration pending', ['CartPage', 'SketchModal'], 'browser-verified'],
    ['Backend Coupon Submission Field', 'Coupon input delegates promo validation exclusively to PaymenterThemeActions.applyCoupon.', 'P1', 'foundation-ready', 'Paymenter integration pending', ['CartPage', 'adapters.ts'], 'unit-tested'],
    ['7-Step Checkout Progress Bar', 'Visual step tracker across Review, Customer, Auth, Billing, Payment, Final Review, and Submission.', 'P0', 'implemented', 'Theme UI implemented', ['CheckoutPage'], 'browser-verified'],
    ['Per-Step Checkout Validation', 'Blocks checkout steps when backend is disconnected, cart is empty, or required fields fail.', 'P0', 'implemented', 'Theme UI implemented', ['validateCheckoutDraftStep', 'cartReducer.ts'], 'unit-tested'],
    ['Checkout Submission Adapter Boundary', 'Delegates Step 7 submission exclusively to PaymenterThemeActions.submitCheckout without simulated paid states.', 'P0', 'foundation-ready', 'Paymenter integration pending', ['adapters.ts', 'CheckoutPage'], 'browser-verified'],
    ['Paymenter Runtime Provider Boundary', 'Provides EMPTY_PAYMENTER_RUNTIME by default so production never falls back to test fixtures.', 'P0', 'implemented', 'Theme UI implemented', ['PaymenterProvider.tsx'], 'unit-tested'],
    ['320px–1920px Responsive Storefront', 'Verified fluid layout across small mobile (320px) through ultrawide desktop (1920px).', 'P0', 'implemented', 'Theme UI implemented', ['Layout', 'HomePage'], 'browser-verified'],
    ['Preserved Theme Lab & Registry Route', 'Keeps Overview, Tokens, Sketch Lab, UI Kit, and 336-feature Registry accessible via Theme Lab nav.', 'P0', 'implemented', 'Theme UI implemented', ['ThemeWorkbench'], 'browser-verified'],
  ]),
];

export interface RegistrySummary {
  total: number;
  byCategory: Record<FeatureCategory, number>;
  byStatus: Record<FeatureStatus, number>;
  byPriority: Record<FeaturePriority, number>;
  byClassification: Record<FeatureClassification, number>;
}

export function summarizeFeatureRegistry(features: ThemeFeature[]): RegistrySummary {
  const byCategory = {} as Record<FeatureCategory, number>;
  for (const cat of FEATURE_CATEGORIES) {
    byCategory[cat] = 0;
  }

  const byStatus: Record<FeatureStatus, number> = {
    implemented: 0,
    'foundation-ready': 0,
    planned: 0,
  };

  const byPriority: Record<FeaturePriority, number> = {
    P0: 0,
    P1: 0,
    P2: 0,
  };

  const byClassification: Record<FeatureClassification, number> = {
    'Theme UI implemented': 0,
    'Paymenter integration pending': 0,
    'Backend verified': 0,
    'Production tested': 0,
  };

  for (const f of features) {
    byCategory[f.category] = (byCategory[f.category] ?? 0) + 1;
    byStatus[f.status] += 1;
    byPriority[f.priority] += 1;
    byClassification[f.classification] += 1;
  }

  return {
    total: features.length,
    byCategory,
    byStatus,
    byPriority,
    byClassification,
  };
}

export function filterFeatures(
  features: ThemeFeature[],
  filters: {
    query?: string;
    category?: FeatureCategory | 'ALL';
    status?: FeatureStatus | 'ALL';
    priority?: FeaturePriority | 'ALL';
    classification?: FeatureClassification | 'ALL';
  }
): ThemeFeature[] {
  const q = (filters.query ?? '').trim().toLowerCase();
  return features.filter((item) => {
    if (filters.category && filters.category !== 'ALL' && item.category !== filters.category) {
      return false;
    }
    if (filters.status && filters.status !== 'ALL' && item.status !== filters.status) {
      return false;
    }
    if (filters.priority && filters.priority !== 'ALL' && item.priority !== filters.priority) {
      return false;
    }
    if (
      filters.classification &&
      filters.classification !== 'ALL' &&
      item.classification !== filters.classification
    ) {
      return false;
    }
    if (!q) return true;
    return (
      item.id.toLowerCase().includes(q) ||
      item.name.toLowerCase().includes(q) ||
      item.description.toLowerCase().includes(q) ||
      item.category.toLowerCase().includes(q) ||
      item.classification.toLowerCase().includes(q)
    );
  });
}
