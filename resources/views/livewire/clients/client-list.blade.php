
<div>
    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-100 text-emerald-800 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Clients</h1>
            <p class="text-slate-500 text-sm">Manage your clients</p>
        </div>
        <button wire:click="openCreateModal"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 font-medium transition-colors w-fit">
            <span class="material-symbols-outlined text-xl">add</span>
            New Client
        </button>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search clients..."
                    class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                />
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Client</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Email</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Phone</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Company</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($clients as $client)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="size-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs">
                                    {{ strtoupper(substr($client->name, 0, 2)) }}
                                </div>
                                <span class="font-medium">{{ $client->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $client->email ?? '—' }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $client->phone ?? '—' }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $client->company ?? '—' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('clients.show', $client) }}"
                                    class="text-slate-400 hover:text-purple-600 transition-colors">
                                    <span class="material-symbols-outlined text-xl">visibility</span>
                                </a>
                                <button wire:click="openEditModal({{ $client->id }})"
                                    class="text-slate-400 hover:text-blue-600 transition-colors">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </button>
                                
                                <button wire:click="delete({{ $client->id }})"
                                    wire:confirm="Are you sure you want to delete this client?"
                                    class="text-slate-400 hover:text-red-500 transition-colors">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl block mb-2">group</span>
                            No clients found. Add your first client!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($clients->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $clients->links() }}
        </div>
        @endif
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50" wire:click="$set('showModal', false)"></div>

        {{-- Modal Card --}}
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 z-10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold">{{ $isEditing ? 'Edit Client' : 'New Client' }}</h2>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="space-y-4">
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input wire:model="name" type="text" placeholder="John Doe"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input wire:model="email" type="email" placeholder="john@company.com"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
                    <input wire:model="phone" type="text" placeholder="+1 234 567 890"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                </div>

                {{-- Company --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Company</label>
                    <input wire:model="company" type="text" placeholder="Acme Corp"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                </div>

                {{-- Address --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Address</label>
                    <textarea wire:model="address" rows="2" placeholder="123 Main St, City"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-3 mt-6">
                <button wire:click="$set('showModal', false)"
                    class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button wire:click="save"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    {{ $isEditing ? 'Update Client' : 'Create Client' }}
                </button>

                
            </div>
        </div>
    </div>
    @endif
</div>