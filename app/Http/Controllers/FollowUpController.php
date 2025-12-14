<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FollowUpController extends Controller
{
    /**
     * Response status options matching the spreadsheet dropdown
     */
    public const RESPONSE_STATUSES = [
        'Yes' => ['label' => 'Yes', 'color' => 'green'],
        'No' => ['label' => 'No', 'color' => 'red'],
        'No Res.' => ['label' => 'No Response', 'color' => 'gray'],
        '50%' => ['label' => '50% Interest', 'color' => 'yellow'],
        'Call Later' => ['label' => 'Call Later', 'color' => 'blue'],
        'Phone off' => ['label' => 'Phone Off', 'color' => 'gray'],
        '80%' => ['label' => '80% Interest', 'color' => 'orange'],
        'Demo Delivered' => ['label' => 'Demo Delivered', 'color' => 'purple'],
        'Interested' => ['label' => 'Interested', 'color' => 'green'],
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

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('follow_up_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('follow_up_date', '<=', $request->date_to);
        }

        // Today's follow-ups
        $todayFollowUps = FollowUp::with(['lead', 'lead.assignedTo'])
            ->whereDate('follow_up_date', today())
            ->where('status', 'Pending')
            ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
            ->orderBy('follow_up_time')
            ->get();

        // Overdue follow-ups
        $overdueFollowUps = FollowUp::with(['lead', 'lead.assignedTo'])
            ->whereDate('follow_up_date', '<', today())
            ->where('status', 'Pending')
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
                ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
                ->count(),
            'completed_today' => FollowUp::whereDate('updated_at', today())
                ->where('status', 'Completed')
                ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
                ->count(),
        ];

        // Upcoming follow-ups (next 7 days excluding today)
        $upcomingFollowUps = FollowUp::with(['lead'])
            ->where('status', 'Pending')
            ->whereDate('follow_up_date', '>', today())
            ->whereDate('follow_up_date', '<=', today()->addDays(7))
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
            'statuses' => self::RESPONSE_STATUSES,
            'currentStatus' => $status,
            'currentDate' => $date,
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
            'notes' => 'required|string|max:1000',
            'status' => 'sometimes|in:Pending,Completed,Cancelled',
        ]);

        $followUp = FollowUp::create([
            'lead_id' => $validated['lead_id'],
            'follow_up_date' => $validated['follow_up_date'],
            'follow_up_time' => $validated['follow_up_time'] ?? null,
            'notes' => $validated['notes'],
            'status' => $validated['status'] ?? 'Pending',
            'created_by' => auth()->id(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'followUp' => $followUp]);
        }

        return back()->with('success', 'Follow-up scheduled successfully.');
    }

    /**
     * Update a follow-up.
     */
    public function update(Request $request, FollowUp $followUp): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'follow_up_date' => 'sometimes|date',
            'follow_up_time' => 'nullable|date_format:H:i',
            'notes' => 'sometimes|string|max:1000',
            'status' => 'sometimes|in:Pending,Completed,Cancelled',
        ]);

        $followUp->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'followUp' => $followUp->fresh()]);
        }

        return back()->with('success', 'Follow-up updated successfully.');
    }

    /**
     * Mark follow-up as complete with response status.
     */
    public function complete(Request $request, FollowUp $followUp): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'response_status' => 'required|string|in:' . implode(',', array_keys(self::RESPONSE_STATUSES)),
            'notes' => 'nullable|string|max:1000',
        ]);

        $followUp->update([
            'status' => 'Completed',
            'notes' => $followUp->notes . "\n[Completed: " . $validated['response_status'] . '] ' . ($validated['notes'] ?? ''),
        ]);

        // Also update the lead status based on response
        $lead = $followUp->lead;
        if (in_array($validated['response_status'], ['Yes', 'Interested', 'Demo Delivered'])) {
            $lead->update(['status' => 'Hot']);
        } elseif ($validated['response_status'] === 'No') {
            $lead->update(['status' => 'Lost']);
        } elseif (in_array($validated['response_status'], ['80%', '50%'])) {
            $lead->update(['status' => 'Warm']);
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
