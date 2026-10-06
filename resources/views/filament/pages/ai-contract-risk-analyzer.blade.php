<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Input Contract Source Card -->
        <div class="lg:col-span-5 space-y-6">
            <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 space-y-5">
                <div class="flex items-center space-x-3 pb-3 border-b border-gray-100 dark:border-gray-800">
                    <div class="p-2.5 bg-blue-500/10 text-blue-600 rounded-xl">
                        <x-heroicon-o-shield-check class="w-6 h-6" />
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white">Clause Risk Engine</h2>
                        <p class="text-xs text-gray-500">Autonomous Indemnity & Liability Redlining</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-2">
                        Agreement Identifier / Title
                    </label>
                    <input
                        type="text"
                        wire:model="documentTitle"
                        class="w-full text-sm rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 p-2.5"
                    />
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-2">
                        Contractual Clauses (Paste Text)
                    </label>
                    <textarea
                        wire:model="contractText"
                        rows="12"
                        class="w-full text-xs font-mono rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 p-3 leading-relaxed"
                        placeholder="Paste agreement text containing indemnity, limitation of liability, termination or dispute resolution clauses..."
                    ></textarea>
                </div>

                <button
                    type="button"
                    wire:click="analyze"
                    wire:loading.attr="disabled"
                    class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold text-sm shadow-md shadow-blue-500/20 flex items-center justify-center space-x-2 transition-all cursor-pointer"
                >
                    <x-heroicon-o-sparkles class="w-5 h-5" wire:loading.remove />
                    <span wire:loading.remove>Run AI Risk Audit</span>
                    <span wire:loading class="flex items-center space-x-2">
                        <span>Analyzing Contract Clauses...</span>
                    </span>
                </button>
            </div>
        </div>

        <!-- Risk Audit Report Card -->
        <div class="lg:col-span-7 space-y-6">
            <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 space-y-6 min-h-[550px]">
                @if($hasAnalyzed)
                    <!-- Risk Score Banner -->
                    <div class="p-5 rounded-2xl border flex items-center justify-between {{ $riskScore > 60 ? 'bg-red-50/50 border-red-200 dark:bg-red-950/20 dark:border-red-900/50' : ($riskScore > 35 ? 'bg-amber-50/50 border-amber-200 dark:bg-amber-950/20 dark:border-amber-900/50' : 'bg-emerald-50/50 border-emerald-200 dark:bg-emerald-950/20 dark:border-emerald-900/50') }}">
                        <div>
                            <span class="text-xs uppercase tracking-wider font-semibold {{ $riskScore > 60 ? 'text-red-700 dark:text-red-400' : ($riskScore > 35 ? 'text-amber-700 dark:text-amber-400' : 'text-emerald-700 dark:text-emerald-400') }}">
                                Overall Liability Risk Score
                            </span>
                            <div class="text-3xl font-extrabold mt-1 {{ $riskScore > 60 ? 'text-red-600' : ($riskScore > 35 ? 'text-amber-600' : 'text-emerald-600') }}">
                                {{ $riskScore }} <span class="text-sm font-normal text-gray-500">/ 100</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $riskScore > 60 ? 'CRITICAL EXPOSURE: Significant un-capped liability or aggressive unilateral terms detected.' : ($riskScore > 35 ? 'MODERATE RISK: Standard negotiation redlines required prior to signing.' : 'FAVORABLE / LOW RISK: Protective caps and balanced covenants detected.') }}
                            </p>
                        </div>

                        <div class="w-16 h-16 rounded-full flex items-center justify-center border-4 {{ $riskScore > 60 ? 'border-red-500 text-red-500' : ($riskScore > 35 ? 'border-amber-500 text-amber-500' : 'border-emerald-500 text-emerald-500') }} font-extrabold text-xl">
                            {{ $riskScore }}%
                        </div>
                    </div>

                    <!-- Clause Redline Breakdown -->
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-3">
                            Clause Analysis & Proposed Redlines
                        </h3>
                        <div class="space-y-3">
                            @foreach($detectedClauses as $item)
                                <div class="p-4 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-800/40 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ $item['clause'] }}
                                        </span>
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $item['risk'] === 'High' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : ($item['risk'] === 'Medium' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400') }}">
                                            {{ $item['risk'] }} Risk
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-300">
                                        <strong>Finding:</strong> {{ $item['finding'] }}
                                    </p>
                                    <div class="p-2.5 bg-blue-50/50 dark:bg-blue-950/20 rounded-lg border border-blue-100 dark:border-blue-900/40 text-xs text-blue-900 dark:text-blue-300 font-mono">
                                        <strong class="font-sans">Redline Counterproposal:</strong> {{ $item['redline'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Strategic Recommendations -->
                    @if(!empty($recommendations))
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700">
                            <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                                Counsel Negotiation Strategy
                            </h4>
                            <div class="text-xs text-gray-600 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">
{{ $recommendations }}
                            </div>
                        </div>
                    @endif
                @else
                    <div class="flex flex-col items-center justify-center py-28 text-center space-y-3 text-gray-400 dark:text-gray-600">
                        <x-heroicon-o-document-magnifying-glass class="w-16 h-16 stroke-1" />
                        <p class="text-sm">Paste contract text on the left and click <strong>Run AI Risk Audit</strong> to generate clause liability score and redlines.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
