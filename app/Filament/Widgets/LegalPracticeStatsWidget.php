<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\LegalCase;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LegalPracticeStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $activeCases = LegalCase::whereNotIn('status', ['Closed', 'Judgement'])->count();
        $todayHearings = LegalCase::whereDate('next_hearing_date', Carbon::today())->count();
        $dueInvoices = Invoice::whereIn('payment_status', ['due', 'partially_paid', 'overdue'])->sum('due');
        $totalClients = Client::count();

        return [
            Stat::make('Active Legal Matters', $activeCases)
                ->description('Active litigation suits & briefs')
                ->descriptionIcon('heroicon-m-scale')
                ->color('primary')
                ->chart([7, 10, 14, 18, 15, 22, $activeCases]),

            Stat::make('Today’s Court Hearings', $todayHearings)
                ->description('Matters listed on daily board')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color($todayHearings > 0 ? 'warning' : 'gray'),

            Stat::make('Receivables & Unbilled', '$' . number_format($dueInvoices, 2))
                ->description('Pending legal fees & invoices')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger')
                ->chart([15000, 22000, 18000, 25000, 19000, $dueInvoices]),

            Stat::make('Retained Clients', $totalClients)
                ->description('Corporate & individual clients')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
        ];
    }
}
