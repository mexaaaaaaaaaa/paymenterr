<?php

namespace App\Livewire\Components;

use App\Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class Notifications extends Component
{
    public $filter = 'all'; // all, unread

    #[Computed, On('notification-added')]
    public function notifications()
    {
        $query = Auth::user()->notifications()->orderBy('created_at', 'desc');

        if ($this->filter === 'unread') {
            $query->whereNull('read_at');
        }

        return $query->limit(5)->get();
    }

    public function goToNotification($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if (!$notification) {
            return;
        }

        $notification->markAsRead();

        if ($notification->url) {
            return $this->redirect($notification->url, true);
        }
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if (!$notification) {
            return;
        }

        $notification->markAsRead();
    }

    public function markAsUnread($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if (!$notification) {
            return;
        }

        $notification->markAsUnread();
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function render()
    {
        return view('components.notifications');
    }
}
