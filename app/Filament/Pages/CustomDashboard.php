<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Invoice;
use Carbon\Carbon;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class CustomDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;
    protected string $view = 'filament.pages.custom-dashboard';

    protected static ?string $title = 'Dashboard';

    public function getTodayTotals(): array
    {
        return [
            'invoice_count' => Invoice::whereDate('created_at', today())->count(),
            'pending_count' => Invoice::where('status', 'Pending')->count(),
            'total_amount_today' => Invoice::whereDate('created_at', today())->sum('inv_total'),
        ];
    }

    public function getPendingInvoices()
    {
        return Invoice::where('status', 'Pending')
            ->where('created_at', '>=', Carbon::now()->subDays(5))
            ->latest()
            ->get();
    }

    public function getQuoteOfDay(): string
    {
        $quotes = [
            "Success is not final; failure is not fatal.",
            "Start where you are. Use what you have.",
            "Dream big. Start small. Act now.",
            "Quality is not an act, it is a habit.",
        ];

        return $quotes[array_rand($quotes)];
    }

    public function getSteps(): array
    {
        return [
            "1. Add Customer",
            "2. Create Product",
            "3. Generate Invoice",
            "4. Record Payment",
            "5. Download Invoice PDF",
        ];
    }
}
