<?php

namespace App\Livewire\AI;

use App\Models\Invoice;
use App\Models\AiInsight;
use App\Services\AIService;
use Livewire\Component;

class InvoiceInsights extends Component
{
    public Invoice $invoice;

    public string $insightsHtml = '';
    public bool $loading        = false;
    public bool $generated      = false;
    public ?string $generatedAt = null;

    public function mount(Invoice $invoice): void
    {
        $this->invoice = $invoice->load(['client', 'items', 'payments']);

        // Load existing insight from DB if available
        $existing = $invoice->latestInsight('invoice_insights');
        if ($existing) {
            $this->insightsHtml = $existing->response_html;
            $this->generatedAt  = $existing->generated_at->diffForHumans();
            $this->generated    = true;
        }
    }

    public function generate(): void
    {
        $this->loading = true;

        $context = $this->buildInvoiceContext();

        $systemPrompt = "You are a financial assistant for a small business invoicing app 
            called Invoicio. You analyze invoice data and provide clear, actionable insights. 
            Keep your response friendly, concise, and practical. Use simple language. 
            Format your response with clear sections using emojis.";

        $userMessage = "Please analyze this invoice and give me insights:\n\n{$context}";

        $ai      = new AIService();
        $insight = $ai->askAndSave(
            type: 'invoice_insights',
            subject: $this->invoice,
            systemPrompt: $systemPrompt,
            userMessage: $userMessage,
            maxTokens: 1024
        );

        $this->insightsHtml = $insight->response_html;
        $this->generatedAt  = $insight->generated_at->diffForHumans();
        $this->loading      = false;
        $this->generated    = true;
    }

    private function buildInvoiceContext(): string
    {
        $invoice  = $this->invoice;
        $client   = $invoice->client;
        $payments = $invoice->payments;

        // Pre-assign all variables
        $invoiceNumber = $invoice->invoice_number;
        $status        = $invoice->status;
        $issueDate     = $invoice->issue_date->format('M d, Y');
        $dueDate       = $invoice->due_date->format('M d, Y');
        $clientName    = $client->name;
        $clientCompany = $client->company  ?? 'N/A';
        $clientEmail   = $client->email    ?? 'N/A';
        $subtotal      = $invoice->subtotal;
        $taxRate       = $invoice->tax_rate;
        $taxAmount     = $invoice->tax_amount;
        $total         = $invoice->total;
        $totalPaid     = $payments->sum('amount');
        $balanceDue    = $invoice->total - $totalPaid;
        $notes         = $invoice->notes ?? 'None';

        // Due situation
        $today        = now();
        $daysDiff     = $today->diffInDays($invoice->due_date, false);
        $dueSituation = $daysDiff >= 0
            ? "Due in {$daysDiff} days"
            : "OVERDUE by " . abs($daysDiff) . " days";

        // Line items
        $lineItems = $invoice->items->map(fn($item) =>
            "- {$item->name}: {$item->quantity} x \${$item->price} = \${$item->total}"
        )->join("\n");

        // Payment history
        $paymentHistory = $payments->isEmpty()
            ? "No payments recorded yet."
            : $payments->map(fn($p) =>
                "- {$p->payment_date->format('M d, Y')}: \${$p->amount} via {$p->method}"
            )->join("\n");

        $todayFormatted = now()->format('M d, Y');

        return "
    INVOICE DETAILS:
    - Invoice Number: #${invoiceNumber}
    - Status: {$status}
    - Issue Date: {$issueDate}
    - Due Date: {$dueDate}
    - Due Situation: {$dueSituation}

    CLIENT INFO:
    - Name: {$clientName}
    - Company: {$clientCompany}
    - Email: {$clientEmail}

    FINANCIAL BREAKDOWN:
    - Subtotal: \${$subtotal}
    - Tax ({$taxRate}%): \${$taxAmount}
    - Total: \${$total}
    - Total Paid: \${$totalPaid}
    - Balance Due: \${$balanceDue}

    LINE ITEMS:
    {$lineItems}

    PAYMENT HISTORY:
    {$paymentHistory}

    NOTES: {$notes}
    TODAY'S DATE: {$todayFormatted}
        ";
    }

    public function render()
    {
        return view('livewire.a-i.invoice-insights');
    }
}