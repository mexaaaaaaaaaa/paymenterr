<?php

namespace Paymenter\Extensions\Others\Smm\Livewire\Storefront;

use App\Livewire\Component;
use Paymenter\Extensions\Others\Smm\Models\SmmService;
use Paymenter\Extensions\Others\Smm\Services\SmmCart;
use Paymenter\Extensions\Others\Smm\Services\PricingEngine;
use Paymenter\Extensions\Others\Smm\Services\Math;

class Service extends Component
{
    public $slug;

    public $targetUrl = '';
    public $quantity = 1;
    public $comment = '';
    public $dripFeed = false;
    public $runs = 1;
    public $interval = 60;

    public $service;
    public $unitPrice = '0';
    public $total = '0';
    public $currency = 'INR';

    public $loading = false;
    public $added = false;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->service = SmmService::where('slug', $slug)
            ->where('is_enabled', true)
            ->where('is_discontinued', false)
            ->firstOrFail();

        $this->currency = PricingEngine::defaultCurrency();
        $this->quantity = (int) ($this->service->min_quantity ?? 1);
        $this->recalculate();
    }

    public function updatedQuantity()
    {
        $this->recalculate();
    }

    public function updatedTargetUrl()
    {
        $this->added = false;
    }

    protected function recalculate(): void
    {
        if (!$this->service) {
            return;
        }
        $qty = max(1, (int) $this->quantity);
        $this->quantity = $qty;

        $min = $this->service->min_quantity;
        $max = $this->service->max_quantity;
        if ($min !== null && $qty < (int) $min) {
            $this->quantity = (int) $min;
            $qty = (int) $min;
        }
        if ($max !== null && $qty > (int) $max) {
            $this->quantity = (int) $max;
            $qty = (int) $max;
        }

        $pricing = PricingEngine::calculateTotal($this->service, $qty, $this->currency);
        $this->unitPrice = $pricing['unit_price'];
        $this->total = $pricing['total'];
    }

    public function addToCart()
    {
        if (!$this->service) {
            return;
        }

        $min = $this->service->min_quantity;
        $max = $this->service->max_quantity;
        $qty = (int) $this->quantity;
        if ($min !== null && $qty < (int) $min) {
            $this->notify(__('smm::messages.quantity_too_low', ['min' => $min]), 'error');
            return;
        }
        if ($max !== null && $qty > (int) $max) {
            $this->notify(__('smm::messages.quantity_too_high', ['max' => $max]), 'error');
            return;
        }

        if (!filter_var($this->targetUrl, FILTER_VALIDATE_URL) && $this->targetUrl !== '') {
            $this->notify(__('smm::messages.invalid_url'), 'error');
            return;
        }

        $params = [];
        if ($this->comment !== '') {
            $params['comment'] = $this->comment;
        }
        if ($this->service->drip_feed) {
            $params['drip_feed'] = $this->dripFeed;
            $params['runs'] = $this->runs;
            $params['interval'] = $this->interval;
        }

        $key = $this->service->id.'|'.md5($this->targetUrl);
        SmmCart::add($this->service, $qty, $this->targetUrl, $params);

        $this->added = true;
        $this->notify(__('smm::messages.added_to_cart', ['name' => $this->service->name]), 'success');

        $this->dispatch('smm-cart-updated');
    }

    public function recalculatePrice()
    {
        $this->recalculate();
    }

    public function render()
    {
        return view('smm::storefront.service', [
            'service' => $this->service,
        ]);
    }
}
