<div>
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-100 text-emerald-800 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Payments</h1>
            <p class="text-slate-500 text-sm">Total collected: <span class="font-semibold text-emerald-600">${{ number_format($totalPayments, 2) }}</span></p>
        </div>
        <button wire:click="openModal"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 font-medium transition-colors w-fit">
            <span class="material-symbols-outlined text-xl">add</span>
            Record Payment
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row gap-3">

            {{-- Search --}}
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input wire:model.live.debounce.300ms="search"
                    type="text" placeholder="Search by invoice # or client..."
                    class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
            </div>

            {{-- Method Filter --}}
            <select wire:model.live="methodFilter"
                class="px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                <option value="">All Methods</option>
                <option value="cash">Cash</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="check">Check</option>
                <option value="credit_card">Credit Card</option>
                <option value="online">Online</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Invoice</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Client</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Amount</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Method</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Date</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Reference</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-blue-600">
                            <a href="{{ route('invoices.show', $payment->invoice) }}" class="hover:underline">
                                #{{ $payment->invoice->invoice_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="size-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs">
                                    {{ strtoupper(substr($payment->invoice->client->name, 0, 2)) }}
                                </div>
                                <span>{{ $payment->invoice->client->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-emerald-600">${{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                {{ $payment->method_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $payment->reference ?? '—' }}</td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="delete({{ $payment->id }})"
                                wire:confirm="Are you sure you want to delete this payment?"
                                class="text-slate-400 hover:text-red-500 transition-colors">
                                <span class="material-symbols-outlined text-xl">delete</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl block mb-2">payments</span>
                            No payments recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $payments->links() }}
        </div>
        @endif
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" wire:click="$set('showModal', false)"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 z-10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold">Record Payment</h2>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="space-y-4">

                {{-- Invoice --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Invoice <span class="text-red-500">*</span></label>
                    <select wire:model="invoice_id"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <option value="">Select invoice...</option>
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}">
                                #{{ $invoice->invoice_number }} — {{ $invoice->client->name }} (${{ number_format($invoice->total, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('invoice_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Amount --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Amount <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">$</span>
                        <input wire:model="amount" type="number" step="0.01" min="0.01" placeholder="0.00"
                            class="w-full pl-7 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                    </div>
                    @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Payment Date --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Payment Date <span class="text-red-500">*</span></label>
                    <input wire:model="payment_date" type="date"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                    @error('payment_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Method --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Payment Method <span class="text-red-500">*</span></label>
                    <select wire:model="method"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="check">Check</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="online">Online</option>
                    </select>
                </div>

                {{-- Reference --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Reference #</label>
                    <input wire:model="reference" type="text" placeholder="Transaction ID, Check #, etc."
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
                    <textarea wire:model="notes" rows="2" placeholder="Optional notes..."
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button wire:click="$set('showModal', false)"
                    class="px-4 py-2 text-sm font-medium text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button wire:click="save"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    Record Payment
                </button>
            </div>
        </div>
    </div>
    @endif
</div>