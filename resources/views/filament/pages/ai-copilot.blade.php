<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Control Panel (Left) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 space-y-5">
                <div class="flex items-center space-x-3 pb-4 border-b border-gray-100 dark:border-gray-800">
                    <div class="p-2.5 bg-gradient-to-tr from-amber-500 to-amber-600 text-white rounded-xl shadow-sm">
                        <x-heroicon-o-cpu-chip class="w-6 h-6" />
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            LexVanguard Copilot
                            <span class="px-2 py-0.5 text-[10px] font-semibold bg-amber-500/10 text-amber-600 rounded-full border border-amber-500/20">2027 v5.2</span>
                        </h2>
                        <p class="text-xs text-gray-500">Autonomous Multi-Jurisdictional Judicial Intelligence</p>
                    </div>
                </div>

                <!-- Copilot Mode Selector -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-2">
                        Intelligence Mode
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <button
                            type="button"
                            wire:click="setMode('research')"
                            class="px-3 py-2.5 text-xs font-medium rounded-xl text-left transition-all border {{ $mode === 'research' ? 'bg-amber-500 text-white border-amber-600 shadow-sm' : 'bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-amber-400' }}"
                        >
                            🏛️ Legal Research
                        </button>
                        <button
                            type="button"
                            wire:click="setMode('cross_exam')"
                            class="px-3 py-2.5 text-xs font-medium rounded-xl text-left transition-all border {{ $mode === 'cross_exam' ? 'bg-amber-500 text-white border-amber-600 shadow-sm' : 'bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-amber-400' }}"
                        >
                            ⚔️ Cross-Examination
                        </button>
                        <button
                            type="button"
                            wire:click="setMode('precedents')"
                            class="px-3 py-2.5 text-xs font-medium rounded-xl text-left transition-all border {{ $mode === 'precedents' ? 'bg-amber-500 text-white border-amber-600 shadow-sm' : 'bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-amber-400' }}"
                        >
                            📚 Precedent Search
                        </button>
                        <button
                            type="button"
                            wire:click="setMode('objections')"
                            class="px-3 py-2.5 text-xs font-medium rounded-xl text-left transition-all border {{ $mode === 'objections' ? 'bg-amber-500 text-white border-amber-600 shadow-sm' : 'bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-amber-400' }}"
                        >
                            🛡️ Trial Objections
                        </button>
                    </div>
                </div>

                <!-- Jurisdiction Selector -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-2">
                        Governing Jurisdiction & Court Forum
                    </label>
                    <select
                        wire:model="jurisdiction"
                        class="w-full text-xs rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-amber-500 focus:border-amber-500 py-2.5"
                    >
                        @foreach($jurisdictionOptions as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Case Context Link -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-2">
                        Link Active Matter (Optional Context)
                    </label>
                    <select
                        wire:model="caseId"
                        class="w-full text-xs rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-amber-500 focus:border-amber-500 py-2.5"
                    >
                        <option value="">-- No matter linked (Generic Jurisprudence) --</option>
                        @foreach($this->cases as $c)
                            <option value="{{ $c->id }}">{{ $c->case_number ?? 'Matter #' . $c->id }}: {{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Mode Specific Inputs: Cross-Examination -->
                @if($mode === 'cross_exam')
                    <div class="p-3 bg-amber-500/5 dark:bg-amber-500/10 rounded-xl border border-amber-500/20 space-y-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Deponent / Witness Role
                            </label>
                            <input
                                type="text"
                                wire:model="targetWitness"
                                placeholder="e.g. Lead Technical Architect / Forensics Officer"
                                class="w-full text-xs rounded-lg border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white py-1.5 px-2.5"
                            />
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Adverse Theory to Impeach
                            </label>
                            <input
                                type="text"
                                wire:model="adverseTheory"
                                placeholder="e.g. Claims lack of knowledge regarding unauthorized data transfer"
                                class="w-full text-xs rounded-lg border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white py-1.5 px-2.5"
                            />
                        </div>
                    </div>
                @endif

                <!-- Inquiry / Facts Input -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-2">
                        Factual Proposition / Legal Question
                    </label>
                    <textarea
                        wire:model="query"
                        rows="4"
                        class="w-full text-xs leading-relaxed rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-amber-500 focus:border-amber-500 p-3"
                        placeholder="State legal issue, facts, or adversary's argument..."
                    ></textarea>
                </div>

                <!-- Quick Prompts Chips -->
                <div class="space-y-1.5">
                    <span class="text-[10px] font-medium text-gray-500 uppercase tracking-wider">Quick Presets:</span>
                    <div class="flex flex-wrap gap-1.5">
                        <button
                            type="button"
                            wire:click="$set('query', 'Enforceability of non-compete covenant post-employment under recent FTC guidelines and state jurisprudence.')"
                            class="px-2 py-1 text-[11px] rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-amber-100 hover:text-amber-800 transition"
                        >
                            Non-Compete Enforceability
                        </button>
                        <button
                            type="button"
                            wire:click="$set('query', 'Standard for Piercing Corporate Veil in Delaware Chancery Court for single-member LLC shell entity.')"
                            class="px-2 py-1 text-[11px] rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-amber-100 hover:text-amber-800 transition"
                        >
                            Delaware Veil Piercing
                        </button>
                        <button
                            type="button"
                            wire:click="$set('query', 'Interim injunction test under UK American Cyanamid guidelines for patent infringement.')"
                            class="px-2 py-1 text-[11px] rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-amber-100 hover:text-amber-800 transition"
                        >
                            UK Interim Injunction
                        </button>
                    </div>
                </div>

                <!-- Run Copilot CTA -->
                <button
                    type="button"
                    wire:click="askCopilot"
                    wire:loading.attr="disabled"
                    class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-semibold text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2 disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="askCopilot" class="flex items-center gap-2">
                        <x-heroicon-o-bolt class="w-5 h-5" />
                        Execute AI Analysis
                    </span>
                    <span wire:loading wire:target="askCopilot" class="flex items-center gap-2">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Synthesizing Multi-Jurisdiction Intelligence...
                    </span>
                </button>
            </div>
        </div>

        <!-- Intelligence Output Stream (Right) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 min-h-[580px] flex flex-col justify-between">
                <div>
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                                ● Judicial Research Active
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ $jurisdiction }}
                            </span>
                        </div>

                        @if(!empty($responseMarkdown))
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    onclick="navigator.clipboard.writeText(document.getElementById('ai-copilot-content').innerText); alert('Copied to clipboard!');"
                                    class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 transition"
                                >
                                    Copy Memo
                                </button>
                                <button
                                    type="button"
                                    onclick="window.print();"
                                    class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 hover:bg-amber-100 transition"
                                >
                                    Print Brief
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Content Display -->
                    <div class="mt-5">
                        @if(empty($responseMarkdown))
                            <div class="py-16 text-center space-y-4">
                                <div class="w-16 h-16 mx-auto rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-600">
                                    <x-heroicon-o-scale class="w-8 h-8" />
                                </div>
                                <div class="max-w-md mx-auto">
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Ready for Judicial Inquiry</h3>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Select a jurisdiction, choose an intelligence mode, and click <strong class="text-amber-600">Execute AI Analysis</strong> to review statutory frameworks, cross-examination question sequences, and cited precedents.
                                    </p>
                                </div>
                            </div>
                        @else
                            <div id="ai-copilot-content" class="prose dark:prose-invert max-w-none text-xs leading-relaxed text-gray-800 dark:text-gray-200 bg-gray-50/50 dark:bg-gray-950/40 p-5 rounded-xl border border-gray-100 dark:border-gray-800/80 font-mono whitespace-pre-wrap select-text">
{{ $responseMarkdown }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Citation Notice -->
                <div class="pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-[11px] text-gray-500">
                    <span>LexVanguard Judicial Intelligence Engine • ISO/IEC 42001 Compliant</span>
                    <span>Confidential Work Product • Attorney-Client Privileged</span>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
