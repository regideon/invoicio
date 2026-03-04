<?php

namespace App\Livewire\AI;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use App\Services\AIService;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Parsedown;

class DraftInvoice extends Component
{
    // The text description from user
    public string $description = '';

    // Loading states
    public bool $loading      = false;
    public bool $drafted      = false;

    // AI explanation of what it understood
    public string $aiSummaryHtml = '';

    // Parsed invoice data from Claude
    public array $draftData = [];

    // Form fields (same as InvoiceCreate)
    public int $client_id     = 0;
    public string $status     = 'draft';
    public string $issue_date = '';
    public string $due_date   = '';
    public string $notes      = '';
    public float $tax_rate    = 0;
    public float $subtotal    = 0;
    public float $tax_amount  = 0;
    public float $total       = 0;
    public array $items       = [];

    // Dropdown data
    public $clients  = [];
    public $products = [];

    // Example prompts to help user
    public array $examples = [
        'Web design for Acme Corp, 5 hours at $100 per hour, 10% tax, due in 15 days',
        'Monthly retainer for Shell Philippines, $2,500 flat fee, due in 30 days',
        'Logo design and branding for Delta Tech, 3 items: logo $500, business cards $200, letterhead $150, 12% VAT',
    ];

    public function mount(): void
    {
        $this->issue_date = now()->format('Y-m-d');
        $this->due_date   = now()->addDays(30)->format('Y-m-d');
        $this->clients    = auth()->user()->clients()->orderBy('name')->get();
        $this->products   = auth()->user()->products()->orderBy('name')->get();
    }

    // When user clicks an example prompt
    public function useExample(string $example): void
    {
        $this->description = $example;
    }

    public function generate(): void
    {
        $this->validate([
            'description' => 'required|string|min:10',
        ]);

        $this->loading = true;
        $this->drafted = false;

        // Build context about existing clients and products
        // So Claude can match them if they exist
        $clientList = $this->clients->map(fn($c) =>
            "ID:{$c->id} Name:{$c->name} Company:{$c->company}"
        )->join(', ');

        $productList = $this->products->map(fn($p) =>
            "ID:{$p->id} Name:{$p->name} Price:{$p->price}"
        )->join(', ');

        $systemPrompt = "You are an invoice assistant for a small business app called Invoicio.
            Your job is to parse a natural language invoice description and return ONLY a valid JSON object.
            Do not include any explanation or markdown — just raw JSON.
            
            The JSON must follow this exact structure:
            {
                \"client_id\": <number or null if not found>,
                \"client_name_suggestion\": \"<name if not found>\",
                \"status\": \"draft\",
                \"issue_date\": \"<YYYY-MM-DD>\",
                \"due_date\": \"<YYYY-MM-DD>\",
                \"tax_rate\": <number>,
                \"notes\": \"<string or empty>\",
                \"items\": [
                    {
                        \"product_id\": <number or null>,
                        \"name\": \"<item name>\",
                        \"description\": \"<description or empty>\",
                        \"price\": <number>,
                        \"quantity\": <number>
                    }
                ],
                \"ai_summary\": \"<friendly explanation of what you understood in 2-3 sentences>\"
            }";

        $userMessage = "Today's date is " . now()->format('Y-m-d') . ".
            
Available clients: {$clientList}
Available products: {$productList}

Parse this invoice description into JSON:
\"{$this->description}\"";

        $ai       = new AIService();
        $response = $ai->ask($systemPrompt, $userMessage, 1024);

        // Parse the JSON response from Claude
        $data = json_decode($response, true);

        if (!$data) {
            session()->flash('error', 'Claude could not parse your description. Please try again with more details.');
            $this->loading = false;
            return;
        }

        // Fill the form with Claude's data
        $this->client_id  = $data['client_id'] ?? 0;
        $this->status     = $data['status']     ?? 'draft';
        $this->issue_date = $data['issue_date'] ?? now()->format('Y-m-d');
        $this->due_date   = $data['due_date']   ?? now()->addDays(30)->format('Y-m-d');
        $this->tax_rate   = $data['tax_rate']   ?? 0;
        $this->notes      = $data['notes']      ?? '';
        $this->items      = array_map(function($item) {
            $price    = (float)($item['price']    ?? 0);
            $quantity = (float)($item['quantity'] ?? 1);
            return [
                'product_id'  => $item['product_id']  ?? null,
                'name'        => $item['name']         ?? '',
                'description' => $item['description']  ?? '',
                'price'       => $price,
                'quantity'    => $quantity,
                'total'       => round($price * $quantity, 2),
            ];
        }, $data['items'] ?? []);

        // AI summary explanation
        $aiSummary           = $data['ai_summary'] ?? 'Invoice drafted successfully.';
        $this->aiSummaryHtml = (new Parsedown())->text($aiSummary);

        // Calculate totals
        $this->calculateTotals();

        $this->draftData = $data;
        $this->loading   = false;
        $this->drafted   = true;
    }

    public function addItem(): void
    {
        $this->items[] = [
            'product_id'  => null,
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

    public function updatedItems(): void
    {
        foreach ($this->items as $index => $item) {
            $this->items[$index]['total'] = round(
                (float)($item['price']    ?? 0) *
                (float)($item['quantity'] ?? 1), 2
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
            'status'             => 'required|in:draft,sent,paid,overdue',
            'issue_date'         => 'required|date',
            'due_date'           => 'required|date|after_or_equal:issue_date',
            'items'              => 'required|array|min:1',
            'items.*.name'       => 'required|string',
            'items.*.price'      => 'required|numeric|min:0',
            'items.*.quantity'   => 'required|numeric|min:1',
        ]);

        DB::transaction(function () {
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
                $invoice->items()->create([
                    'product_id'  => $item['product_id'] ?: null,
                    'name'        => $item['name'],
                    'description' => $item['description'],
                    'price'       => $item['price'],
                    'quantity'    => $item['quantity'],
                    'total'       => $item['total'],
                ]);
            }
        });

        session()->flash('success', 'Invoice created successfully from AI draft!');
        $this->redirect(route('invoices.index'));
    }

    public function render()
    {
        return view('livewire.a-i.draft-invoice')
            ->layout('layouts.dashboard', ['title' => 'AI Draft Invoice']);
    }
}