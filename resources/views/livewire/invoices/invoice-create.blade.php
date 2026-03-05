<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Create Invoice</h1>
            <p class="text-slate-500 text-sm">Fill in the details below</p>
        </div>
        <a href="{{ route('invoices.index') }}"
            class="flex items-center gap-2 text-slate-500 hover:text-slate-700 text-sm font-medium">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
            Back to Invoices
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT COLUMN - Main Form --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Invoice Info Card --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-base font-semibold mb-4 text-slate-700">Invoice Details</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Invoice Number --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Invoice Number</label>
                        <p class="text-sm font-bold text-slate-600">It will be auto-generated on save</p>
                    </div>
                    {{-- <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Invoice Number</label>
                        <input wire:model="invoice_number" type="text"
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-slate-50" />
                        @error('invoice_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div> --}}

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                        <select wire:model="status"
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            <option value="draft">Draft</option>
                            <option value="sent">Sent</option>
                            <option value="partial">Partial</option>
                            <option value="paid">Paid</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>

                    {{-- Client --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Client <span class="text-red-500">*</span></label>
                        <select wire:model="client_id"
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            <option value="">Select a client...</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tax Rate --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tax Rate (%)</label>
                        <div class="relative">
                            <input wire:model.live="tax_rate" type="number" step="0.01" min="0" max="100" placeholder="0"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">%</span>
                        </div>
                    </div>

                    {{-- Issue Date --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Issue Date <span class="text-red-500">*</span></label>
                        <input wire:model="issue_date" type="date"
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                        @error('issue_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Due Date --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Due Date <span class="text-red-500">*</span></label>
                        <input wire:model="due_date" type="date"
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                        @error('due_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Line Items Card --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-slate-700">Line Items</h2>
                    <button wire:click="addItem" type="button"
                        class="flex items-center gap-1 text-blue-600 hover:text-blue-700 text-sm font-medium">
                        <span class="material-symbols-outlined text-xl">add</span>
                        Add Item
                    </button>
                </div>

                @error('items') <p class="text-red-500 text-xs mb-3">{{ $message }}</p> @enderror

                <div class="space-y-4">
                    @foreach($items as $index => $item)
                    <div class="border border-slate-200 rounded-lg p-4 relative">

                        {{-- Remove button --}}
                        @if(count($items) > 1)
                        <button wire:click="removeItem({{ $index }})" type="button"
                            class="absolute top-3 right-3 text-slate-300 hover:text-red-500 transition-colors">
                            <span class="material-symbols-outlined text-xl">close</span>
                        </button>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                            {{-- Product Selector --}}
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-slate-500 mb-1">Product <span class="text-red-500">*</span></label>
                                <select wire:model="items.{{ $index }}.product_id"
                                    wire:change="productSelected({{ $index }})"
                                    class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                    <option value="">Select a product or type manually...</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} — ${{ number_format($product->price, 2) }}</option>
                                    @endforeach
                                </select>
                            </div>


                            {{-- Description --}}
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-slate-500 mb-1">Description</label>
                                <input wire:model="items.{{ $index }}.description" type="text" placeholder="Optional description"
                                    class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                            </div>

                            {{-- Price --}}
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1">Price <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">$</span>
                                    <input wire:model.live="items.{{ $index }}.price" type="number" step="0.01" min="0" placeholder="0.00"
                                        class="w-full pl-7 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                                </div>
                                @error("items.{$index}.price") <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Quantity --}}
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1">Quantity <span class="text-red-500">*</span></label>
                                <input wire:model.live="items.{{ $index }}.quantity" type="number" step="1" min="1" placeholder="1"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                                @error("items.{$index}.quantity") <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Line Total --}}
                        <div class="flex justify-end">
                            <div class="text-sm font-semibold text-slate-700">
                                Line Total: <span class="text-blue-600">${{ number_format($item['total'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Notes --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-base font-semibold mb-4 text-slate-700">Notes</h2>
                <textarea wire:model="notes" rows="3" placeholder="Any additional notes for the client..."
                    class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
            </div>
        </div>

        {{-- RIGHT COLUMN - Summary --}}
        <div class="space-y-6">

            {{-- Totals Card --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 sticky top-24">
                <h2 class="text-base font-semibold mb-4 text-slate-700">Summary</h2>

                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Subtotal</span>
                        <span class="font-medium">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Tax ({{ $tax_rate }}%)</span>
                        <span class="font-medium">${{ number_format($tax_amount, 2) }}</span>
                    </div>
                    <div class="border-t border-slate-200 pt-3 flex justify-between">
                        <span class="font-bold text-slate-900">Total</span>
                        <span class="font-bold text-blue-600 text-lg">${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <button wire:click="save" type="button"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold transition-colors">
                        Save Invoice
                    </button>
                    <a href="{{ route('invoices.index') }}"
                        class="block w-full text-center border border-slate-200 text-slate-600 hover:bg-slate-50 py-2.5 rounded-lg font-medium transition-colors text-sm">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
