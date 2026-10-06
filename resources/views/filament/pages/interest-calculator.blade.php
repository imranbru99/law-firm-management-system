<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-5 space-y-6">
            <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 space-y-5">
                <div class="flex items-center space-x-3 pb-3 border-b border-gray-100 dark:border-gray-800">
                    <div class="p-2.5 bg-indigo-500/10 text-indigo-600 rounded-xl">
                        <x-heroicon-o-receipt-percent class="w-6 h-6" />
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white">Decree Interest Engine</h2>
                        <p class="text-xs text-gray-500">Sec 34 CPC / Commercial Claim Calculator</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-2">
                        Principal Claim / Decreetal Sum ($)
                    </label>
                    <input
                        type="number"
                        wire:model="principalAmount"
                        wire:input="calculate"
                        class="w-full text-base font-bold rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 p-2.5"
                    />
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-2">
                        Interest Rate (% per annum)
                    </label>
                    <input
                        type="number"
                        step="0.25"
                        wire:model="interestRate"
                        wire:input="calculate"
                        class="w-full text-base font-bold rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 p-2.5"
                    />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1">
                            Claim / Default Date
                        </label>
                        <input
                            type="date"
                            wire:model="startDate"
                            wire:change="calculate"
                            class="w-full text-xs rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white p-2.5"
                        />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-1">
                            Decree / Settlement Date
                        </label>
                        <input
                            type="date"
                            wire:model="endDate"
                            wire:change="calculate"
                            class="w-full text-xs rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white p-2.5"
                        />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-2">
                        Compounding Frequency
                    </label>
                    <select
                        wire:model="interestType"
                        wire:change="calculate"
                        class="w-full text-sm rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white"
                    >
                        <option value="simple">Simple Interest (Standard Statutory Rule)</option>
                        <option value="compound_quarterly">Compound Quarterly (Banking & Commercial Debt)</option>
                        <option value="compound_annually">Compound Annually</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="lg:col-span-7 space-y-6">
            <div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-5 rounded-2xl bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/50">
                        <span class="text-xs uppercase tracking-wider font-semibold text-indigo-700 dark:text-indigo-400">Accrued Interest</span>
                        <div class="text-3xl font-extrabold text-indigo-600 mt-2">
                            ${{ number_format($accruedInterest, 2) }}
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Duration: {{ $totalDays }} days</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/50">
                        <span class="text-xs uppercase tracking-wider font-semibold text-emerald-700 dark:text-emerald-400">Total Decreetal Sum</span>
                        <div class="text-3xl font-extrabold text-emerald-600 mt-2">
                            ${{ number_format($totalAmount, 2) }}
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Principal + Interest</p>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 space-y-2 text-xs">
                    <div class="flex justify-between py-1 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-gray-500">Base Principal:</span>
                        <span class="font-bold">${{ number_format($principalAmount, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-gray-500">Applied Annual Rate:</span>
                        <span class="font-bold">{{ $interestRate }}% p.a.</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-gray-500">Daily Run-Rate:</span>
                        <span class="font-bold">${{ number_format(($principalAmount * ($interestRate / 100)) / 365, 2) }} / day</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
