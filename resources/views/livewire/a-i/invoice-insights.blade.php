<div>
    {{-- 
        This component is embedded inside the invoice show page.
        It's NOT a full page — just a section/card.
    --}}

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                {{-- Robot/AI icon --}}
                <div class="size-8 bg-blue-600/10 text-blue-600 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">smart_toy</span>
                </div>
                <h3 class="text-base font-semibold text-slate-700">AI Invoice Insights</h3>
                @if($generatedAt)
                    <p class="text-xs text-slate-400 mt-0.5">Last generated: {{ $generatedAt }}</p>
                @endif
            </div>

            {{-- Generate Button --}}
            @if(!$generated)
                <button
                    wire:click="generate"
                    wire:loading.attr="disabled"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors disabled:opacity-50">
                    <span class="material-symbols-outlined text-xl"
                        wire:loading.class="animate-spin"
                        wire:loading.attr="style"
                        wire:target="generate">
                        {{ $loading ? 'refresh' : 'auto_awesome' }}
                    </span>
                    {{ $loading ? 'Analyzing...' : 'Generate Insights' }}
                </button>
            @else
                <button
                    wire:click="generate"
                    class="flex items-center gap-2 px-3 py-1.5 border border-slate-200 text-slate-500 hover:bg-slate-50 rounded-lg text-sm transition-colors">
                    <span class="material-symbols-outlined text-lg">refresh</span>
                    Regenerate
                </button>
            @endif
        </div>

        {{-- Loading State --}}
        @if($loading)
            <div class="flex items-center gap-3 py-6 text-slate-400">
                <div class="animate-spin size-5 border-2 border-blue-600 border-t-transparent rounded-full"></div>
                <p class="text-sm">Claude is analyzing your invoice...</p>
            </div>

        {{-- Empty State - before clicking --}}
        @elseif(!$generated)
            <div class="py-6 text-center text-slate-400">
                <span class="material-symbols-outlined text-4xl block mb-2">auto_awesome</span>
                <p class="text-sm">Click "Generate Insights" to get AI-powered analysis of this invoice.</p>
            </div>

        {{-- Results --}}
        @else
            <div class="bg-slate-50 rounded-lg p-4 text-sm leading-relaxed prose prose-sm max-w-none
                prose-headings:text-slate-800 prose-headings:font-bold
                prose-h1:text-lg prose-h2:text-base prose-h3:text-sm
                prose-p:text-slate-600 prose-p:leading-relaxed
                prose-li:text-slate-600
                prose-strong:text-slate-800
                prose-ul:space-y-1 prose-ol:space-y-1">
                {!! $insightsHtml !!}
            </div>
        @endif
    </div>
</div>