<?php

namespace App\Repositories;

use App\Models\Conversion;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\LeadContact;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class LeadRepository
{
    /**
     * Get leads by date with eager loading
     */
    public function getByDate(string $date, ?int $userId = null): Collection
    {
        $query = Lead::query()
            ->where('lead_date', $date)
            ->with(['assignedTo', 'contacts', 'followUps', 'meetings', 'conversion'])
            ->orderBy('created_at', 'desc');

        if ($userId) {
            $query->where('assigned_to', $userId);
        }

        return $query->get();
    }

    /**
     * Get leads by date with filters
     */
    public function getByDateWithFilters(
        string $date,
        ?int $userId = null,
        ?string $source = null,
        ?string $service = null,
        ?string $status = null,
        ?string $priority = null
    ): Collection {
        $query = Lead::query()
            ->where('lead_date', $date)
            ->with(['assignedTo', 'contacts', 'followUps', 'meetings', 'conversion'])
            ->orderBy('created_at', 'desc');

        if ($userId) {
            $query->where('assigned_to', $userId);
        }

        if ($source && $source !== 'all') {
            $query->where('source', $source);
        }

        if ($service && $service !== 'all') {
            $query->where('service_interested', $service);
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($priority && $priority !== 'all') {
            $query->where('priority', $priority);
        }

        return $query->get();
    }

    /**
     * Get leads for a month (for calendar view)
     */
    public function getByMonth(int $year, int $month, ?int $userId = null): Collection
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $query = Lead::query()
            ->whereBetween('lead_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->with(['assignedTo', 'conversion']);

        if ($userId) {
            $query->where('assigned_to', $userId);
        }

        return $query->get();
    }

    /**
     * Get lead count grouped by date for a month
     */
    public function getLeadCountsByMonth(int $year, int $month, ?int $userId = null): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $query = Lead::query()
            ->selectRaw('lead_date, COUNT(*) as count')
            ->whereBetween('lead_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->groupBy('lead_date');

        if ($userId) {
            $query->where('assigned_to', $userId);
        }

        return $query->pluck('count', 'lead_date')->toArray();
    }

    /**
     * Count leads for a specific date
     */
    public function countByDate(string $date, ?int $userId = null): int
    {
        $query = Lead::query()->where('lead_date', $date);

        if ($userId) {
            $query->where('assigned_to', $userId);
        }

        return $query->count();
    }

    /**
     * Count calls made on a specific date
     */
    public function countCallsByDate(string $date, ?int $userId = null): int
    {
        $query = LeadContact::query()
            ->where('call_date', $date)
            ->whereHas('lead', function ($q) use ($userId) {
                if ($userId) {
                    $q->where('assigned_to', $userId);
                }
            });

        return $query->count();
    }

    /**
     * Count conversions for a specific date
     */
    public function countConversionsByDate(string $date, ?int $userId = null): int
    {
        $query = Conversion::query()
            ->where('conversion_date', $date)
            ->whereHas('lead');

        if ($userId) {
            $query->where('converted_by', $userId);
        }

        return $query->count();
    }

    /**
     * Check for repeat leads by phone number
     */
    public function findByPhoneNumber(string $phoneNumber, ?string $excludeLeadId = null): Collection
    {
        $query = Lead::query()
            ->where('phone_number', $phoneNumber)
            ->with(['assignedTo'])
            ->orderBy('lead_date', 'desc');

        if ($excludeLeadId) {
            $query->where('id', '!=', $excludeLeadId);
        }

        return $query->get();
    }

    /**
     * Get pending follow-ups for a date
     */
    public function getPendingFollowUps(string $date, ?int $userId = null): Collection
    {
        $query = FollowUp::query()
            ->where('follow_up_date', $date)
            ->where('status', 'Pending')
            ->with(['lead.assignedTo'])
            ->whereHas('lead', function ($q) use ($userId) {
                if ($userId) {
                    $q->where('assigned_to', $userId);
                }
            })
            ->orderBy('follow_up_time');

        return $query->get();
    }

    /**
     * Get recent leads
     */
    public function getRecent(int $limit = 10, ?int $userId = null): Collection
    {
        $query = Lead::query()
            ->with(['assignedTo', 'followUps' => function ($q) {
                $q->latest('follow_up_date');
            }, 'meetings' => function ($q) {
                $q->latest('meeting_date');
            }])
            ->orderBy('created_at', 'desc')
            ->limit($limit);

        if ($userId) {
            $query->where('assigned_to', $userId);
        }

        return $query->get();
    }

    /**
     * Search leads by phone number, client name, or lead number
     */
    public function search(string $search, ?int $userId = null): Collection
    {
        $query = Lead::query()
            ->with(['assignedTo', 'contacts', 'followUps', 'meetings', 'conversion'])
            ->where(function ($q) use ($search) {
                $q->where('phone_number', 'LIKE', "%{$search}%")
                    ->orWhere('client_name', 'LIKE', "%{$search}%")
                    ->orWhere('lead_number', 'LIKE', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->limit(100);

        if ($userId) {
            $query->where('assigned_to', $userId);
        }

        return $query->get();
    }

    /**
     * Create a new lead
     */
    public function create(array $data): Lead
    {
        return Lead::create($data);
    }

    /**
     * Update a lead
     */
    public function update(Lead $lead, array $data): Lead
    {
        $lead->update($data);

        return $lead->fresh();
    }

    /**
     * Find lead by ID with relationships
     */
    public function findWithRelations(int $id): ?Lead
    {
        return Lead::with([
            'assignedTo',
            'contacts',
            'followUps',
            'meetings',
            'conversion',
        ])->find($id);
    }

    /**
     * Get monthly stats
     */
    public function getMonthlyStats(int $year, int $month, ?int $userId = null): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $leadsQuery = Lead::query()
            ->whereBetween('lead_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        $conversionsQuery = Conversion::query()
            ->whereBetween('conversion_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->whereHas('lead');

        $callsQuery = LeadContact::query()
            ->whereBetween('call_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->whereHas('lead', function ($q) use ($userId) {
                if ($userId) {
                    $q->where('assigned_to', $userId);
                }
            });

        if ($userId) {
            $leadsQuery->where('assigned_to', $userId);
            $conversionsQuery->where('converted_by', $userId);
        }

        return [
            'total_leads' => $leadsQuery->count(),
            'total_conversions' => $conversionsQuery->count(),
            'total_calls' => $callsQuery->count(),
            'total_revenue' => $conversionsQuery->sum('deal_value'),
            'total_commission' => $conversionsQuery->sum('commission_amount'),
        ];
    }
}
