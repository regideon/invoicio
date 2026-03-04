<?php

namespace App\Livewire\AI;

use App\Models\AiInsight;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Client;
use App\Services\AIService;
use Livewire\Component;

class FinancialReport extends Component
{
    public string $insightsHtml = '';
    public bool $loading        = false;
    public bool $generated      = false;
    public ?string $generatedAt = null;
    public string $period       = '30'; // days

    public array $periods = [
        '7'   => 'Last 7 Days',
        '30'  => 'Last 30 Days',
        '90'  => 'Last 90 Days',
        '365' => 'Last 12 Months',
    ];

    public function mount(): void
    {
        // Load existing report from DB if available
        $existing = AiInsight::where('user_id', auth()->id())
            ->where('type', 'financial_report_' . $this->period)
            ->latest()
            ->first();

        if ($existing) {
            $this->insightsHtml = $existing->response_html;
            $this->generatedAt  = $existing->generated_at->diffForHumans();
            $this->generated    = true;
        }
    }

    public function updatedPeriod(): void
    {
        // When period changes, load existing report for that period
        $this->insightsHtml = '';
        $this->generated    = false;
        $this->generatedAt  = null;

        $existing = AiInsight::where('user_id', auth()->id())
            ->where('type', 'financial_report_' . $this->period)
            ->latest()
            ->first();

        if ($existing) {
            $this->insightsHtml = $existing->response_html;
            $this->generatedAt  = $existing->generated_at->diffForHumans();
            $this->generated    = true;
        }
    }

    public function generate(): void
    {
        $this->loading = true;

        $context = $this->buildFinancialContext();

        $systemPrompt = "You are a senior financial analyst for a small business using 
            an invoicing app called Invoicio. Generate a comprehensive, professional 
            financial report based on the data provided. Use clear sections, 
            highlight key metrics, identify trends, and provide actionable 
            recommendations. Use emojis and markdown formatting.";

        $userMessage = "Generate a comprehensive financial report for this period:\n\n{$context}";

        // For financial report we use a fake "subject" — the user itself
        // We store it differently since it's not tied to a specific invoice/client
        $ai       = new AIService();
        $response = $ai->ask($systemPrompt, $userMessage, 2048);

        // Parse markdown
        $responseHtml = (new \Parsedown())->text($response);

        // Save to DB
        AiInsight::updateOrCreate(
            [
                'user_id'      => auth()->id(),
                'type'         => 'financial_report_' . $this->period,
                'subject_type' => 'App\Models\User',
                'subject_id'   => auth()->id(),
            ],
            [
                'prompt'        => $userMessage,
                'response'      => $response,
                'response_html' => $responseHtml,
                'generated_at'  => now(),
            ]
        );

        $this->insightsHtml = $responseHtml;
        $this->generatedAt  = now()->diffForHumans();
        $this->loading      = false;
        $this->generated    = true;
    }

    private function buildFinancialContext(): string
    {
        $userId    = auth()->id();
        $days      = (int) $this->period;
        $startDate = now()->subDays($days);
        $periodLabel = $this->periods[$this->period];

        // --- INVOICES ---
        $allInvoices      = Invoice::where('user_id', $userId)->get();
        $periodInvoices   = Invoice::where('user_id', $userId)
            ->where('created_at', '>=', $startDate)
            ->with(['client', 'payments'])
            ->get();

        $totalInvoiced    = $periodInvoices->sum('total');
        $totalPaid        = $periodInvoices->where('status', 'paid')->sum('total');
        $totalPending     = $periodInvoices->where('status', 'sent')->sum('total');
        $totalOverdue     = $periodInvoices->where('status', 'overdue')->sum('total');
        $totalDraft       = $periodInvoices->where('status', 'draft')->sum('total');
        $paidCount        = $periodInvoices->where('status', 'paid')->count();
        $pendingCount     = $periodInvoices->where('status', 'sent')->count();
        $overdueCount     = $periodInvoices->where('status', 'overdue')->count();
        $collectionRate   = $totalInvoiced > 0
            ? round(($totalPaid / $totalInvoiced) * 100, 1)
            : 0;

        // --- PAYMENTS ---
        $periodPayments   = Payment::where('user_id', $userId)
            ->where('payment_date', '>=', $startDate)
            ->get();
        $totalCollected   = $periodPayments->sum('amount');

        // Payment method breakdown
        $methodBreakdown  = $periodPayments->groupBy('method')->map(function($group, $method) {
            $total = $group->sum('amount');
            return "- {$method}: \${$total} ({$group->count()} payments)";
        })->join("\n");

        // --- CLIENTS ---
        $totalClients     = Client::where('user_id', $userId)->count();
        $activeClients    = $periodInvoices->pluck('client_id')->unique()->count();

        // Top clients by invoice value
        $topClients = $periodInvoices
            ->groupBy('client_id')
            ->map(function($invoices) {
                $clientName = $invoices->first()->client->name;
                $total      = $invoices->sum('total');
                $count      = $invoices->count();
                return "- {$clientName}: \${$total} ({$count} invoices)";
            })
            ->values()
            ->take(5)
            ->join("\n");

        // --- OVERDUE LIST ---
        $overdueList = $periodInvoices->where('status', 'overdue')
            ->map(function($invoice) {
                $clientName    = $invoice->client->name;
                $invoiceNumber = $invoice->invoice_number;
                $total         = $invoice->total;
                $dueDate       = $invoice->due_date->format('M d, Y');
                $daysOverdue   = now()->diffInDays($invoice->due_date);
                return "- #{$invoiceNumber} | {$clientName} | \${$total} | Due: {$dueDate} | {$daysOverdue} days overdue";
            })->join("\n");

        // All time stats
        $allTimeRevenue  = $allInvoices->where('status', 'paid')->sum('total');
        $allTimeInvoices = $allInvoices->count();

        $startFormatted = $startDate->format('M d, Y');
        $todayFormatted = now()->format('M d, Y');

        return "
REPORT PERIOD: {$periodLabel} ({$startFormatted} to {$todayFormatted})

INVOICE SUMMARY:
- Total Invoices Created: {$periodInvoices->count()}
- Total Invoiced Amount: \${$totalInvoiced}
- Paid: \${$totalPaid} ({$paidCount} invoices)
- Pending/Sent: \${$totalPending} ({$pendingCount} invoices)
- Overdue: \${$totalOverdue} ({$overdueCount} invoices)
- Draft: \${$totalDraft}
- Collection Rate: {$collectionRate}%

PAYMENTS COLLECTED:
- Total Collected: \${$totalCollected}
- Number of Payments: {$periodPayments->count()}

PAYMENT METHODS:
{$methodBreakdown}

CLIENT OVERVIEW:
- Total Clients: {$totalClients}
- Active Clients This Period: {$activeClients}

TOP CLIENTS BY REVENUE:
{$topClients}

OVERDUE INVOICES:
" . ($overdueList ?: "None - Great job!") . "

ALL TIME STATS:
- Total Revenue (All Time): \${$allTimeRevenue}
- Total Invoices (All Time): {$allTimeInvoices}
        ";
    }

    public function render()
    {
        return view('livewire.a-i.financial-report')
            ->layout('layouts.dashboard', ['title' => 'Financial Report']);
    }
}