<?php

namespace Paymenter\Extensions\Others\Smm\Livewire\Customer;

use App\Livewire\Component;
use Paymenter\Extensions\Others\Smm\Models\SmmOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OrderShow extends Component
{
    public $order;

    public function mount($order)
    {
        $this->order = SmmOrder::where('id', $order)->with(['items.service', 'invoice', 'statusHistory', 'refills'])->firstOrFail();

        if (!$this->order->isOwnedBy(Auth::user()) && !Auth::user()->hasPermission('admin.smm.orders') && !Auth::user()->hasPermission('*')) {
            abort(403);
        }
    }

    public function syncStatus()
    {
        $this->order = SmmOrder::find($this->order->id);
        if (!$this->order->provider_order_id) {
            $this->notify(__('smm::messages.no_provider_order'), 'error');
            return;
        }
        app(\Paymenter\Extensions\Others\Smm\Services\OrderService::class)->syncOrderStatus($this->order);
        $this->order = SmmOrder::find($this->order->id);
        $this->notify(__('smm::messages.status_synced'), 'success');
    }

    public function requestRefill()
    {
        $res = app(\Paymenter\Extensions\Others\Smm\Services\OrderService::class)->requestRefill($this->order);
        $this->order = SmmOrder::find($this->order->id);
        $this->notify($res['message'], $res['success'] ? 'success' : 'error');
    }

    public function requestCancellation()
    {
        $res = app(\Paymenter\Extensions\Others\Smm\Services\OrderService::class)->requestCancellation($this->order);
        $this->order = SmmOrder::find($this->order->id);
        $this->notify($res['message'], $res['success'] ? 'success' : 'error');
    }

    public function render()
    {
        return view('smm::customer.order-show', ['order' => $this->order]);
    }
}
