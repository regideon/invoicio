<?php

namespace App\Livewire\Invoices;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InvoiceCreate extends Component
{
    // Invoice fields
    public int $client_id = 0;
    // public string $invoice_number = '';
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

    public function mount(): void
    {
        $this->issue_date     = now()->format('Y-m-d');
        $this->due_date       = now()->addDays(30)->format('Y-m-d');
        $this->clients        = auth()->user()->clients()->orderBy('name')->get();
        $this->products       = auth()->user()->products()->orderBy('name')->get();

        // Start with one empty line item
        $this->addItem();
    }

    // Add a new line item row
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

    // Remove a line item row
    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }

    // When a product is selected in a line item
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

    // Recalculate line item total when price or quantity changes
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

    // Recalculate totals when tax rate changes
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
            'status'             => 'required|in:draft,sent,paid,overdue',
            'issue_date'         => 'required|date',
            'due_date'           => 'required|date|after_or_equal:issue_date',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id', // 👈 required now
            'items.*.price'      => 'required|numeric|min:0',
            'items.*.quantity'   => 'required|numeric|min:1',
        ]);

        DB::transaction(function () {
            // Generate number safely inside transaction with lock
            $invoiceNumber = Invoice::generateNumber();

            $invoice = Invoice::create([
                'user_id'        => auth()->id(),
                'client_id'      => $this->client_id,
                'invoice_number' => $invoiceNumber,
                'status'         => $this->status,
                'issue_date'     => $this->issue_date,
                'due_date'       => $this->due_date,
                'subtotal'       => $this->subtotal,
                'tax_rate'       => $this->tax_rate,
                'tax_amount'     => $this->tax_amount,
                'total'          => $this->total,
                'notes'          => $this->notes,
            ]);

            foreach ($this->items as $item) {
                $product = Product::find($item['product_id']);
                $invoice->items()->create([
                    'product_id'  => $item['product_id'],
                    'name'        => $product->name, // 👈 auto from product
                    'description' => $item['description'],
                    'price'       => $item['price'],
                    'quantity'    => $item['quantity'],
                    'total'       => $item['total'],
                ]);
            }
        });

        session()->flash('success', 'Invoice created successfully!');
        $this->redirect(route('invoices.index'));
    }

    public function render()
    {
        return view('livewire.invoices.invoice-create')
            ->layout('layouts.dashboard', ['title' => 'Create Invoice']);
    }
}