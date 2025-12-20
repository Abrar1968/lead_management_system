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

        // Get the highest sequence number for this date
        $latestLead = Lead::whereDate('lead_date', $date)
            ->where('lead_number', 'LIKE', "LEAD-{$dateStr}-%")
            ->orderByRaw('CAST(SUBSTRING(lead_number, -3) AS UNSIGNED) DESC')
            ->first();

        if ($latestLead) {
            // Extract the sequence number and increment it
            $lastSequence = (int) substr($latestLead->lead_number, -3);
            $nextSequence = $lastSequence + 1;
        } else {
            // First lead of the day
            $nextSequence = 1;
        }

        return sprintf('LEAD-%s-%03d', $dateStr, $nextSequence);
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

        if (! isset($data['lead_time'])) {
            $data['lead_time'] = now()->format('H:i');
        }

        return $this->repository->create($data);
    }

    /**
     * Update an existing lead
     */
    public function updateLead(Lead $lead, array $data): Lead
    {
        $oldStatus = $lead->status;
        $lead = $this->repository->update($lead, $data);

        // Check if status changed to 'Converted' and no conversion exists
        if ($oldStatus !== 'Converted' && $lead->status === 'Converted' && ! $lead->conversion) {
            // Auto-create conversion and client
            $user = \Illuminate\Support\Facades\Auth::user();
            $commissionService = app(\App\Services\CommissionService::class);
            $dealValue = 0; // Default to 0 for manual status change
            
            $commissionAmount = $commissionService->calculateCommission($user, $dealValue);

            $conversion = \App\Models\Conversion::create([
                'lead_id' => $lead->id,
                'converted_by' => $user->id,
                'conversion_date' => now(),
                'deal_value' => $dealValue,
                'commission_rate_used' => $user->default_commission_rate,
                'commission_type_used' => $user->commission_type,
                'commission_amount' => $commissionAmount,
                'package_plan' => $lead->service->name ?? 'Standard',
                'notes' => 'Auto-converted via status change',
            ]);

            \App\Models\ClientDetail::create([
                'conversion_id' => $conversion->id,
            ]);

            // Auto-create Contact log
            \App\Models\LeadContact::create([
                'lead_id' => $lead->id,
                'caller_id' => $user->id,
                'call_date' => now(),
                'call_time' => now(),
                'daily_call_made' => true,
                'response_status' => 'Connected',
                'notes' => 'Lead converted to Client',
            ]);
        }

        return $lead;
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
