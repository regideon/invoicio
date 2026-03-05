<?php

namespace App\Livewire\Payments;

use App\Models\Invoice;
use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $methodFilter = '';

    public bool $showModal = false;

    // Form fields
    public int $invoice_id = 0;
    public string $amount = '';
    public string $payment_date = '';
    public string $method = 'cash';
    public string $reference = '';
    public string $notes = '';

    public $invoices = [];

    public function mount(): void
    {
        $this->payment_date = now()->format('Y-m-d');
        $this->invoices     = auth()->user()->invoices()
            ->with('client')
            ->whereIn('status', ['sent', 'partial', 'overdue'])
            ->orderBy('due_date')
            ->get();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'invoice_id'   => 'required|exists:invoices,id',
            'amount'       => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'method'       => 'required|in:cash,bank_transfer,check,credit_card,online',
            'reference'    => 'nullable|string|max:255',
            'notes'        => 'nullable|string',
        ]);

        $payment = Payment::create([
            'user_id'      => auth()->id(),
            'invoice_id'   => $this->invoice_id,
            'amount'       => $this->amount,
            'payment_date' => $this->payment_date,
            'method'       => $this->method,
            'reference'    => $this->reference,
            'notes'        => $this->notes,
        ]);

        // Auto mark invoice as paid if balance is zero
        $invoice   = Invoice::with('payments')->find($this->invoice_id);
        $totalPaid = $invoice->payments->sum('amount');

        $invoice->update([
            'status' => $totalPaid >= $invoice->total ? 'paid' : 'partial',
        ]);

        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', 'Payment recorded successfully!');
    }

    public function delete(int $id): void
    {
        Payment::findOrFail($id)->delete();
        session()->flash('success', 'Payment deleted!');
    }

    private function resetForm(): void
    {
        $this->invoice_id   = 0;
        $this->amount       = '';
        $this->payment_date = now()->format('Y-m-d');
        $this->method       = 'cash';
        $this->reference    = '';
        $this->notes        = '';
        $this->resetValidation();
    }

    public function render()
    {
        $payments = auth()->user()->payments()
            ->with(['invoice.client'])
            ->when($this->search, fn($q) =>
                $q->whereHas('invoice', fn($q) =>
                    $q->where('invoice_number', 'like', "%{$this->search}%")
                      ->orWhereHas('client', fn($q) =>
                          $q->where('name', 'like', "%{$this->search}%")
                      )
                )
            )
            ->when($this->methodFilter, fn($q) =>
                $q->where('method', $this->methodFilter)
            )
            ->latest()
            ->paginate(10);

        $totalPayments = auth()->user()->payments()->sum('amount');

        return view('livewire.payments.payment-list', compact('payments', 'totalPayments'))
            ->layout('layouts.dashboard', ['title' => 'Payments']);
    }
}
