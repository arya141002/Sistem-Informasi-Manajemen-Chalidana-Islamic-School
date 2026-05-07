<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/dashboard');
    }

    public function visitorStats(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'period' => 'nullable|in:daily,weekly,monthly,custom',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $period = $validated['period'] ?? 'daily';
        $now = Carbon::now();

        [$startAt, $endAt] = match ($period) {
            'weekly' => [$now->copy()->subWeeks(7)->startOfWeek(), $now->copy()->endOfWeek()],
            'monthly' => [$now->copy()->subMonths(11)->startOfMonth(), $now->copy()->endOfMonth()],
            'custom' => [
                isset($validated['start_date']) ? Carbon::parse($validated['start_date'])->startOfDay() : $now->copy()->subDays(6)->startOfDay(),
                isset($validated['end_date']) ? Carbon::parse($validated['end_date'])->endOfDay() : $now->copy()->endOfDay(),
            ],
            default => [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()],
        };

        if ($startAt->greaterThan($endAt)) {
            [$startAt, $endAt] = [$endAt->copy()->startOfDay(), $startAt->copy()->endOfDay()];
        }

        $sessions = collect();
        try {
            if (Schema::hasTable('sessions')) {
                $sessions = DB::table('sessions')
                    ->whereBetween('last_activity', [$startAt->timestamp, $endAt->timestamp])
                    ->get(['id', 'last_activity']);
            }
        } catch (\Throwable $th) {
            $sessions = collect();
        }

        $rows = $sessions
            ->filter(fn ($row) => !empty($row->last_activity))
            ->map(function ($row) {
                return [
                    'id' => $row->id,
                    'date' => Carbon::createFromTimestamp((int) $row->last_activity),
                ];
            });

        [$labels, $series] = match ($period) {
            'weekly' => $this->buildWeeklySeries($rows, $startAt, $endAt),
            'monthly' => $this->buildMonthlySeries($rows, $startAt, $endAt),
            default => $this->buildDailySeries($rows, $startAt, $endAt),
        };

        return response()->json([
            'period' => $period,
            'start_date' => $startAt->toDateString(),
            'end_date' => $endAt->toDateString(),
            'labels' => $labels,
            'data' => $series,
            'total' => array_sum($series),
        ]);
    }

    private function buildDailySeries(Collection $rows, Carbon $startAt, Carbon $endAt): array
    {
        $grouped = $rows->groupBy(fn ($row) => $row['date']->toDateString());
        $period = CarbonPeriod::create($startAt->copy()->startOfDay(), '1 day', $endAt->copy()->startOfDay());

        $labels = [];
        $series = [];
        foreach ($period as $day) {
            $key = $day->toDateString();
            $labels[] = $day->format('d M');
            $series[] = isset($grouped[$key]) ? $grouped[$key]->unique('id')->count() : 0;
        }

        return [$labels, $series];
    }

    private function buildWeeklySeries(Collection $rows, Carbon $startAt, Carbon $endAt): array
    {
        $grouped = $rows->groupBy(fn ($row) => $row['date']->copy()->startOfWeek()->toDateString());
        $cursor = $startAt->copy()->startOfWeek();
        $lastWeek = $endAt->copy()->startOfWeek();

        $labels = [];
        $series = [];
        while ($cursor->lessThanOrEqualTo($lastWeek)) {
            $key = $cursor->toDateString();
            $labels[] = 'Wk '.$cursor->format('W').' '.$cursor->format('M');
            $series[] = isset($grouped[$key]) ? $grouped[$key]->unique('id')->count() : 0;
            $cursor->addWeek();
        }

        return [$labels, $series];
    }

    private function buildMonthlySeries(Collection $rows, Carbon $startAt, Carbon $endAt): array
    {
        $grouped = $rows->groupBy(fn ($row) => $row['date']->format('Y-m'));
        $cursor = $startAt->copy()->startOfMonth();
        $lastMonth = $endAt->copy()->startOfMonth();

        $labels = [];
        $series = [];
        while ($cursor->lessThanOrEqualTo($lastMonth)) {
            $key = $cursor->format('Y-m');
            $labels[] = $cursor->format('M Y');
            $series[] = isset($grouped[$key]) ? $grouped[$key]->unique('id')->count() : 0;
            $cursor->addMonth();
        }

        return [$labels, $series];
    }
}
