<?php

namespace Paymenter\Extensions\Others\Smm\Livewire\Storefront;

use App\Livewire\Component;
use Paymenter\Extensions\Others\Smm\Models\SmmCategory;
use Paymenter\Extensions\Others\Smm\Models\SmmService;

class Category extends Component
{
    public $slug;

    public function mount($slug = null)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        if ($this->slug) {
            $category = SmmCategory::where('slug', $this->slug)->where('is_active', true)->firstOrFail();
            $services = SmmService::where('category_id', $category->id)
                ->where('is_enabled', true)
                ->where('is_discontinued', false)
                ->orderBy('name')
                ->paginate(12);

            return view('smm::storefront.category-services', compact('category', 'services'));
        }

        $categories = SmmCategory::where('is_active', true)
            ->withCount(['services' => function ($q) {
                $q->where('is_enabled', true)->where('is_discontinued', false);
            }])
            ->orderBy('sort')
            ->orderBy('name')
            ->get();

        return view('smm::storefront.category', compact('categories'));
    }
}
