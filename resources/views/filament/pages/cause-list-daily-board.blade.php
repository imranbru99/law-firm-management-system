<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Filter Tabs & Date Bar -->
        <div class="p-4 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-2">
                <button
                    type="button"
                    wire:click="setFilter('today')"
                    class="px-4 py-2 text-xs font-semibold rounded-xl transition-all {{ $activeFilter === 'today' ? 'bg-amber-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}"
                >
                    Today's Cause List ({{ \Carbon\Carbon::today()->format('M d') }})
                </button>
                <button
                    type="button"
                    wire:click="setFilter('tomorrow')"
                    class="px-4 py-2 text-xs font-semibold rounded-xl transition-all {{ $activeFilter === 'tomorrow' ? 'bg-amber-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}"
                >
                    Tomorrow's Board
                </button>
                <button
                    type="button"
                    wire:click="setFilter('week')"
                    class="px-4 py-2 text-xs font-semibold rounded-xl transition-all {{ $activeFilter === 'week' ? 'bg-amber-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}"
                >
                    Next 7 Days
                </button>
                <button
                    type="button"
                    wire:click="setFilter('awaited')"
                    class="px-4 py-2 text-xs font-semibold rounded-xl transition-all {{ $activeFilter === 'awaited' ? 'bg-amber-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}"
                >
                    Date Awaited Matters
                </button>
            </div>

            <div class="flex items-center space-x-2">
                <span class="text-xs text-gray-500">Pick Custom Date:</span>
                <input
                    type="date"
                    wire:model="selectedDate"
                    wire:change="$set('activeFilter', 'custom')"
                    class="text-xs rounded-xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white p-2"
                />
            </div>
        </div>

        <!-- Live Display Board Cards -->
        @if($this->cases->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($this->cases as $case)
                    <div class="p-5 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 space-y-4 hover:border-amber-400 dark:hover:border-amber-500/50 transition-all">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="px-2.5 py-1 text-[11px] font-mono font-bold rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                    {{ $case->case_no ?? 'UNASSIGNED' }}
                                </span>
                                <div class="text-sm font-bold text-gray-900 dark:text-white mt-2 line-clamp-1">
                                    {{ $case->title }}
                                </div>
                            </div>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $case->priority === 'Urgent' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                {{ $case->priority }}
                            </span>
                        </div>

                        <div class="space-y-1.5 text-xs text-gray-600 dark:text-gray-300">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Court / Forum:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $case->court?->name ?? 'TBD' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Court Room / Bench:</span>
                                <span class="font-medium text-amber-600 dark:text-amber-400">
                                    {{ $case->court?->room_number ?? 'Bench Listing' }} ({{ $case->court?->bench ?? 'Court' }})
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Stage:</span>
                                <span class="px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 font-semibold">
                                    {{ $case->stage?->name ?? 'Hearing' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Client / Party:</span>
                                <span>{{ $case->client?->name }} ({{ $case->client_role }})</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Lead Counsel:</span>
                                <span>{{ $case->leadLawyer?->name ?? 'Senior Advocate' }}</span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-xs">
                            <span class="text-gray-400 font-mono">
                                Listed: {{ $case->next_hearing_date?->format('M d, Y') ?? 'Date Awaited' }}
                            </span>
                            <a
                                href="/admin/legal-cases/{{ $case->id }}/edit"
                                class="text-amber-600 hover:text-amber-700 font-semibold flex items-center space-x-1"
                            >
                                <span>Open Matter</span>
                                <x-heroicon-m-arrow-right class="w-3.5 h-3.5" />
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-16 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 flex flex-col items-center justify-center text-center space-y-3 text-gray-400 dark:text-gray-600">
                <x-heroicon-o-calendar-days class="w-16 h-16 stroke-1" />
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">No Matters Listed for Selected Criteria</h3>
                <p class="text-xs">Select another date tab or schedule hearings from the Legal Matters resource.</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
