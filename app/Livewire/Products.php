<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductCategory;
use Livewire\Component;

class Products extends Component
{
    public $categories;
    public $selectedCategory;
    public $searchQuery = '';
    public $products;

    public function mount()
    {
        $this->categories = ProductCategory::with('products')->get();
        $this->products = Product::with('categories')->get();
    }

    public function render()
    {
        return view('livewire.products', [
            'records' => $this->products,
        ]);
    }

    public function filterByCategory($categoryId)
    {
        if ($categoryId === 'all') {
            $this->products = Product::with('categories')->get();
            $this->selectedCategory = null;
        } else {
            $category = ProductCategory::with('products')->find($categoryId);
            if ($category) {
                $this->products = $category->products;
                $this->selectedCategory = $category->name;
            }
        }
    }

    public function updatedSearchQuery()
    {
        $this->products = Product::where('name', 'like', '%' . $this->searchQuery . '%')->get();
    }
}
