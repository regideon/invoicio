<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Dashboard Overview</h1>
            <p class="text-slate-500 text-sm">Welcome back, here's what's happening with your invoices today.</p>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 font-medium transition-colors w-fit">
            <span class="material-symbols-outlined">add</span>
            New Invoice
        </button>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">

        {{-- Total Revenue --}}
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-10 bg-blue-600/10 text-blue-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                </div>
                <span class="text-emerald-500 text-xs font-bold bg-emerald-500/10 px-2 py-1 rounded-full">+12.5%</span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Total Revenue</p>
            <p class="text-2xl font-bold mt-1">$128,430.00</p>
        </div>

        {{-- Paid Invoices --}}
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-10 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined">task_alt</span>
                </div>
                <span class="text-emerald-500 text-xs font-bold bg-emerald-500/10 px-2 py-1 rounded-full">+4.2%</span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Paid Invoices</p>
            <p class="text-2xl font-bold mt-1">$92,120.00</p>
        </div>

        {{-- Pending --}}
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-10 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined">pending_actions</span>
                </div>
                <span class="text-amber-500 text-xs font-bold bg-amber-500/10 px-2 py-1 rounded-full">+2.1%</span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Pending Amount</p>
            <p class="text-2xl font-bold mt-1">$24,310.00</p>
        </div>

        {{-- Overdue --}}
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="size-10 bg-rose-100 text-rose-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined">error_outline</span>
                </div>
                <span class="text-rose-500 text-xs font-bold bg-rose-500/10 px-2 py-1 rounded-full">-0.8%</span>
            </div>
            <p class="text-slate-500 text-sm font-medium">Overdue Amount</p>
            <p class="text-2xl font-bold mt-1">$12,000.00</p>
        </div>

    </div>

    {{-- Recent Invoices Table --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-bold">Recent Invoices</h3>
            <a href="#" class="text-blue-600 text-sm font-semibold hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Invoice #</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Client</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Amount</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Date</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                    $invoices = [
                        ['num' => 'INV-001', 'client' => 'Acme Corp',        'initials' => 'AC', 'amount' => '$2,500.00', 'status' => 'Paid',    'date' => 'Oct 24, 2023'],
                        ['num' => 'INV-002', 'client' => 'Global Solutions',  'initials' => 'GS', 'amount' => '$1,850.00', 'status' => 'Pending', 'date' => 'Oct 22, 2023'],
                        ['num' => 'INV-003', 'client' => 'Delta Tech',        'initials' => 'DT', 'amount' => '$4,200.00', 'status' => 'Overdue', 'date' => 'Oct 15, 2023'],
                        ['num' => 'INV-004', 'client' => 'Nexus Labs',        'initials' => 'NL', 'amount' => '$950.00',   'status' => 'Paid',    'date' => 'Oct 12, 2023'],
                    ];
                    @endphp

                    @foreach($invoices as $invoice)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-blue-600">#{{ $invoice['num'] }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="size-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-xs text-slate-600">
                                    {{ $invoice['initials'] }}
                                </div>
                                <span>{{ $invoice['client'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-semibold">{{ $invoice['amount'] }}</td>
                        <td class="px-6 py-4">
                            @if($invoice['status'] === 'Paid')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Paid</span>
                            @elseif($invoice['status'] === 'Pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pending</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Overdue</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $invoice['date'] }}</td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-slate-400 hover:text-slate-600">
                                <span class="material-symbols-outlined">more_horiz</span>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>