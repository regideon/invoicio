<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('clients.index') }}"
                class="flex items-center gap-2 text-slate-500 hover:text-slate-700 text-sm font-medium">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
            </a>
            <div class="size-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg">
                {{ strtoupper(substr($client->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $client->name }}</h1>
                <p class="text-slate-500 text-sm">{{ $client->company ?? $client->email ?? 'No company' }}</p>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center">
            <p class="text-2xl font-bold text-slate-900">{{ $client->invoices->count() }}</p>
            <p class="text-xs text-slate-500 mt-1">Total Invoices</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center">
            <p class="text-2xl font-bold text-emerald-600">${{ number_format($client->invoices->sum('total'), 2) }}</p>
            <p class="text-xs text-slate-500 mt-1">Total Value</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $client->invoices->where('status', 'paid')->count() }}</p>
            <p class="text-xs text-slate-500 mt-1">Paid Invoices</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center">
            <p class="text-2xl font-bold text-rose-600">{{ $client->invoices->where('status', 'overdue')->count() }}</p>
            <p class="text-xs text-slate-500 mt-1">Overdue</p>
        </div>
    </div>

    {{-- AI Client Insights --}}
    @livewire('a-i.client-insights', ['client' => $client])

    {{-- Invoice History --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-base font-semibold text-slate-700">Invoice History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Invoice #</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Amount</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Due Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($client->invoices as $invoice)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-blue-600">
                            <a href="{{ route('invoices.show', $invoice) }}" class="hover:underline">
                                #{{ $invoice->invoice_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 font-semibold">${{ number_format($invoice->total, 2) }}</td>
                        <td class="px-6 py-4">
                            @php
                                $sc = match($invoice->status) {
                                    'paid'    => 'bg-emerald-100 text-emerald-800',
                                    'sent'    => 'bg-blue-100 text-blue-800',
                                    'overdue' => 'bg-rose-100 text-rose-800',
                                    default   => 'bg-slate-100 text-slate-600',
                                };
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sc }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $invoice->due_date->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-sm">No invoices yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>