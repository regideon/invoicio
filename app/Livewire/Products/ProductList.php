<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;
    public bool $isEditing = false;

    public int $productId = 0;
    public string $name = '';
    public string $description = '';
    public string $price = '';
    public string $unit = 'pcs';

    public array $units = ['pcs', 'hrs', 'kg', 'lbs', 'day', 'month'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $product = Product::findOrFail($id);
        $this->productId   = $product->id;
        $this->name        = $product->name;
        $this->description = $product->description ?? '';
        $this->price       = $product->price;
        $this->unit        = $product->unit;
        $this->isEditing   = true;
        $this->showModal   = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'price'       => 'required|numeric|min:0',
            'unit'        => 'required|string',
        ]);

        if ($this->isEditing) {
            Product::findOrFail($this->productId)->update($data);
            session()->flash('success', 'Product updated successfully!');
        } else {
            auth()->user()->products()->create($data);
            session()->flash('success', 'Product created successfully!');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Product::findOrFail($id)->delete();
        session()->flash('success', 'Product deleted successfully!');
    }

    private function resetForm(): void
    {
        $this->productId   = 0;
        $this->name        = '';
        $this->description = '';
        $this->price       = '';
        $this->unit        = 'pcs';
        $this->resetValidation();
    }

    public function render()
    {
        $products = auth()->user()->products()
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%")
            )
            ->latest()
            ->paginate(10);

        return view('livewire.products.product-list', compact('products'))
            ->layout('layouts.dashboard', ['title' => 'Products']);
    }
}