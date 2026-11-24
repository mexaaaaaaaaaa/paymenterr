<?php

namespace Paymenter\Extensions\Others\Smm\Livewire\Customer;

use App\Livewire\Component;
use Livewire\WithPagination;
use Paymenter\Extensions\Others\Smm\Models\SmmOrder;
use Illuminate\Support\Facades\Auth;

class Orders extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    protected $queryString = ['search', 'status'];

    public function render()
    {
        $user = Auth::user();

        $query = SmmOrder::where('user_id', $user->id)->with(['items.service', 'invoice']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_code', 'like', '%'.$this->search.'%')
                    ->orWhere('provider_order_id', 'like', '%'.$this->search.'%')
                    ->orWhere('target_url', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $orders = $query->latest()->paginate(15);

        $stats = [
            'total' => SmmOrder::where('user_id', $user->id)->count(),
            'active' => SmmOrder::where('user_id', $user->id)->whereIn('status', [SmmOrder::STATUS_QUEUED, SmmOrder::STATUS_PROCESSING, SmmOrder::STATUS_IN_PROGRESS])->count(),
            'completed' => SmmOrder::where('user_id', $user->id)->where('status', SmmOrder::STATUS_COMPLETED)->count(),
            'pending' => SmmOrder::where('user_id', $user->id)->where('status', SmmOrder::STATUS_PENDING_PAYMENT)->count(),
            'failed' => SmmOrder::where('user_id', $user->id)->where('status', SmmOrder::STATUS_FAILED)->count(),
        ];

        return view('smm::customer.orders', compact('orders', 'stats'));
    }
}
