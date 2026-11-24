<?php

namespace App\Livewire\Products;

use App\Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{


    public $categories;

    public $childCategories;

    public Category $category;

    // Filter properties
    public $minPrice = 0;
    public $maxPrice = 0;
    public $sortBy = 'name';

    public function mount()
    {
        $this->childCategories = $this->category->children()->where(function ($query) {
            $query->whereHas('children')->orWhereHas('products', function ($query) {
                $query->where('hidden', false);
            });
        })->orderBy('sort')->get();
        $this->categories = Category::whereNull('parent_id')->where(function ($query) {
            $query->whereHas('children')->orWhereHas('products', function ($query) {
                $query->where('hidden', false);
            });
        })->orderBy('sort')->get();
        if (count($this->category->products()->where('hidden', false)->get()) === 0 && count($this->childCategories) === 0) {
            abort(404);
        }
    }

    public function getProductsProperty()
    {
        $query = $this->category->products()->where('hidden', false)->with(['category', 'plans.prices', 'configOptions.children.plans.prices']);

        // Apply price filters
        if ($this->minPrice > 0) {
            $query->whereHas('plans.prices', function ($q) {
                $q->where('price', '>=', $this->minPrice);
            });
        }

        if ($this->maxPrice > 0) {
            $query->whereHas('plans.prices', function ($q) {
                $q->where('price', '<=', $this->maxPrice);
            });
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'price':
                $query->orderByRaw('MIN(plans_prices.price)');
                break;
            case 'popularity':
                // Assuming popularity could be based on sales or views - for now we'll sort by sort order
                $query->orderBy('sort');
                break;
            case 'name':
            default:
                $query->orderBy('sort');
                break;
        }

        return $query->get();
    }

    public function applyFilters()
    {
        // This method is called when the filter button is clicked
        // The products property will automatically update due to the getProductsProperty method
    }

    public function resetFilters()
    {
        $this->minPrice = 0;
        $this->maxPrice = 0;
        $this->sortBy = 'name';
    }

    public function render()
    {
        return view('products.index', [
            'products' => $this->getProductsProperty(),
        ])->layoutData([
            'title' => $this->category->name,
            'image' => $this->category->image ? Storage::url($this->category->image) : null,
        ]);
    }
}
