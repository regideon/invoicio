<div>
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="size-8 bg-purple-600/10 text-purple-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">psychology</span>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-slate-700">AI Client Analysis</h3>
                    <p class="text-xs text-slate-400">Payment behavior & recommendations</p>
                    @if($generatedAt)
                        <p class="text-xs text-slate-400 mt-0.5">Last generated: {{ $generatedAt }}</p>
                    @endif
                </div>
            </div>

            @if(!$generated)
                <button
                    wire:click="generate"
                    wire:loading.attr="disabled"
                    wire:target="generate"
                    class="flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition-colors disabled:opacity-50">
                    <span class="material-symbols-outlined text-xl">auto_awesome</span>
                    <span wire:loading.remove wire:target="generate">Analyze Client</span>
                    <span wire:loading wire:target="generate">Analyzing...</span>
                </button>
            @else
                <button
                    wire:click="generate"
                    wire:loading.attr="disabled"
                    wire:target="generate"
                    class="flex items-center gap-2 px-3 py-1.5 border border-slate-200 text-slate-500 hover:bg-slate-50 rounded-lg text-sm transition-colors">
                    <span class="material-symbols-outlined text-lg">refresh</span>
                    <span wire:loading.remove wire:target="generate">Regenerate</span>
                    <span wire:loading wire:target="generate">Analyzing...</span>
                </button>
            @endif
        </div>

        {{-- Loading --}}
        @if($loading)
            <div class="flex items-center gap-3 py-6 text-slate-400">
                <div class="animate-spin size-5 border-2 border-purple-600 border-t-transparent rounded-full"></div>
                <p class="text-sm">Claude is analyzing {{ $client->name }}'s payment history...</p>
            </div>

        {{-- Empty State --}}
        @elseif(!$generated)
            <div class="py-6 text-center text-slate-400">
                <span class="material-symbols-outlined text-4xl block mb-2">psychology</span>
                <p class="text-sm">Click "Analyze Client" to get AI-powered payment behavior analysis.</p>
                <p class="text-xs mt-1 text-slate-300">Based on {{ $client->invoices->count() }} invoice(s)</p>
            </div>

        {{-- Results --}}
        @else
            <div class="bg-slate-50 rounded-lg p-4 text-sm leading-relaxed prose prose-sm max-w-none
                prose-headings:text-slate-800 prose-headings:font-bold
                prose-h1:text-lg prose-h2:text-base prose-h3:text-sm
                prose-p:text-slate-600 prose-li:text-slate-600
                prose-strong:text-slate-800
                prose-ul:space-y-1 prose-ol:space-y-1">
                {!! $insightsHtml !!}
            </div>
        @endif
    </div>
</div>