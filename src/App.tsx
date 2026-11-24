import React, { useState } from 'react';
import type { PaymenterProduct } from './theme/paymenter/contracts';
import { PaymenterProvider, usePaymenter } from './theme/paymenter/PaymenterProvider';
import { PaperBackground } from './theme/sketch';
import {
  PageContainer,
  ScrollProgressBar,
  ThemeFooter,
  ThemeHeader,
  ToastProvider,
  type NavItem,
} from './theme/ui';
import { HomePage } from './components/storefront/HomePage';
import { CatalogPage } from './components/storefront/CatalogPage';
import { ProductDetailPage } from './components/storefront/ProductDetailPage';
import { CartDrawer } from './components/storefront/CartDrawer';
import { CartPage } from './components/storefront/CartPage';
import { CheckoutPage } from './components/storefront/CheckoutPage';
import { ThemeWorkbench } from './components/workbench/ThemeWorkbench';

type AppView = 'home' | 'catalog' | 'product' | 'cart' | 'checkout' | 'theme-lab';

const NAV_ITEMS: NavItem[] = [
  { id: 'home', label: 'Home' },
  { id: 'catalog', label: 'Catalog' },
  { id: 'cart', label: 'Cart' },
  { id: 'checkout', label: 'Checkout' },
  { id: 'theme-lab', label: 'Theme Lab' },
];

const PetalnodeShell: React.FC = () => {
  const { runtime } = usePaymenter();
  const [view, setView] = useState<AppView>('home');
  const [catalogCategorySlug, setCatalogCategorySlug] = useState<string>('all');
  const [selectedProduct, setSelectedProduct] = useState<PaymenterProduct | null>(null);
  const [cartDrawerOpen, setCartDrawerOpen] = useState(false);
  const [paperMode, setPaperMode] = useState<'plain' | 'grid' | 'notebook'>('plain');

  const itemCount = runtime.cart.summary?.itemCount ?? 0;

  const navigateTo = (nextView: AppView) => {
    setView(nextView);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const handleOpenCatalog = (slug = 'all') => {
    setCatalogCategorySlug(slug);
    navigateTo('catalog');
  };

  const handleOpenProduct = (product: PaymenterProduct) => {
    setSelectedProduct(product);
    navigateTo('product');
  };

  const activeNavId = view === 'product' ? 'catalog' : view;

  return (
    <PaperBackground variant={paperMode} showMarginRule>
      <ScrollProgressBar />

      <ThemeHeader
        brandName="Petalnode"
        navItems={NAV_ITEMS}
        activeNavId={activeNavId}
        onSelectNav={(id) => navigateTo(id as AppView)}
        primaryActionLabel={`Cart (${itemCount})`}
        onPrimaryAction={() => setCartDrawerOpen(true)}
      />

      <PageContainer>
        <div key={view} className="pn-page-enter">
          {view === 'home' && (
            <HomePage
              onOpenCatalog={handleOpenCatalog}
              onSelectProduct={handleOpenProduct}
              onOpenThemeLab={() => navigateTo('theme-lab')}
            />
          )}

          {view === 'catalog' && (
            <CatalogPage
              key={catalogCategorySlug}
              initialCategorySlug={catalogCategorySlug}
              onSelectProduct={handleOpenProduct}
              onOpenThemeLab={() => navigateTo('theme-lab')}
            />
          )}

          {view === 'product' && (
            <ProductDetailPage
              product={selectedProduct}
              onBackToCatalog={() => handleOpenCatalog('all')}
            />
          )}

          {view === 'cart' && (
            <CartPage
              onContinueShopping={() => handleOpenCatalog('all')}
              onProceedToCheckout={() => navigateTo('checkout')}
            />
          )}

          {view === 'checkout' && (
            <CheckoutPage
              onBackToCart={() => navigateTo('cart')}
              onBrowseCatalog={() => handleOpenCatalog('all')}
            />
          )}

          {view === 'theme-lab' && (
            <ThemeWorkbench paperMode={paperMode} onPaperModeChange={setPaperMode} />
          )}
        </div>
      </PageContainer>

      <ThemeFooter
        brandName="Petalnode"
        links={NAV_ITEMS}
        onSelectNav={(id) => navigateTo(id as AppView)}
      />

      <CartDrawer
        open={cartDrawerOpen}
        onClose={() => setCartDrawerOpen(false)}
        onOpenFullCart={() => navigateTo('cart')}
        onProceedToCheckout={() => navigateTo('checkout')}
        onBrowseCatalog={() => handleOpenCatalog('all')}
      />
    </PaperBackground>
  );
};

export default function App() {
  return (
    <PaymenterProvider>
      <ToastProvider>
        <PetalnodeShell />
      </ToastProvider>
    </PaymenterProvider>
  );
}
