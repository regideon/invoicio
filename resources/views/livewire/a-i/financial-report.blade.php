<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">AI Financial Report</h1>
            <p class="text-slate-500 text-sm">Claude analyzes your data and generates a complete financial report</p>
        </div>
    </div>

    {{-- Controls Card --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">

            {{-- Period Selector --}}
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-2">Report Period</label>
                <select wire:model.live="period"
                    class="w-full sm:w-64 px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    @foreach($periods as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Generate Button --}}
            <div class="flex items-center gap-3 sm:mt-6">
                @if($generatedAt)
                    <p class="text-xs text-slate-400">Last generated: {{ $generatedAt }}</p>
                @endif

                <button
                    wire:click="generate"
                    wire:loading.attr="disabled"
                    wire:target="generate"
                    class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-colors disabled:opacity-50">
                    <span class="material-symbols-outlined text-xl"
                        wire:loading.class="animate-spin"
                        wire:target="generate">
                        auto_awesome
                    </span>
                    <span wire:loading.remove wire:target="generate">
                        {{ $generated ? 'Regenerate Report' : 'Generate Report' }}
                    </span>
                    <span wire:loading wire:target="generate">Generating...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Loading State --}}
    @if($loading)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-12 text-center">
            <div class="animate-spin size-10 border-4 border-blue-600 border-t-transparent rounded-full mx-auto mb-4"></div>
            <p class="text-slate-600 font-medium">Claude is analyzing your financial data...</p>
            <p class="text-slate-400 text-sm mt-1">This may take a few seconds</p>
        </div>

    {{-- Empty State --}}
    @elseif(!$generated)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-12 text-center">
            <span class="material-symbols-outlined text-5xl text-slate-300 block mb-4">analytics</span>
            <h3 class="text-lg font-semibold text-slate-700 mb-2">No Report Generated Yet</h3>
            <p class="text-slate-400 text-sm">Select a period and click "Generate Report" to get your AI-powered financial analysis.</p>
        </div>

    {{-- Report --}}
    @else
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-8">
            <div class="prose prose-sm max-w-none
                prose-headings:text-slate-800 prose-headings:font-bold
                prose-h1:text-xl prose-h2:text-lg prose-h3:text-base
                prose-p:text-slate-600 prose-p:leading-relaxed
                prose-li:text-slate-600
                prose-strong:text-slate-800
                prose-ul:space-y-1 prose-ol:space-y-1
                prose-table:w-full prose-th:text-left prose-th:py-2 prose-td:py-2">
                {!! $insightsHtml !!}
            </div>
        </div>
    @endif

</div>