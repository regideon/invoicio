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
            <h1 class="text-2xl font-bold text-slate-900">Products & Services</h1>
            <p class="text-slate-500 text-sm">Manage your products and services</p>
        </div>
        <button wire:click="openCreateModal"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 font-medium transition-colors w-fit">
            <span class="material-symbols-outlined text-xl">add</span>
            New Product
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search products..."
                    class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Product</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Description</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Price</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Unit</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $product)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="size-9 rounded-lg bg-blue-600/10 text-blue-600 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-xl">inventory_2</span>
                                </div>
                                <span class="font-medium">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-500 max-w-xs truncate">{{ $product->description ?? '—' }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-900">${{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                {{ $product->unit }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="openEditModal({{ $product->id }})"
                                    class="text-slate-400 hover:text-blue-600 transition-colors">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </button>
                                <button wire:click="delete({{ $product->id }})"
                                    wire:confirm="Are you sure you want to delete this product?"
                                    class="text-slate-400 hover:text-red-500 transition-colors">
                                    <span class="material-symbols-outlined text-xl">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl block mb-2">inventory_2</span>
                            No products found. Add your first product!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $products->links() }}
        </div>
        @endif
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" wire:click="$set('showModal', false)"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 z-10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold">{{ $isEditing ? 'Edit Product' : 'New Product' }}</h2>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="space-y-4">
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input wire:model="name" type="text" placeholder="Web Design Service"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                    <textarea wire:model="description" rows="2" placeholder="Brief description..."
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
                </div>

                {{-- Price + Unit --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Price <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">$</span>
                            <input wire:model="price" type="number" step="0.01" min="0" placeholder="0.00"
                                class="w-full pl-7 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                        </div>
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Unit <span class="text-red-500">*</span></label>
                        <select wire:model="unit"
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            @foreach($units as $u)
                                <option value="{{ $u }}">{{ strtoupper($u) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-3 mt-6">
                <button wire:click="$set('showModal', false)"
                    class="px-4 py-2 text-sm font-medium text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button wire:click="save"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    {{ $isEditing ? 'Update Product' : 'Create Product' }}
                </button>
            </div>
        </div>
    </div>
    @endif
</div>