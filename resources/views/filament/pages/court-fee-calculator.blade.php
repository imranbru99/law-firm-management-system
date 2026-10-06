<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-5 space-y-6">
            <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 space-y-5">
                <div class="flex items-center space-x-3 pb-3 border-b border-gray-100 dark:border-gray-800">
                    <div class="p-2.5 bg-emerald-500/10 text-emerald-600 rounded-xl">
                        <x-heroicon-o-calculator class="w-6 h-6" />
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white">Ad-Valorem Fee Engine</h2>
                        <p class="text-xs text-gray-500">Statutory Court Fees Act Computation</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-2">
                        Nature of Suit / Cause of Action
                    </label>
                    <select
                        wire:model="suitType"
                        wire:change="calculate"
                        class="w-full text-sm rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                    >
                        <option value="Money Suit for Recovery">Money Suit for Recovery</option>
                        <option value="Suit for Damages / Breach of Contract">Suit for Damages / Breach of Contract</option>
                        <option value="Suit for Permanent Injunction">Suit for Permanent Injunction</option>
                        <option value="Declaratory Decree without Consequential Relief">Declaratory Decree without Consequential Relief</option>
                        <option value="Partition Suit">Partition Suit</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-2">
                        Suit Valuation / Claim Amount ($)
                    </label>
                    <input
                        type="number"
                        wire:model="claimAmount"
                        wire:input="calculate"
                        class="w-full text-lg font-bold rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-emerald-500 focus:border-emerald-500 p-2.5"
                    />
                </div>

                <div class="p-4 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/40 text-xs text-emerald-900 dark:text-emerald-300">
                    Court fee updates in real-time as you type the claim valuation.
                </div>
            </div>
        </div>

        <div class="lg:col-span-7 space-y-6">
            <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 space-y-6">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Calculated Court Fee Payable</span>
                    <div class="text-4xl font-extrabold text-emerald-600 mt-2">
                        ${{ number_format($calculatedFee, 2) }}
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 space-y-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400">Statutory Schedule Applied:</span>
                    <p class="text-sm font-mono text-gray-900 dark:text-gray-200">{{ $formulaApplied }}</p>
                </div>

                <div class="border-t border-gray-100 dark:border-gray-800 pt-4 space-y-2 text-xs text-gray-500">
                    <p>• E-Challan / Judicial Stamp Paper can be purchased referencing this valuation.</p>
                    <p>• In case of counter-claims by adverse party, additional fee will be assessed upon their filing.</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
