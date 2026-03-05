<div>
    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-100 text-emerald-800 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center gap-2">
        <a href="{{ route('invoices.ai-draft') }}"
            class="flex items-center gap-2 px-4 py-2 border border-blue-600 text-blue-600 hover:bg-blue-50 rounded-lg font-medium transition-colors text-sm">
            <span class="material-symbols-outlined text-xl">auto_awesome</span>
            AI Draft
        </a>
        <a href="{{ route('invoices.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 font-medium transition-colors w-fit">
            <span class="material-symbols-outlined text-xl">add</span>
            New Invoice
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row gap-3">

            {{-- Search --}}
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search by invoice # or client..."
                    class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                />
            </div>

            {{-- Status Filter --}}
            <select wire:model.live="statusFilter"
                class="px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                <option value="">All Status</option>
                <option value="draft">Draft</option>
                <option value="sent">Sent</option>
                <option value="partial">Partial</option>
                <option value="paid">Paid</option>
                <option value="overdue">Overdue</option>
            </select>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Invoice #</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Client</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Amount</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Issue Date</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Due Date</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-blue-600">
                            <a href="{{ route('invoices.show', $invoice) }}" class="hover:underline">
                                #{{ $invoice->invoice_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="size-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs">
                                    {{ strtoupper(substr($invoice->client->name, 0, 2)) }}
                                </div>
                                <span>{{ $invoice->client->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-semibold">${{ number_format($invoice->total, 2) }}</td>
                        <td class="px-6 py-4">
                            <select wire:change="updateStatus({{ $invoice->id }}, $event.target.value)"
                                class="text-xs font-medium px-2 py-1 rounded-full border-0 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500/20
                                {{ $invoice->getStatusColorClass() }}">
                                <option value="draft"   {{ $invoice->status === 'draft'   ? 'selected' : '' }}>Draft</option>
                                <option value="sent"    {{ $invoice->status === 'sent'    ? 'selected' : '' }}>Sent</option>
                                <option value="partial" {{ $invoice->status === 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="paid"    {{ $invoice->status === 'paid'    ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ $invoice->status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $invoice->issue_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $invoice->due_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('invoices.show', $invoice) }}"
                                    class="text-slate-400 hover:text-blue-600 transition-colors">
                                    <span class="material-symbols-outlined text-xl">visibility</span>
                                </a>
                                <a href="{{ route('invoices.edit', $invoice) }}"
                                    class="text-slate-400 hover:text-amber-500 transition-colors">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </a>
                                <button wire:click="delete({{ $invoice->id }})"
                                    wire:confirm="Are you sure? This will permanently delete the invoice."
                                    class="text-slate-400 hover:text-red-500 transition-colors">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl block mb-2">description</span>
                            No invoices found. Create your first invoice!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($invoices->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>
