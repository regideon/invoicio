<div class="space-y-8">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Dashboard Overview</h1>
            <p class="text-slate-500 text-sm">Welcome back, {{ auth()->user()->name }}! Here's what's happening today.</p>
        </div>
        <a href="{{ route('invoices.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 font-medium transition-colors w-fit">
            <span class="material-symbols-outlined">add</span>
            New Invoice
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-10 bg-blue-600/10 text-blue-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                </div>
                <span class="text-xs font-bold px-2 py-1 rounded-full bg-slate-100 text-slate-600">
                    {{ $totalInvoices }} total
                </span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Total Revenue</p>
            <p class="text-2xl font-bold mt-1">${{ number_format($totalRevenue, 2) }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-10 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined">task_alt</span>
                </div>
                <span class="text-xs font-bold px-2 py-1 rounded-full bg-emerald-500/10 text-emerald-600">
                    {{ $paidInvoices }} invoices
                </span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Paid</p>
            <p class="text-2xl font-bold mt-1">${{ number_format($paidAmount, 2) }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-10 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined">pending_actions</span>
                </div>
                <span class="text-xs font-bold px-2 py-1 rounded-full bg-amber-500/10 text-amber-600">
                    {{ $pendingInvoices }} invoices
                </span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Pending</p>
            <p class="text-2xl font-bold mt-1">${{ number_format($pendingAmount, 2) }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-10 bg-rose-100 text-rose-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined">error_outline</span>
                </div>
                <span class="text-xs font-bold px-2 py-1 rounded-full bg-rose-500/10 text-rose-600">
                    {{ $overdueInvoices }} invoices
                </span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Overdue</p>
            <p class="text-2xl font-bold mt-1">${{ number_format($overdueAmount, 2) }}</p>
        </div>

    </div>

    {{-- Recent Invoices --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-bold">Recent Invoices</h3>
            <a href="{{ route('invoices.index') }}" class="text-blue-600 text-sm font-semibold hover:underline">
                View All
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Invoice #</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Client</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Amount</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Due Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentInvoices as $invoice)
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
                            @php
                                $statusClass = match($invoice->status) {
                                    'paid'    => 'bg-emerald-100 text-emerald-800',
                                    'sent'    => 'bg-blue-100 text-blue-800',
                                    'overdue' => 'bg-rose-100 text-rose-800',
                                    default   => 'bg-slate-100 text-slate-600',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $invoice->due_date->format('M d, Y') }}
                            @if($invoice->due_date->isPast() && $invoice->status !== 'paid')
                                <span class="text-rose-500 text-xs ml-1">⚠</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl block mb-2">description</span>
                            No invoices yet.
                            <a href="{{ route('invoices.create') }}" class="text-blue-600 hover:underline">Create your first invoice!</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>