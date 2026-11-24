<?php

namespace Paymenter\Extensions\Others\Smm\Policies;

use App\Models\User;
use Paymenter\Extensions\Others\Smm\Models\SmmOrder;

class SmmOrderPolicy
{
    public function view(User $user, SmmOrder $order): bool
    {
        if ($user->hasPermission('*')) {
            return true;
        }
        return $order->isOwnedBy($user);
    }

    public function manage(User $user): bool
    {
        return $user->hasPermission('admin.smm.orders') || $user->hasPermission('*');
    }
}
