<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;


class InvoiceList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->items()->delete();
        $invoice->delete();
        session()->flash('success', 'Invoice deleted successfully!');
    }

    public function updateStatus(int $id, string $status): void
    {
        Invoice::findOrFail($id)->update(['status' => $status]);
        session()->flash('success', 'Invoice status updated!');
    }

    public function render()
    {
        $invoices = auth()->user()->invoices()
            ->with(['client', 'items'])
            ->when($this->search, fn($q) =>
                $q->where('invoice_number', 'like', "%{$this->search}%")
                  ->orWhereHas('client', fn($q) =>
                      $q->where('name', 'like', "%{$this->search}%")
                  )
            )
            ->when($this->statusFilter, fn($q) =>
                $q->where('status', $this->statusFilter)
            )
            ->latest()
            ->paginate(10);

        return view('livewire.invoices.invoice-list', compact('invoices'))
            ->layout('layouts.dashboard', ['title' => 'Invoices']);
    }
}