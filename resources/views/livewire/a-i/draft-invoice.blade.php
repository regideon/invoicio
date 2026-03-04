<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">AI Draft Invoice</h1>
        <p class="text-slate-500 text-sm">Describe your invoice in plain English and Claude will fill it in for you</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="px-4 py-3 bg-rose-100 text-rose-800 rounded-lg text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    {{-- Description Input Card --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-4">
            <div class="size-8 bg-blue-600/10 text-blue-600 rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">smart_toy</span>
            </div>
            <h2 class="text-base font-semibold text-slate-700">Describe Your Invoice</h2>
        </div>

        {{-- Text Input --}}
        <textarea
            wire:model="description"
            rows="3"
            placeholder="e.g. Web design for Shell Philippines, 10 hours at $150 per hour, 12% tax, due in 30 days"
            class="w-full px-4 py-3 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 resize-none">
        </textarea>
        @error('description')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror

        {{-- Example Prompts --}}
        <div class="mt-3">
            <p class="text-xs text-slate-400 mb-2">💡 Try an example:</p>
            <div class="flex flex-wrap gap-2">
                @foreach($examples as $example)
                    <button wire:click="useExample('{{ $example }}')"
                        class="text-xs px-3 py-1.5 bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 rounded-full transition-colors border border-slate-200 text-left">
                        {{ Str::limit($example, 50) }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Generate Button --}}
        <div class="mt-4 flex justify-end">
            <button
                wire:click="generate"
                wire:loading.attr="disabled"
                wire:target="generate"
                class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-colors disabled:opacity-50">
                <span class="material-symbols-outlined text-xl">auto_awesome</span>
                <span wire:loading.remove wire:target="generate">Draft with AI</span>
                <span wire:loading wire:target="generate">Claude is thinking...</span>
            </button>
        </div>
    </div>

    {{-- Loading --}}
    @if($loading)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-12 text-center">
            <div class="animate-spin size-10 border-4 border-blue-600 border-t-transparent rounded-full mx-auto mb-4"></div>
            <p class="text-slate-600 font-medium">Claude is drafting your invoice...</p>
            <p class="text-slate-400 text-sm mt-1">Reading your description and filling in the details</p>
        </div>

    {{-- Drafted Invoice Form --}}
    @elseif($drafted)

        {{-- AI Summary --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex gap-3">
            <span class="material-symbols-outlined text-blue-600 mt-0.5">smart_toy</span>
            <div class="text-sm text-blue-800 prose prose-sm max-w-none">
                {!! $aiSummaryHtml !!}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT - Form --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Invoice Details --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h2 class="text-base font-semibold mb-4 text-slate-700">Invoice Details
                        <span class="text-xs text-blue-600 font-normal ml-2">✨ Pre-filled by AI — review and adjust</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Client --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Client <span class="text-red-500">*</span>
                                @if(isset($draftData['client_name_suggestion']) && !$client_id)
                                    <span class="text-amber-500 text-xs ml-2">
                                        ⚠ Claude suggested "{{ $draftData['client_name_suggestion'] }}" — please select manually
                                    </span>
                                @endif
                            </label>
                            <select wire:model="client_id"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                <option value="">Select a client...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                            @error('client_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                            <select wire:model="status"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                <option value="draft">Draft</option>
                                <option value="sent">Sent</option>
                                <option value="paid">Paid</option>
                                <option value="overdue">Overdue</option>
                            </select>
                        </div>

                        {{-- Tax Rate --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tax Rate (%)</label>
                            <div class="relative">
                                <input wire:model.live="tax_rate" type="number" step="0.01" min="0" max="100"
                                    class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">%</span>
                            </div>
                        </div>

                        {{-- Issue Date --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Issue Date</label>
                            <input wire:model="issue_date" type="date"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                        </div>

                        {{-- Due Date --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Due Date</label>
                            <input wire:model="due_date" type="date"
                                class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                        </div>
                    </div>
                </div>

                {{-- Line Items --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-semibold text-slate-700">Line Items</h2>
                        <button wire:click="addItem" type="button"
                            class="flex items-center gap-1 text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <span class="material-symbols-outlined text-xl">add</span>
                            Add Item
                        </button>
                    </div>

                    <div class="space-y-4">
                        @foreach($items as $index => $item)
                        <div class="border border-slate-200 rounded-lg p-4 relative">

                            @if(count($items) > 1)
                            <button wire:click="removeItem({{ $index }})" type="button"
                                class="absolute top-3 right-3 text-slate-300 hover:text-red-500 transition-colors">
                                <span class="material-symbols-outlined text-xl">close</span>
                            </button>
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">

                                {{-- Name --}}
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Item Name <span class="text-red-500">*</span></label>
                                    <input wire:model="items.{{ $index }}.name" type="text"
                                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                                    @error("items.{$index}.name") <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Description --}}
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Description</label>
                                    <input wire:model="items.{{ $index }}.description" type="text"
                                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                                </div>

                                {{-- Price --}}
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Price <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">$</span>
                                        <input wire:model.live="items.{{ $index }}.price" type="number" step="0.01" min="0"
                                            class="w-full pl-7 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                                    </div>
                                    @error("items.{$index}.price") <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Quantity --}}
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Quantity <span class="text-red-500">*</span></label>
                                    <input wire:model.live="items.{{ $index }}.quantity" type="number" step="1" min="1"
                                        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" />
                                    @error("items.{$index}.quantity") <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Line Total --}}
                            <div class="flex justify-end">
                                <span class="text-sm font-semibold text-slate-700">
                                    Line Total: <span class="text-blue-600">${{ number_format($item['total'], 2) }}</span>
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Notes --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h2 class="text-base font-semibold mb-4 text-slate-700">Notes</h2>
                    <textarea wire:model="notes" rows="3"
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"></textarea>
                </div>
            </div>

            {{-- RIGHT - Summary --}}
            <div>
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
                        <button wire:click="$set('drafted', false)" type="button"
                            class="w-full border border-slate-200 text-slate-600 hover:bg-slate-50 py-2.5 rounded-lg font-medium transition-colors text-sm">
                            Start Over
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>