<?php

namespace App\Livewire\AI;

use App\Models\Client;
use App\Services\AIService;
use Livewire\Component;

class ClientInsights extends Component
{
    public Client $client;

    public string $insightsHtml = '';
    public bool $loading        = false;
    public bool $generated      = false;
    public ?string $generatedAt = null;

    public function mount(Client $client): void
    {
        $this->client = $client->load(['invoices.payments']);

        // Load existing from DB
        $existing = $client->latestInsight('client_insights');
        if ($existing) {
            $this->insightsHtml = $existing->response_html;
            $this->generatedAt  = $existing->generated_at->diffForHumans();
            $this->generated    = true;
        }
    }

    public function generate(): void
    {
        $this->loading = true;

        $context = $this->buildClientContext();

        $systemPrompt = "You are a financial advisor for a small business using an 
            invoicing app called Invoicio. Analyze client payment behavior and provide 
            clear, actionable insights. Be direct, friendly, and practical. 
            Use emojis and markdown formatting for clarity.";

        $userMessage = "Please analyze this client's payment behavior:\n\n{$context}";

        $ai      = new AIService();
        $insight = $ai->askAndSave(
            type: 'client_insights',
            subject: $this->client,
            systemPrompt: $systemPrompt,
            userMessage: $userMessage,
            maxTokens: 1024
        );

        $this->insightsHtml = $insight->response_html;
        $this->generatedAt  = $insight->generated_at->diffForHumans();
        $this->loading      = false;
        $this->generated    = true;
    }

    private function buildClientContext(): string
    {
        $client   = $this->client;
        $invoices = $client->invoices;

        // Pre-assign all variables
        $clientName    = $client->name;
        $clientCompany = $client->company ?? 'N/A';
        $clientEmail   = $client->email   ?? 'N/A';
        $clientPhone   = $client->phone   ?? 'N/A';
        $clientAddress = $client->address ?? 'N/A';

        // Statistics
        $totalInvoices = $invoices->count();
        $totalValue    = $invoices->sum('total');
        $paidInvoices  = $invoices->where('status', 'paid');
        $overdueCount  = $invoices->where('status', 'overdue')->count();
        $totalPaid     = $invoices->flatMap->payments->sum('amount');
        $totalUnpaid   = $totalValue - $totalPaid;

        // Average days to pay
        $daysToPayList = [];
        foreach ($paidInvoices as $invoice) {
            if ($invoice->payments->isNotEmpty()) {
                $firstPayment    = $invoice->payments->sortBy('payment_date')->first();
                $daysToPayList[] = $invoice->issue_date->diffInDays($firstPayment->payment_date);
            }
        }
        $avgDaysToPay = count($daysToPayList) > 0
            ? round(array_sum($daysToPayList) / count($daysToPayList))
            : 'N/A';

        // Invoice breakdown
        $invoiceBreakdown = $invoices->map(function($invoice) {
            $invoiceNumber = $invoice->invoice_number;
            $status        = $invoice->status;
            $total         = $invoice->total;
            $paid          = $invoice->payments->sum('amount');
            $balance       = $total - $paid;
            $dueDate       = $invoice->due_date->format('M d, Y');
            return "- #{$invoiceNumber} | {$status} | \${$total} | Paid: \${$paid} | Balance: \${$balance} | Due: {$dueDate}";
        })->join("\n");

        $todayFormatted = now()->format('M d, Y');

        return "
    CLIENT PROFILE:
    - Name: {$clientName}
    - Company: {$clientCompany}
    - Email: {$clientEmail}
    - Phone: {$clientPhone}
    - Address: {$clientAddress}

    INVOICE STATISTICS:
    - Total Invoices: {$totalInvoices}
    - Total Value: \${$totalValue}
    - Total Collected: \${$totalPaid}
    - Total Outstanding: \${$totalUnpaid}
    - Overdue Invoices: {$overdueCount}
    - Average Days to Pay: {$avgDaysToPay} days

    INVOICE BREAKDOWN:
    {$invoiceBreakdown}

    TODAY'S DATE: {$todayFormatted}
        ";
    }

    public function render()
    {
        return view('livewire.a-i.client-insights');
    }
}