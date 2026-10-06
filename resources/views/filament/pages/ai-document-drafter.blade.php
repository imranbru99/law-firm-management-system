<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Input Configuration Card -->
        <div class="lg:col-span-5 space-y-6">
            <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 space-y-5">
                <div class="flex items-center space-x-3 pb-3 border-b border-gray-100 dark:border-gray-800">
                    <div class="p-2.5 bg-amber-500/10 text-amber-600 rounded-xl">
                        <x-heroicon-o-sparkles class="w-6 h-6" />
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white">Generative Legal Drafter</h2>
                        <p class="text-xs text-gray-500">2027 Autonomous Statutory Document Engine</p>
                    </div>
                </div>

                <!-- Template Selector -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-2">
                        Select Document Archetype
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(['Legal Notice', 'Bail Application', 'Non-Disclosure Agreement', 'Demand Letter', 'Commercial Injunction'] as $tmpl)
                            <button
                                type="button"
                                wire:click="$set('templateType', '{{ $tmpl }}')"
                                class="px-3 py-2 text-xs font-medium rounded-lg text-left transition-all border {{ $templateType === $tmpl ? 'bg-amber-500 text-white border-amber-600 shadow-sm' : 'bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-amber-400' }}"
                            >
                                {{ $tmpl }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Case Linking -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-2">
                        Link Existing Case (Optional)
                    </label>
                    <select
                        wire:model="caseId"
                        class="w-full text-sm rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-amber-500 focus:border-amber-500"
                    >
                        <option value="">-- No linked case (Standalone template) --</option>
                        @foreach(\App\Models\LegalCase::all() as $c)
                            <option value="{{ $c->id }}">{{ $c->case_no ?? 'Case #' . $c->id }}: {{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Prompt Instructions -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-2">
                        Factual Context, Claims & Relief Instructions
                    </label>
                    <textarea
                        wire:model="promptInput"
                        rows="6"
                        class="w-full text-sm rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-amber-500 focus:border-amber-500 p-3 leading-relaxed"
                        placeholder="Detail the monetary defaults, dates, statutory grounds, contract breach particulars..."
                    ></textarea>
                </div>

                <!-- Action Button -->
                <button
                    type="button"
                    wire:click="generate"
                    wire:loading.attr="disabled"
                    class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-semibold text-sm shadow-md shadow-amber-500/20 flex items-center justify-center space-x-2 transition-all cursor-pointer"
                >
                    <x-heroicon-o-bolt class="w-5 h-5" wire:loading.remove />
                    <span wire:loading.remove>Synthesize Legal Draft</span>
                    <span wire:loading class="flex items-center space-x-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span>Generating Draft...</span>
                    </span>
                </button>
            </div>
        </div>

        <!-- Output Preview Card -->
        <div class="lg:col-span-7 space-y-6">
            <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 min-h-[550px] flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                Output: {{ $templateType }}
                            </span>
                            @if(count($extractedClauses) > 0)
                                <span class="text-xs text-gray-500">({{ count($extractedClauses) }} Sections Identified)</span>
                            @endif
                        </div>

                        @if(!empty($generatedDraft))
                            <div class="flex items-center space-x-2">
                                <button
                                    type="button"
                                    onclick="navigator.clipboard.writeText(document.getElementById('draftContent').innerText); alert('Draft copied to clipboard!');"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors"
                                >
                                    Copy Text
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Clauses Pill Row -->
                    @if(count($extractedClauses) > 0)
                        <div class="flex flex-wrap gap-1.5 my-3">
                            @foreach($extractedClauses as $clause)
                                <span class="px-2 py-0.5 text-[11px] rounded bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 font-mono">
                                    § {{ $clause }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Content Display Area -->
                    <div class="mt-4">
                        @if(!empty($generatedDraft))
                            <div id="draftContent" class="p-5 bg-gray-50 dark:bg-gray-950 rounded-xl border border-gray-200 dark:border-gray-800 text-sm font-mono leading-relaxed whitespace-pre-wrap max-h-[460px] overflow-y-auto text-gray-900 dark:text-gray-100 select-text">
{{ $generatedDraft }}
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-24 text-center space-y-3 text-gray-400 dark:text-gray-600">
                                <x-heroicon-o-document-text class="w-16 h-16 stroke-1" />
                                <p class="text-sm">Configure parameters on the left and click <strong>Synthesize Legal Draft</strong> to generate statutory documents.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-xs text-gray-400">
                    <span>Engine: LexVanguard / Gemini 2027 Legal Model</span>
                    <span>Compliant with standard judicial filing rules</span>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
