<?php

namespace App\Http\Controllers;

use App\Services\LeadService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class MonthlyLeadController extends Controller
{
    public function __construct(
        private LeadService $leadService
    ) {}

    /**
     * Display the monthly calendar view
     */
    public function index(Request $request): View
    {
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);
        $user = $request->user();

        // Get lead counts per day
        $leadCounts = $this->leadService->getLeadCountsForMonth($year, $month, $user);

        // Get monthly summary stats
        $summary = $this->leadService->getMonthlySummary($year, $month, $user);

        // Build calendar data
        $calendarData = $this->buildCalendarData($year, $month, $leadCounts);

        // Navigation
        $currentMonth = Carbon::create($year, $month, 1);
        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();

        return view('leads.monthly', [
            'calendarData' => $calendarData,
            'summary' => $summary,
            'currentMonth' => $currentMonth,
            'prevMonth' => $prevMonth,
            'nextMonth' => $nextMonth,
            'year' => $year,
            'month' => $month,
        ]);
    }

    /**
     * Build calendar data structure for the view
     */
    private function buildCalendarData(int $year, int $month, array $leadCounts): array
    {
        $firstDay = Carbon::create($year, $month, 1);
        $lastDay = $firstDay->copy()->endOfMonth();
        $today = now()->startOfDay();

        $calendar = [];
        $week = [];

        // Add empty days for the first week
        $startDayOfWeek = $firstDay->dayOfWeek;
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $week[] = null;
        }

        // Fill in the days
        for ($day = 1; $day <= $lastDay->day; $day++) {
            $date = Carbon::create($year, $month, $day);
            $dateStr = $date->format('Y-m-d');

            $week[] = [
                'day' => $day,
                'date' => $dateStr,
                'count' => $leadCounts[$dateStr] ?? 0,
                'isToday' => $date->isSameDay($today),
                'isFuture' => $date->isAfter($today),
                'isWeekend' => $date->isWeekend(),
            ];

            // If Sunday, start new week
            if ($date->dayOfWeek === 6) {
                $calendar[] = $week;
                $week = [];
            }
        }

        // Add remaining days of the last week
        if (! empty($week)) {
            while (count($week) < 7) {
                $week[] = null;
            }
            $calendar[] = $week;
        }

        return $calendar;
    }
}
