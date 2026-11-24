<?php

namespace App\Livewire;

use Livewire\Component;

class FreePlans extends Component
{
    public array $inviteTiers = [];
    public array $boostTiers = [];

    public function mount(): void
    {
        $this->inviteTiers = [
            ['name' => 'Tier I', 'invites' => 6, 'ram' => '2 GB', 'ram_mb' => 2048],
            ['name' => 'Tier II', 'invites' => 8, 'ram' => '5 GB', 'ram_mb' => 5120],
            ['name' =>'Tier III', 'invites' => 12, 'ram' => '7 GB', 'ram_mb' => 7168],
        ];

        $this->boostTiers = [
            ['name' => 'Tier I', 'boosts' => 1, 'ram' => '3 GB', 'ram_mb' => 3072],
            ['name' => 'Tier II', 'boosts' => 2, 'ram' => '6 GB', 'ram_mb' => 6144],
            ['name' => 'Tier III', 'boosts' => 3, 'ram' => '9 GB', 'ram_mb' => 9216],
            ['name' => 'Tier IV', 'boosts' => 4, 'ram' => '12 GB', 'ram_mb' => 12288],
        ];
    }

    public function render()
    {
        return view('free-plans')->layoutData([
            'title' => 'Free Plans',
        ]);
    }
}
