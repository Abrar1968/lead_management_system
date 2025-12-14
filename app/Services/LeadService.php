<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\User;
use App\Repositories\LeadRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class LeadService
{
    public function __construct(
        private LeadRepository $repository
    ) {}

    /**
     * Get leads for a specific date
     */
    public function getLeadsByDate(string $date, ?User $user = null): Collection
    {
        $userId = $user && ! $user->isAdmin() ? $user->id : null;

        return $this->repository->getByDate($date, $userId);
    }

    /**
     * Get leads for a specific date with filters
     */
    public function getLeadsByDateWithFilters(
        string $date,
        ?User $user = null,
        ?string $source = null,
        ?string $service = null,
        ?string $status = null,
        ?string $priority = null
    ): Collection {
        $userId = $user && ! $user->isAdmin() ? $user->id : null;

        return $this->repository->getByDateWithFilters(
            $date,
            $userId,
            $source,
            $service,
            $status,
            $priority
        );
    }

    /**
     * Get daily summary statistics
     */
    public function getDailySummary(string $date, ?User $user = null): array
    {
        $userId = $user && ! $user->isAdmin() ? $user->id : null;

        return [
            'total_leads' => $this->repository->countByDate($date, $userId),
            'calls_made' => $this->repository->countCallsByDate($date, $userId),
            'conversions' => $this->repository->countConversionsByDate($date, $userId),
            'pending_followups' => $this->repository->getPendingFollowUps($date, $userId)->count(),
        ];
    }

    /**
     * Generate unique lead number for a date
     * Format: LEAD-YYYYMMDD-XXX
     */
    public function generateLeadNumber(string $date): string
    {
        $dateStr = Carbon::parse($date)->format('Ymd');
        $count = $this->repository->countByDate($date);

        return sprintf('LEAD-%s-%03d', $dateStr, $count + 1);
    }

    /**
     * Check if a phone number is a repeat lead
     */
    public function checkRepeatLead(string $phoneNumber, ?string $excludeLeadId = null): array
    {
        $existingLeads = $this->repository->findByPhoneNumber($phoneNumber, $excludeLeadId);

        if ($existingLeads->isEmpty()) {
            return [
                'is_repeat' => false,
                'previous_leads' => [],
            ];
        }

        return [
            'is_repeat' => true,
            'previous_leads' => $existingLeads->map(function ($lead) {
                return [
                    'id' => $lead->id,
                    'lead_number' => $lead->lead_number,
                    'lead_date' => $lead->lead_date->format('Y-m-d'),
                    'customer_name' => $lead->customer_name,
                    'status' => $lead->status,
                    'assigned_to' => $lead->assignedTo?->name,
                ];
            })->toArray(),
        ];
    }

    /**
     * Get leads for monthly calendar view
     */
    public function getLeadsForMonth(int $year, int $month, ?User $user = null): Collection
    {
        $userId = $user && ! $user->isAdmin() ? $user->id : null;

        return $this->repository->getByMonth($year, $month, $userId);
    }

    /**
     * Get lead counts grouped by date for calendar
     */
    public function getLeadCountsForMonth(int $year, int $month, ?User $user = null): array
    {
        $userId = $user && ! $user->isAdmin() ? $user->id : null;

        return $this->repository->getLeadCountsByMonth($year, $month, $userId);
    }

    /**
     * Get monthly statistics
     */
    public function getMonthlySummary(int $year, int $month, ?User $user = null): array
    {
        $userId = $user && ! $user->isAdmin() ? $user->id : null;

        return $this->repository->getMonthlyStats($year, $month, $userId);
    }

    /**
     * Create a new lead
     */
    public function createLead(array $data): Lead
    {
        // Generate lead number if not provided
        if (! isset($data['lead_number'])) {
            $leadDate = $data['lead_date'] ?? now()->format('Y-m-d');
            $data['lead_number'] = $this->generateLeadNumber($leadDate);
        }

        return $this->repository->create($data);
    }

    /**
     * Update an existing lead
     */
    public function updateLead(Lead $lead, array $data): Lead
    {
        return $this->repository->update($lead, $data);
    }

    /**
     * Get lead with all relationships
     */
    public function getLeadWithDetails(int $id): ?Lead
    {
        return $this->repository->findWithRelations($id);
    }

    /**
     * Get pending follow-ups for a date
     */
    public function getPendingFollowUps(string $date, ?User $user = null): Collection
    {
        $userId = $user && ! $user->isAdmin() ? $user->id : null;

        return $this->repository->getPendingFollowUps($date, $userId);
    }

    /**
     * Get recent leads
     */
    public function getRecentLeads(int $limit = 10, ?User $user = null): Collection
    {
        $userId = $user && ! $user->isAdmin() ? $user->id : null;

        return $this->repository->getRecent($limit, $userId);
    }

    /**
     * Get date navigation data
     */
    public function getDateNavigation(string $date): array
    {
        $currentDate = Carbon::parse($date);

        return [
            'current' => $currentDate->format('Y-m-d'),
            'display' => $currentDate->format('F d, Y'),
            'day_name' => $currentDate->format('l'),
            'previous' => $currentDate->copy()->subDay()->format('Y-m-d'),
            'next' => $currentDate->copy()->addDay()->format('Y-m-d'),
            'is_today' => $currentDate->isToday(),
            'is_future' => $currentDate->isFuture(),
        ];
    }
}
