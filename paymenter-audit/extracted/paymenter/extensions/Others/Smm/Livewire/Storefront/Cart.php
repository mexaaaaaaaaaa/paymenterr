<?php

namespace Paymenter\Extensions\Others\Smm\Livewire\Storefront;

use App\Livewire\Component;
use Paymenter\Extensions\Others\Smm\Services\SmmCart;

class Cart extends Component
{
    public $items = [];
    public $total = '0';
    public $count = 0;
    public $currency = 'INR';

    public function mount()
    {
        $this->refresh();
    }

    public function refresh()
    {
        $data = SmmCart::total();
        $this->items = $data['items'];
        $this->total = $data['total'];
        $this->count = $data['count'];
        $this->currency = !empty($this->items) ? ($this->items[array_key_first($this->items)]['currency'] ?? 'INR') : 'INR';
    }

    public function remove($key)
    {
        SmmCart::remove($key);
        $this->refresh();
        $this->notify(__('smm::messages.removed_from_cart'), 'success');
        $this->dispatch('smm-cart-updated');
    }

    public function updateQuantity($key, $quantity)
    {
        $quantity = (int) $quantity;
        if ($quantity < 1) {
            $quantity = 1;
        }
        SmmCart::update($key, ['quantity' => $quantity]);
        $this->refresh();
        $this->dispatch('smm-cart-updated');
    }

    public function clear()
    {
        SmmCart::clear();
        $this->refresh();
        $this->dispatch('smm-cart-updated');
    }

    public function checkout()
    {
        if ($this->count === 0) {
            $this->notify(__('smm::messages.cart_empty'), 'error');
            return;
        }
        $this->redirect(route('smm.checkout'), navigate: true);
    }

    public function render()
    {
        return view('smm::storefront.cart');
    }
}
