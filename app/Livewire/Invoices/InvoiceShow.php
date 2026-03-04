<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;

class InvoiceShow extends Component
{
    public Invoice $invoice;

    public function mount(Invoice $invoice): void
    {
        abort_if($invoice->user_id !== auth()->id(), 403);
        $this->invoice = $invoice->load(['client', 'items.product']);
    }

    public function updateStatus(string $status): void
    {
        $this->invoice->update(['status' => $status]);
        $this->invoice->refresh();
        session()->flash('success', 'Status updated!');
    }

    public function render()
    {
        return view('livewire.invoices.invoice-show')
            ->layout('layouts.dashboard', ['title' => 'Invoice ' . $this->invoice->invoice_number]);
    }
}