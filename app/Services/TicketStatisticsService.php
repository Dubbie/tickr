<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class TicketStatisticsService
{
    /**
     * Get ticket statistics for a given date range.
     *
     * @param string $range The date range ('this_week', 'last_week', etc.).
     * @return array
     */
    public function getAverages(string $range): array
    {
        // Calculate the start and end date based on the range
        $startDate = $this->getStartDate($range);
        $endDate = $this->getEndDate($range, $startDate);

        // Fetch the ticket counts and solved ticket counts
        $ticketCounts = $this->getTicketCounts($startDate, $endDate);
        $solvedCounts = $this->getSolvedCounts($startDate, $endDate);

        // Fill in missing days with zero counts
        $period = CarbonPeriod::create($startDate, $endDate);
        $dailyCreatedCounts = collect();
        $dailySolvedCounts = collect();

        foreach ($period as $date) {
            $formattedDate = $date->format('M d');
            $dailyCreatedCounts[$formattedDate] = $ticketCounts[$date->format('Y-m-d')] ?? 0;
            $dailySolvedCounts[$formattedDate] = $solvedCounts[$date->format('Y-m-d')] ?? 0;
        }

        // Calculate averages
        $averageCreated = $this->calculateAverage($dailyCreatedCounts);
        $averageSolved = $this->calculateAverage($dailySolvedCounts);

        return [
            'created' => [
                'daily_counts' => $dailyCreatedCounts,
                'average' => round($averageCreated, 2),
            ],
            'solved' => [
                'daily_counts' => $dailySolvedCounts,
                'average' => round($averageSolved, 2),
            ],
        ];
    }

    /**
     * Get the time to first reply distribution.
     *
     * @return array
     */
    public function getTimeToFirstReplyDistribution()
    {
        // Query the database and categorize the tickets by time to first reply
        $distribution = DB::table('tickets')
            ->selectRaw('
            CASE
                WHEN time_to_first_reply <= 60 THEN "0-1 hours"
                WHEN time_to_first_reply <= 480 THEN "1-8 hours"
                WHEN time_to_first_reply <= 1440 THEN "8-24 hours"
                WHEN time_to_first_reply > 1440 THEN ">24 hours"
                ELSE "No reply"
            END as time_category,
            COUNT(*) as count
        ')
            ->groupBy('time_category')
            ->get()
            ->pluck('count', 'time_category');

        // Ensure all categories exist in the result, even if their count is 0
        $categories = [
            "0-1 hours" => 0,
            "1-8 hours" => 0,
            "8-24 hours" => 0,
            ">24 hours" => 0,
            "No reply" => 0
        ];

        foreach ($distribution as $category => $count) {
            $categories[$category] = $count;
        }

        return $categories;
    }

    /**
     * Get the start date based on the range.
     *
     * @param string $range
     * @return Carbon
     */
    protected function getStartDate(string $range): Carbon
    {
        $now = Carbon::now();
        return match ($range) {
            'this_week' => $now->startOfWeek(),
            'last_week' => $now->subWeek()->startOfWeek(),
            'this_month' => $now->startOfMonth(),
            'last_month' => $now->subMonth()->startOfMonth(),
            default => throw new \InvalidArgumentException('Invalid range'),
        };
    }

    /**
     * Get the end date based on the range and start date.
     *
     * @param string $range
     * @param Carbon $startDate
     * @return Carbon
     */
    protected function getEndDate(string $range, Carbon $startDate): Carbon
    {
        return match ($range) {
            'this_week', 'last_week' => $startDate->copy()->endOfWeek(),
            'this_month', 'last_month' => $startDate->copy()->endOfMonth(),
            default => throw new \InvalidArgumentException('Invalid range'),
        };
    }

    /**
     * Get the ticket creation counts grouped by day.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return \Illuminate\Support\Collection
     */
    protected function getTicketCounts(Carbon $startDate, Carbon $endDate)
    {
        return DB::table('tickets')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');
    }

    /**
     * Get the solved ticket counts grouped by day.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return \Illuminate\Support\Collection
     */
    protected function getSolvedCounts(Carbon $startDate, Carbon $endDate)
    {
        return DB::table('status_changes')
            ->selectRaw('DATE(changed_at) as date, COUNT(*) as count')
            ->whereBetween('changed_at', [$startDate, $endDate])
            ->whereIn('new_status', ['resolved', 'closed']) // Define solved statuses
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');
    }

    /**
     * Calculate the average of a collection of counts.
     *
     * @param \Illuminate\Support\Collection $counts
     * @return float
     */
    protected function calculateAverage($counts): float
    {
        return $counts->count() > 0 ? $counts->sum() / $counts->count() : 0;
    }
}
