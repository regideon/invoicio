<?php

namespace App\Livewire\Invoices;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class InvoiceEdit extends Component
{
    public Invoice $invoice;

    // Invoice fields
    public int $client_id = 0;
    public string $invoice_number = '';
    public string $status = 'draft';
    public string $issue_date = '';
    public string $due_date = '';
    public string $notes = '';

    // Totals
    public float $subtotal = 0;
    public float $tax_rate = 0;
    public float $tax_amount = 0;
    public float $total = 0;

    // Line items
    public array $items = [];

    // Dropdown data
    public $clients = [];
    public $products = [];

    public function mount(Invoice $invoice): void
    {
        // Make sure this invoice belongs to the logged in user
        abort_if($invoice->user_id !== auth()->id(), 403);

        $this->invoice        = $invoice;
        $this->invoice_number = $invoice->invoice_number;
        $this->client_id      = $invoice->client_id;
        $this->status         = $invoice->status;
        $this->issue_date     = $invoice->issue_date->format('Y-m-d');
        $this->due_date       = $invoice->due_date->format('Y-m-d');
        $this->notes          = $invoice->notes ?? '';
        $this->subtotal       = $invoice->subtotal;
        $this->tax_rate       = $invoice->tax_rate;
        $this->tax_amount     = $invoice->tax_amount;
        $this->total          = $invoice->total;

        $this->clients  = auth()->user()->clients()->orderBy('name')->get();
        $this->products = auth()->user()->products()->orderBy('name')->get();

        // Load existing line items
        $this->items = $invoice->items->map(fn($item) => [
            'product_id'  => $item->product_id ?? '',
            'name'        => $item->name,
            'description' => $item->description ?? '',
            'price'       => $item->price,
            'quantity'    => $item->quantity,
            'total'       => $item->total,
        ])->toArray();
    }

    public function addItem(): void
    {
        $this->items[] = [
            'product_id'  => '',
            'name'        => '',
            'description' => '',
            'price'       => 0,
            'quantity'    => 1,
            'total'       => 0,
        ];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }

    public function productSelected(int $index): void
    {
        $productId = $this->items[$index]['product_id'];

        if ($productId) {
            $product = Product::find($productId);
            if ($product) {
                $this->items[$index]['name']        = $product->name;
                $this->items[$index]['description'] = $product->description ?? '';
                $this->items[$index]['price']       = $product->price;
                $this->items[$index]['quantity']    = 1;
                $this->items[$index]['total']       = $product->price;
            }
        }

        $this->calculateTotals();
    }

    public function updatedItems(): void
    {
        foreach ($this->items as $index => $item) {
            $this->items[$index]['total'] = round(
                (float)($item['price'] ?? 0) * (float)($item['quantity'] ?? 1),
                2
            );
        }
        $this->calculateTotals();
    }

    public function updatedTaxRate(): void
    {
        $this->calculateTotals();
    }

    private function calculateTotals(): void
    {
        $this->subtotal   = round(array_sum(array_column($this->items, 'total')), 2);
        $this->tax_amount = round($this->subtotal * ($this->tax_rate / 100), 2);
        $this->total      = round($this->subtotal + $this->tax_amount, 2);
    }

    public function save(): void
    {
        $this->validate([
            'client_id'          => 'required|exists:clients,id',
            'status'             => 'required|in:draft,sent,partial,paid,overdue',
            'issue_date'         => 'required|date',
            'due_date'           => 'required|date|after_or_equal:issue_date',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.price'      => 'required|numeric|min:0',
            'items.*.quantity'   => 'required|numeric|min:1',
        ]);

        DB::transaction(function () {
            $this->invoice->update([
                'client_id'  => $this->client_id,
                'status'     => $this->status,
                'issue_date' => $this->issue_date,
                'due_date'   => $this->due_date,
                'subtotal'   => $this->subtotal,
                'tax_rate'   => $this->tax_rate,
                'tax_amount' => $this->tax_amount,
                'total'      => $this->total,
                'notes'      => $this->notes,
            ]);

            // Delete old items and recreate
            $this->invoice->items()->delete();

            foreach ($this->items as $item) {
                $product = Product::find($item['product_id']);
                $this->invoice->items()->create([
                    'product_id'  => $item['product_id'],
                    'name'        => $product->name,
                    'description' => $item['description'],
                    'price'       => $item['price'],
                    'quantity'    => $item['quantity'],
                    'total'       => $item['total'],
                ]);
            }
        });

        session()->flash('success', 'Invoice updated successfully!');
        $this->redirect(route('invoices.index'));
    }

    public function render()
    {
        return view('livewire.invoices.invoice-edit')
            ->layout('layouts.dashboard', ['title' => 'Edit Invoice']);
    }
}
