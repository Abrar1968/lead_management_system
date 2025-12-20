<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class FollowUpController extends Controller
{
    /**
     * Interest status options matching the spreadsheet dropdown
     */
    public const INTEREST_STATUSES = [
        'Yes' => ['label' => 'Yes', 'color' => 'green', 'bg' => 'bg-green-100', 'text' => 'text-green-800'],
        'No' => ['label' => 'No', 'color' => 'red', 'bg' => 'bg-red-100', 'text' => 'text-red-800'],
        'No Response' => ['label' => 'No Response', 'color' => 'gray', 'bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
        '50%' => ['label' => '50%', 'color' => 'yellow', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
        'Phone Off' => ['label' => 'Phone Off', 'color' => 'gray', 'bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
        'Call Later' => ['label' => 'Call Later', 'color' => 'blue', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
    ];

    /**
     * Display a listing of follow-ups.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $date = $request->input('date', now()->format('Y-m-d'));
        $status = $request->input('status', 'all');

        $query = FollowUp::with(['lead', 'lead.assignedTo', 'createdBy'])
            ->whereHas('lead')
            ->orderBy('follow_up_date', 'desc')
            ->orderBy('follow_up_time', 'asc');

        // Filter by user if sales person
        if ($user->isSalesPerson()) {
            $query->whereHas('lead', fn ($q) => $q->where('assigned_to', $user->id));
        }

        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $targetDate = Carbon::parse($date);

        // Filter by date if specifically requested (not using date range)
        if ($request->has('date') && !$request->has('date_from') && !$request->has('date_to')) {
            $query->whereDate('follow_up_date', $targetDate);
        }

        // Today's follow-ups (becomes 'Selected Date' follow-ups)
        $todayFollowUps = FollowUp::with(['lead', 'lead.assignedTo'])
            ->whereDate('follow_up_date', $targetDate)
            ->where('status', 'Pending')
            ->whereHas('lead')
            ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
            ->orderBy('follow_up_time')
            ->get();

        // Overdue follow-ups
        $overdueFollowUps = FollowUp::with(['lead', 'lead.assignedTo'])
            ->whereDate('follow_up_date', '<', $targetDate)
            ->where('status', 'Pending')
            ->whereHas('lead')
            ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
            ->orderBy('follow_up_date')
            ->get();

        $followUps = $query->paginate(20);

        // Stats
        $stats = [
            'today' => $todayFollowUps->count(),
            'overdue' => $overdueFollowUps->count(),
            'this_week' => FollowUp::where('status', 'Pending')
                ->whereBetween('follow_up_date', [today(), today()->addDays(7)])
                ->whereHas('lead')
                ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
                ->count(),
            'completed_today' => FollowUp::whereDate('updated_at', today())
                ->where('status', 'Completed')
                ->whereHas('lead')
                ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
                ->count(),
        ];

        // Upcoming follow-ups (next 7 days excluding target date)
        $upcomingFollowUps = FollowUp::with(['lead'])
            ->where('status', 'Pending')
            ->whereDate('follow_up_date', '>', $targetDate)
            ->whereDate('follow_up_date', '<=', $targetDate->copy()->addDays(7))
            ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
            ->orderBy('follow_up_date')
            ->orderBy('follow_up_time')
            ->take(10)
            ->get();

        return view('follow-ups.index', [
            'followUps' => $followUps,
            'todayFollowUps' => $todayFollowUps,
            'overdueFollowUps' => $overdueFollowUps,
            'upcomingFollowUps' => $upcomingFollowUps,
            'stats' => $stats,
            'interestStatuses' => self::INTEREST_STATUSES,
            'currentStatus' => $status,
            'currentDate' => $date,
            'date' => $date,
            'users' => User::all(),
        ]);
    }

    /**
     * Store a new follow-up.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'follow_up_date' => 'required|date',
            'follow_up_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
            'interest' => 'nullable|in:'.implode(',', array_keys(self::INTEREST_STATUSES)),
            'price' => 'nullable|numeric|min:0',
            'status' => 'sometimes|in:Pending,Completed,Cancelled',
        ]);

        $followUp = FollowUp::create([
            'lead_id' => $validated['lead_id'],
            'follow_up_date' => $validated['follow_up_date'],
            'follow_up_time' => $validated['follow_up_time'] ?? null,
            'notes' => $validated['notes'] ?? '',
            'interest' => $validated['interest'] ?? null,
            'price' => $validated['price'] ?? null,
            'status' => $validated['status'] ?? 'Pending',
            'created_by' => auth()->id(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'followUp' => $followUp]);
        }

        // If redirect_back parameter is present, return to previous page
        if ($request->has('redirect_back')) {
            return redirect()->back()->with('success', 'Follow-up scheduled successfully.');
        }

        return redirect()->route('follow-ups.index')->with('success', 'Follow-up scheduled successfully.');
    }

    /**
     * Update a follow-up.
     */
    public function update(Request $request, FollowUp $followUp): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'follow_up_date' => 'sometimes|date',
            'follow_up_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
            'interest' => 'nullable|in:'.implode(',', array_keys(self::INTEREST_STATUSES)),
            'price' => 'nullable|numeric|min:0',
            'status' => 'sometimes|in:Pending,Completed,Cancelled',
        ]);

        $followUp->update($validated);

        // Update lead status based on interest
        if (isset($validated['interest'])) {
            $this->updateLeadStatusFromInterest($followUp->lead, $validated['interest']);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'followUp' => $followUp->fresh()]);
        }

        return back()->with('success', 'Follow-up updated successfully.');
    }

    /**
     * Update lead status based on interest response.
     */
    protected function updateLeadStatusFromInterest(Lead $lead, string $interest): void
    {
        $statusMap = [
            'Yes' => 'Qualified',
            'No' => 'Lost',
            '50%' => 'Negotiation',
            'No Response' => 'Contacted',
            'Phone Off' => 'Contacted',
            'Call Later' => 'Contacted',
        ];

        if (isset($statusMap[$interest])) {
            $lead->update(['status' => $statusMap[$interest]]);
        }
    }

    /**
     * Mark follow-up as complete with interest status.
     */
    public function complete(Request $request, FollowUp $followUp): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'interest' => 'nullable|in:'.implode(',', array_keys(self::INTEREST_STATUSES)),
            'price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $followUp->update([
            'status' => 'Completed',
            'interest' => $validated['interest'] ?? $followUp->interest,
            'price' => $validated['price'] ?? $followUp->price,
            'notes' => $validated['notes'] ?? $followUp->notes,
        ]);

        // Update lead status based on interest
        if (isset($validated['interest'])) {
            $this->updateLeadStatusFromInterest($followUp->lead, $validated['interest']);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'followUp' => $followUp->fresh()]);
        }

        return back()->with('success', 'Follow-up marked as complete.');
    }

    /**
     * Delete a follow-up.
     */
    public function destroy(FollowUp $followUp): RedirectResponse|JsonResponse
    {
        $followUp->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Follow-up deleted.');
    }

    /**
     * Get follow-ups for a specific lead (API endpoint).
     */
    public function forLead(Lead $lead): JsonResponse
    {
        $followUps = $lead->followUps()
            ->with('createdBy')
            ->orderBy('follow_up_date', 'desc')
            ->get();

        return response()->json($followUps);
    }

    /**
     * Quick add follow-up from lead page.
     */
    public function quickAdd(Request $request, Lead $lead): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'follow_up_date' => 'required|date',
            'notes' => 'required|string|max:1000',
        ]);

        $followUp = FollowUp::create([
            'lead_id' => $lead->id,
            'follow_up_date' => $validated['follow_up_date'],
            'notes' => $validated['notes'],
            'status' => 'Pending',
            'created_by' => auth()->id(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'followUp' => $followUp]);
        }

        return back()->with('success', 'Follow-up scheduled.');
    }
}
