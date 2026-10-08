<?php

namespace App\Filament\App\Pages;

use App\Models\Order;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class EstadisticasSemanales extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.estadisticas-semanales';

    protected static string | UnitEnum | null $navigationGroup = 'Mesoneros';

    public ?string $weekStart = null;

    public function getTitle(): string
    {
        return 'Estadísticas Semanales';
    }

    public function mount(): void
    {
        $this->weekStart ??= now()->startOfWeek()->format('Y-m-d');
    }

    public function getWeekDates(): array
    {
        $start = \Carbon\Carbon::parse($this->weekStart)->startOfWeek();
        $end = $start->copy()->endOfWeek();

        return [
            'start' => $start,
            'end' => $end,
            'label' => $start->format('d/m/Y') . ' — ' . $end->format('d/m/Y'),
        ];
    }

    public function previousWeek(): void
    {
        $this->weekStart = \Carbon\Carbon::parse($this->weekStart)->subWeek()->format('Y-m-d');
    }

    public function nextWeek(): void
    {
        $this->weekStart = \Carbon\Carbon::parse($this->weekStart)->addWeek()->format('Y-m-d');
    }

    public function thisWeek(): void
    {
        $this->weekStart = now()->startOfWeek()->format('Y-m-d');
    }

    public function getRanking(): array
    {
        $dates = $this->getWeekDates();

        $waiters = User::whereHas('orders', function ($q) use ($dates) {
            $q->where('created_at', '>=', $dates['start'])
              ->where('created_at', '<=', $dates['end']);
        })->withCount(['orders' => function ($q) use ($dates) {
            $q->where('created_at', '>=', $dates['start'])
              ->where('created_at', '<=', $dates['end']);
        }])->get();

        $ranking = [];
        foreach ($waiters as $waiter) {
            $orders = Order::where('waiter_id', $waiter->id)
                ->where('created_at', '>=', $dates['start'])
                ->where('created_at', '<=', $dates['end'])
                ->get();

            $totalSales = $orders->sum('total');
            $tablesServed = $orders->pluck('table_id')->unique()->count();
            $orderCount = $orders->count();
            $avgPerOrder = $orderCount > 0 ? round($totalSales / $orderCount, 2) : 0;

            $servedOrders = $orders->where('status', 'served');
            $times = $servedOrders->map(fn ($o) => $o->created_at->diffInMinutes($o->updated_at));
            $avgMinutes = $times->avg();
            $minMinutes = $times->min();
            $maxMinutes = $times->max();

            $ranking[] = [
                'waiter_name' => $waiter->name,
                'order_count' => $orderCount,
                'tables_served' => $tablesServed,
                'total_sales' => $totalSales,
                'avg_per_order' => $avgPerOrder,
                'avg_minutes' => $avgMinutes ? round($avgMinutes, 1) : null,
                'min_minutes' => $minMinutes,
                'max_minutes' => $maxMinutes,
            ];
        }

        usort($ranking, fn ($a, $b) => $b['total_sales'] <=> $a['total_sales']);

        return $ranking;
    }

    public function getTotals(): array
    {
        $ranking = $this->getRanking();

        return [
            'total_sales' => collect($ranking)->sum('total_sales'),
            'total_orders' => collect($ranking)->sum('order_count'),
            'total_tables' => collect($ranking)->sum('tables_served'),
            'waiter_count' => count($ranking),
            'avg_attention' => collect($ranking)->avg('avg_minutes'),
        ];
    }
}
