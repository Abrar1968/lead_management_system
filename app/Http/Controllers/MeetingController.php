<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Meeting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MeetingController extends Controller
{
    /**
     * Meeting types available
     */
    public const MEETING_TYPES = [
        'Demo' => ['label' => 'Demo', 'color' => 'purple', 'icon' => 'presentation'],
        'Consultation' => ['label' => 'Consultation', 'color' => 'blue', 'icon' => 'chat'],
        'Follow-up' => ['label' => 'Follow-up', 'color' => 'yellow', 'icon' => 'arrow-path'],
        'Closing' => ['label' => 'Closing', 'color' => 'green', 'icon' => 'check-circle'],
        'Support' => ['label' => 'Support', 'color' => 'orange', 'icon' => 'support'],
    ];

    /**
     * Meeting statuses
     */
    public const MEETING_STATUSES = [
        'Positive' => ['label' => 'Positive', 'color' => 'green', 'bg' => 'bg-green-100', 'text' => 'text-green-800'],
        'Negative' => ['label' => 'Negative', 'color' => 'red', 'bg' => 'bg-red-100', 'text' => 'text-red-800'],
        'Confirmed' => ['label' => 'Confirmed', 'color' => 'blue', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
        'Pending' => ['label' => 'Pending', 'color' => 'yellow', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
    ];

    /**
     * Meeting outcomes
     */
    public const MEETING_OUTCOMES = [
        'Pending' => ['label' => 'Pending', 'color' => 'gray', 'bg' => 'bg-gray-500'],
        'Successful' => ['label' => 'Successful', 'color' => 'green', 'bg' => 'bg-green-500'],
        'Follow-up Needed' => ['label' => 'Follow-up Needed', 'color' => 'yellow', 'bg' => 'bg-yellow-500'],
        'Rescheduled' => ['label' => 'Rescheduled', 'color' => 'blue', 'bg' => 'bg-blue-500'],
        'Cancelled' => ['label' => 'Cancelled', 'color' => 'red', 'bg' => 'bg-red-500'],
        'No Show' => ['label' => 'No Show', 'color' => 'red', 'bg' => 'bg-red-600'],
    ];

    /**
     * Display a listing of meetings.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $date = $request->input('date', now()->format('Y-m-d'));

        $query = Meeting::with(['lead', 'lead.assignedTo'])
            ->orderBy('meeting_date', 'desc')
            ->orderBy('meeting_time', 'desc');

        // Filter by user if sales person
        if ($user->isSalesPerson()) {
            $query->whereHas('lead', fn ($q) => $q->where('assigned_to', $user->id));
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('meeting_date', $date);
        }

        // Filter by meeting type
        if ($request->filled('meeting_type')) {
            $query->where('meeting_type', $request->meeting_type);
        }

        // Filter by outcome
        if ($request->filled('outcome')) {
            $query->where('outcome', $request->outcome);
        }

        $meetings = $query->paginate(20);

        // Today's meetings
        $todayMeetings = Meeting::whereDate('meeting_date', today())
            ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
            ->get();

        // Upcoming meetings (next 7 days)
        $upcomingMeetings = Meeting::whereDate('meeting_date', '>', today())
            ->whereDate('meeting_date', '<=', now()->addDays(7))
            ->where('outcome', 'Pending')
            ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
            ->with(['lead'])
            ->orderBy('meeting_date')
            ->orderBy('meeting_time')
            ->get();

        // Stats
        $stats = [
            'today_meetings' => $todayMeetings->count(),
            'successful_today' => $todayMeetings->where('outcome', 'Successful')->count(),
            'upcoming_week' => $upcomingMeetings->count(),
            'pending' => $todayMeetings->where('outcome', 'Pending')->count(),
        ];

        return view('meetings.index', [
            'meetings' => $meetings,
            'todayMeetings' => $todayMeetings,
            'upcomingMeetings' => $upcomingMeetings,
            'stats' => $stats,
            'meetingTypes' => self::MEETING_TYPES,
            'meetingStatuses' => self::MEETING_STATUSES,
            'outcomes' => self::MEETING_OUTCOMES,
            'currentDate' => $date,
        ]);
    }

    /**
     * Store a new meeting.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'follow_up_id' => 'nullable|exists:follow_ups,id',
            'meeting_date' => 'required|date|after_or_equal:today',
            'meeting_time' => 'required|date_format:H:i',
            'meeting_type' => 'required|string|in:' . implode(',', array_keys(self::MEETING_TYPES)),
            'meeting_status' => 'nullable|in:' . implode(',', array_keys(self::MEETING_STATUSES)),
            'price' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // If follow_up_id provided, get price from follow-up
        $price = $validated['price'] ?? null;
        if (!empty($validated['follow_up_id'])) {
            $followUp = \App\Models\FollowUp::find($validated['follow_up_id']);
            if ($followUp && $followUp->price) {
                $price = $followUp->price;
            }
        }

        $meeting = Meeting::create([
            'lead_id' => $validated['lead_id'],
            'follow_up_id' => $validated['follow_up_id'] ?? null,
            'meeting_date' => $validated['meeting_date'],
            'meeting_time' => $validated['meeting_time'],
            'meeting_type' => $validated['meeting_type'],
            'meeting_status' => $validated['meeting_status'] ?? 'Pending',
            'price' => $price,
            'location' => $validated['location'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'outcome' => 'Pending',
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'meeting' => $meeting]);
        }

        return back()->with('success', 'Meeting scheduled successfully.');
    }

    /**
     * Update a meeting.
     */
    public function update(Request $request, Meeting $meeting): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'meeting_date' => 'sometimes|date',
            'meeting_time' => 'sometimes|date_format:H:i',
            'meeting_type' => 'sometimes|string|in:' . implode(',', array_keys(self::MEETING_TYPES)),
            'meeting_status' => 'sometimes|in:' . implode(',', array_keys(self::MEETING_STATUSES)),
            'outcome' => 'sometimes|string|in:' . implode(',', array_keys(self::MEETING_OUTCOMES)),
            'price' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $meeting->update($validated);

        // Update lead status based on meeting_status
        if (isset($validated['meeting_status'])) {
            $this->updateLeadStatusFromMeeting($meeting->lead, $validated['meeting_status']);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'meeting' => $meeting->fresh()]);
        }

        return back()->with('success', 'Meeting updated successfully.');
    }

    /**
     * Update lead status based on meeting status.
     */
    protected function updateLeadStatusFromMeeting(Lead $lead, string $meetingStatus): void
    {
        $statusMap = [
            'Positive' => 'Qualified',
            'Negative' => 'Lost',
            'Confirmed' => 'Negotiation',
            'Pending' => 'Contacted',
        ];

        if (isset($statusMap[$meetingStatus])) {
            $lead->update(['status' => $statusMap[$meetingStatus]]);
        }
    }

    /**
     * Update meeting outcome and status.
     */
    public function updateOutcome(Request $request, Meeting $meeting): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'outcome' => 'sometimes|string|in:' . implode(',', array_keys(self::MEETING_OUTCOMES)),
            'meeting_status' => 'sometimes|in:' . implode(',', array_keys(self::MEETING_STATUSES)),
            'price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $meeting->update($validated);

        // Update lead status based on meeting_status
        if (isset($validated['meeting_status'])) {
            $this->updateLeadStatusFromMeeting($meeting->lead, $validated['meeting_status']);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'meeting' => $meeting->fresh()]);
        }

        return back()->with('success', 'Meeting updated.');
    }

    /**
     * Delete a meeting.
     */
    public function destroy(Meeting $meeting): RedirectResponse|JsonResponse
    {
        $meeting->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Meeting deleted.');
    }

    /**
     * Get meetings for a specific lead (API endpoint).
     */
    public function forLead(Lead $lead): JsonResponse
    {
        $meetings = $lead->meetings()
            ->orderBy('meeting_date', 'desc')
            ->orderBy('meeting_time', 'desc')
            ->get();

        return response()->json($meetings);
    }

    /**
     * Quick schedule from lead page.
     */
    public function quickSchedule(Request $request, Lead $lead): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'meeting_date' => 'required|date|after_or_equal:today',
            'meeting_time' => 'required|date_format:H:i',
            'meeting_type' => 'required|string|in:' . implode(',', array_keys(self::MEETING_TYPES)),
            'notes' => 'nullable|string|max:1000',
        ]);

        $meeting = Meeting::create([
            'lead_id' => $lead->id,
            'meeting_date' => $validated['meeting_date'],
            'meeting_time' => $validated['meeting_time'],
            'meeting_type' => $validated['meeting_type'],
            'notes' => $validated['notes'] ?? null,
            'outcome' => 'Pending',
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'meeting' => $meeting]);
        }

        return back()->with('success', 'Meeting scheduled successfully.');
    }
}
