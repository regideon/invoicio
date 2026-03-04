<div>
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-100 text-emerald-800 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Top Actions Bar --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('invoices.index') }}"
                class="flex items-center gap-2 text-slate-500 hover:text-slate-700 text-sm font-medium">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
                Back
            </a>
            <h1 class="text-2xl font-bold text-slate-900">#{{ $invoice->invoice_number }}</h1>

            {{-- Status Badge --}}
            @php
                $statusClass = match($invoice->status) {
                    'paid'    => 'bg-emerald-100 text-emerald-800',
                    'sent'    => 'bg-blue-100 text-blue-800',
                    'overdue' => 'bg-rose-100 text-rose-800',
                    default   => 'bg-slate-100 text-slate-600',
                };

                $statusIcon = match($invoice->status) {
                    'paid'    => 'check_circle',
                    'sent'    => 'send',
                    'overdue' => 'error',
                    default   => 'draft',
                };
            @endphp
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ $statusClass }}">
                <span class="material-symbols-outlined text-sm">{{ $statusIcon }}</span>
                {{ ucfirst($invoice->status) }}
            </span>

            {{-- Balance Due --}}
            @php
                $totalPaid  = $invoice->payments->sum('amount');
                $balanceDue = $invoice->total - $totalPaid;
            @endphp
            @if($balanceDue > 0)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">
                    <span class="material-symbols-outlined text-sm">account_balance_wallet</span>
                    Balance Due: ${{ number_format($balanceDue, 2) }}
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                    <span class="material-symbols-outlined text-sm">check_circle</span>
                    Fully Paid
                </span>
            @endif
        </div>

        <div class="flex items-center gap-2">
            {{-- Quick Status Update --}}
            <select wire:change="updateStatus($event.target.value)"
                class="px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                <option value="draft"   {{ $invoice->status === 'draft'   ? 'selected' : '' }}>Draft</option>
                <option value="sent"    {{ $invoice->status === 'sent'    ? 'selected' : '' }}>Sent</option>
                <option value="paid"    {{ $invoice->status === 'paid'    ? 'selected' : '' }}>Paid</option>
                <option value="overdue" {{ $invoice->status === 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>

            <a href="{{ route('invoices.edit', $invoice) }}"
                class="flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                <span class="material-symbols-outlined text-xl">edit</span>
                Edit
            </a>

            <a href="{{ route('invoices.pdf.stream', $invoice) }}" target="_blank"
                class="flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                <span class="material-symbols-outlined text-xl">visibility</span>
                View PDF
            </a>

            <a href="{{ route('invoices.pdf.download', $invoice) }}"
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                <span class="material-symbols-outlined text-xl">download</span>
                Download PDF
            </a>
        </div>
    </div>

    {{-- Invoice Card --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8" id="invoice-print">

        {{-- Invoice Header --}}
        <div class="flex flex-col sm:flex-row justify-between gap-6 mb-10">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="bg-blue-600 p-2 rounded-lg">
                        <span class="material-symbols-outlined text-white">receipt_long</span>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">Invoicio</h2>
                </div>
                <p class="text-slate-500 text-sm">{{ auth()->user()->name }}</p>
                <p class="text-slate-500 text-sm">{{ auth()->user()->email }}</p>
            </div>

            <div class="text-left sm:text-right">
                <h1 class="text-3xl font-bold text-slate-900 mb-1">INVOICE</h1>
                <p class="text-blue-600 font-semibold">#{{ $invoice->invoice_number }}</p>
            </div>
        </div>

        {{-- Client + Dates --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10 p-6 bg-slate-50 rounded-xl">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Bill To</p>
                <p class="font-semibold text-slate-900">{{ $invoice->client->name }}</p>
                @if($invoice->client->company)
                    <p class="text-slate-500 text-sm">{{ $invoice->client->company }}</p>
                @endif
                @if($invoice->client->email)
                    <p class="text-slate-500 text-sm">{{ $invoice->client->email }}</p>
                @endif
                @if($invoice->client->phone)
                    <p class="text-slate-500 text-sm">{{ $invoice->client->phone }}</p>
                @endif
                @if($invoice->client->address)
                    <p class="text-slate-500 text-sm">{{ $invoice->client->address }}</p>
                @endif
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Issue Date</p>
                <p class="font-semibold text-slate-900">{{ $invoice->issue_date->format('M d, Y') }}</p>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Due Date</p>
                <p class="font-semibold text-slate-900">{{ $invoice->due_date->format('M d, Y') }}</p>
                @if($invoice->due_date->isPast() && $invoice->status !== 'paid')
                    <p class="text-rose-500 text-xs font-medium mt-1">⚠ Overdue</p>
                @endif
            </div>
        </div>

        {{-- Line Items Table --}}
        <div class="mb-8">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b-2 border-slate-200">
                        <th class="pb-3 text-xs font-bold uppercase tracking-wider text-slate-500">Product</th>
                        <th class="pb-3 text-xs font-bold uppercase tracking-wider text-slate-500">Description</th>
                        <th class="pb-3 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Price</th>
                        <th class="pb-3 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Qty</th>
                        <th class="pb-3 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($invoice->items as $item)
                    <tr>
                        <td class="py-4 font-medium text-slate-900">{{ $item->name }}</td>
                        <td class="py-4 text-slate-500 text-sm">{{ $item->description ?? '—' }}</td>
                        <td class="py-4 text-right text-slate-700">${{ number_format($item->price, 2) }}</td>
                        <td class="py-4 text-right text-slate-700">{{ $item->quantity }}</td>
                        <td class="py-4 text-right font-semibold text-slate-900">${{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        <div class="flex justify-end mb-8">
            <div class="w-full sm:w-72 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Subtotal</span>
                    <span class="font-medium">${{ number_format($invoice->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Tax ({{ $invoice->tax_rate }}%)</span>
                    <span class="font-medium">${{ number_format($invoice->tax_amount, 2) }}</span>
                </div>
                <div class="border-t-2 border-slate-200 pt-2 flex justify-between">
                    <span class="font-bold text-slate-900">Total</span>
                    <span class="font-bold text-blue-600 text-xl">${{ number_format($invoice->total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        @if($invoice->notes)
        <div class="border-t border-slate-200 pt-6">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Notes</p>
            <p class="text-slate-600 text-sm">{{ $invoice->notes }}</p>
        </div>
        @endif
    </div>

    {{-- AI Insights Section --}}
    <div class="mt-6">
        @livewire('a-i.invoice-insights', ['invoice' => $invoice])
    </div>
</div>

{{-- Print Styles --}}
<style>
@media print {
    body * { visibility: hidden; }
    #invoice-print, #invoice-print * { visibility: visible; }
    #invoice-print { position: absolute; left: 0; top: 0; width: 100%; }
}
</style>