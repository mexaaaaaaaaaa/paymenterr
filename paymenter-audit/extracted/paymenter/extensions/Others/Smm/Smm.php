<?php

namespace Paymenter\Extensions\Others\Smm;

use App\Attributes\ExtensionMeta;
use App\Classes\Extension\Extension;
use App\Events\Invoice\Paid as InvoicePaid;
use App\Helpers\ExtensionHelper;
use App\Models\Extension as ExtensionModel;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Paymenter\Extensions\Others\Smm\Http\Controllers\WebhookController;
use Paymenter\Extensions\Others\Smm\Jobs\SubmitSmmOrderJob;
use Paymenter\Extensions\Others\Smm\Listeners\SubmitOrderOnInvoicePaid;
use Paymenter\Extensions\Others\Smm\Models\SmmOrder;
use Paymenter\Extensions\Others\Smm\Models\SmmProvider;

#[ExtensionMeta(
    name: 'SMM Hub',
    description: 'Social Media Marketplace powered by EasySMMPanel, integrated into Paymenter.',
    version: '1.0.0',
    author: 'ZyperNodes',
    url: 'https://zypernodes.fun',
    icon: 'heroicon-o-shopping-bag',
)]
class Smm extends Extension
{
    public function getName(): string
    {
        return 'SMM Hub';
    }

    public function getDescription(): string
    {
        return 'Social Media Marketplace powered by EasySMMPanel.';
    }

    public function getVersion(): string
    {
        return '1.0.0';
    }

    public function getAuthor(): string
    {
        return 'ZyperNodes';
    }

    public function getConfig($values = [])
    {
        return [
            [
                'name' => 'provider_api_key',
                'label' => 'EasySMMPanel API Key',
                'type' => 'text',
                'encrypted' => true,
                'placeholder' => 'Enter your EasySMMPanel API key',
                'description' => 'Your EasySMMPanel API v2 key. Stored encrypted.',
                'required' => true,
            ],
            [
                'name' => 'provider_api_endpoint',
                'label' => 'API Endpoint',
                'type' => 'text',
                'placeholder' => 'https://easysmmpanel.com/api/v2',
                'description' => 'EasySMMPanel API v2 endpoint.',
                'required' => true,
                'default' => 'https://easysmmpanel.com/api/v2',
            ],
            [
                'name' => 'default_currency',
                'label' => 'Default Customer Currency',
                'type' => 'text',
                'placeholder' => 'INR',
                'description' => 'Primary customer-facing currency (e.g. INR).',
                'required' => true,
                'default' => 'INR',
            ],
            [
                'name' => 'global_markup_percent',
                'label' => 'Global Markup (%)',
                'type' => 'number',
                'suffix' => '%',
                'description' => 'Default percentage markup applied to provider cost.',
                'required' => true,
                'default' => '50',
                'validation' => 'numeric|min:0|max:1000',
            ],
            [
                'name' => 'exchange_rate_buffer',
                'label' => 'Exchange Rate Buffer (%)',
                'type' => 'number',
                'suffix' => '%',
                'description' => 'Buffer applied on top of the exchange rate for currency conversion.',
                'required' => true,
                'default' => '2',
                'validation' => 'numeric|min:0|max:100',
            ],
            [
                'name' => 'sync_interval_minutes',
                'label' => 'Service Sync Interval (minutes)',
                'type' => 'number',
                'placeholder' => '360',
                'description' => 'How often services are synchronized from the provider.',
                'required' => true,
                'default' => '360',
                'validation' => 'integer|min:30',
            ],
            [
                'name' => 'maintenance_mode',
                'label' => 'Maintenance Mode',
                'type' => 'checkbox',
                'description' => 'Disable the SMM storefront when enabled.',
                'required' => false,
            ],
        ];
    }

    public function installed(): void
    {
        ExtensionHelper::runMigrations('extensions/Others/Smm/database/migrations');
        $this->ensureDefaultProvider();
    }

    public function uninstalled(): void
    {
        ExtensionHelper::rollbackMigrations('extensions/Others/Smm/database/migrations');
    }

    public function upgraded($oldVersion = null): void
    {
        ExtensionHelper::runMigrations('extensions/Others/Smm/database/migrations');
    }

    public function boot(): void
    {
        require __DIR__ . '/routes/web.php';

        View::addNamespace('smm', __DIR__ . '/resources/views');
        Lang::addNamespace('smm', __DIR__ . '/resources/lang');

        // Livewire components
        Livewire::component('smm.category', \Paymenter\Extensions\Others\Smm\Livewire\Storefront\Category::class);
        Livewire::component('smm.service', \Paymenter\Extensions\Others\Smm\Livewire\Storefront\Service::class);
        Livewire::component('smm.cart', \Paymenter\Extensions\Others\Smm\Livewire\Storefront\Cart::class);
        Livewire::component('smm.checkout', \Paymenter\Extensions\Others\Smm\Livewire\Storefront\Checkout::class);
        Livewire::component('smm.customer-orders', \Paymenter\Extensions\Others\Smm\Livewire\Customer\Orders::class);
        Livewire::component('smm.customer-order-show', \Paymenter\Extensions\Others\Smm\Livewire\Customer\OrderShow::class);

        // Policies
        Gate::policy(SmmOrder::class, \Paymenter\Extensions\Others\Smm\Policies\SmmOrderPolicy::class);

        // Invoice paid hook: submit SMM orders
        Event::listen(InvoicePaid::class, SubmitOrderOnInvoicePaid::class);

        // Navigation: customer account
        Event::listen('navigation.account', function () {
            return [
                'name' => __('smm::messages.smm_orders'),
                'route' => 'smm.customer.orders',
                'priority' => 20,
            ];
        });

        // Admin permissions
        Event::listen('permissions', function () {
            return [
                'admin.smm.view' => 'View SMM Hub',
                'admin.smm.services' => 'Manage SMM Services',
                'admin.smm.orders' => 'Manage SMM Orders',
                'admin.smm.providers' => 'Manage SMM Providers',
                'admin.smm.settings' => 'Manage SMM Settings',
            ];
        });
    }

    protected function ensureDefaultProvider(): void
    {
        try {
            $provider = $this->config('provider_api_key') ? SmmProvider::where('code', 'easysmmpanel')->first() : null;
            if (!$provider) {
                SmmProvider::create([
                    'name' => 'EasySMMPanel',
                    'code' => 'easysmmpanel',
                    'api_endpoint' => $this->config('provider_api_endpoint') ?: 'https://easysmmpanel.com/api/v2',
                    'api_key' => $this->config('provider_api_key') ?: '',
                    'is_active' => true,
                    'is_default' => true,
                    'balance' => null,
                ]);
            }
        } catch (\Throwable $e) {
            // Table may not exist yet during early boot
        }
    }
}
