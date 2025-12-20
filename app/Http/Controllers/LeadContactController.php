<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadContact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadContactController extends Controller
{
    /**
     * Response status options matching the spreadsheet dropdown
     */
    public const RESPONSE_STATUSES = [
        'Interested' => ['label' => 'Interested', 'color' => 'green', 'bg' => 'bg-green-600'],
        '50%' => ['label' => '50% Interest', 'color' => 'yellow', 'bg' => 'bg-yellow-500'],
        'Yes' => ['label' => 'Yes', 'color' => 'green', 'bg' => 'bg-green-500'],
        'Call Later' => ['label' => 'Call Later', 'color' => 'blue', 'bg' => 'bg-blue-500'],
        'No Response' => ['label' => 'No Response', 'color' => 'gray', 'bg' => 'bg-gray-500'],
        'No' => ['label' => 'No', 'color' => 'red', 'bg' => 'bg-red-500'],
        'Phone off' => ['label' => 'Phone Off', 'color' => 'gray', 'bg' => 'bg-gray-400'],
    ];

    /**
     * Display a listing of calls/contacts.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $date = $request->input('date', now()->format('Y-m-d'));

        $query = LeadContact::with(['lead', 'lead.assignedTo', 'caller'])
            ->whereHas('lead')
            ->orderBy('call_date', 'desc')
            ->orderBy('call_time', 'desc');

        // Filter by user if sales person
        if ($user->isSalesPerson()) {
            $query->whereHas('lead', fn ($q) => $q->where('assigned_to', $user->id));
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('call_date', $date);
        }

        // Filter by response status
        if ($request->filled('response_status')) {
            $query->where('response_status', $request->response_status);
        }

        $contacts = $query->paginate(20);

        // Today's calls
        $todayCalls = LeadContact::whereDate('call_date', today())
            ->whereHas('lead')
            ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
            ->count();

        // Response breakdown for today
        $responseBreakdown = LeadContact::whereDate('call_date', today())
            ->whereHas('lead')
            ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
            ->selectRaw('response_status, COUNT(*) as count')
            ->groupBy('response_status')
            ->pluck('count', 'response_status')
            ->toArray();

        // Stats
        $stats = [
            'today_calls' => $todayCalls,
            'positive_responses' => ($responseBreakdown['Yes'] ?? 0) + ($responseBreakdown['Interested'] ?? 0) + ($responseBreakdown['80%'] ?? 0),
            'negative_responses' => $responseBreakdown['No'] ?? 0,
            'pending_callbacks' => ($responseBreakdown['Call Later'] ?? 0) + ($responseBreakdown['No Res.'] ?? 0),
        ];

        return view('contacts.index', [
            'contacts' => $contacts,
            'stats' => $stats,
            'responseBreakdown' => $responseBreakdown,
            'statuses' => self::RESPONSE_STATUSES,
            'currentDate' => $date,
        ]);
    }

    /**
     * Store a new contact/call record.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'response_status' => 'required|string|in:'.implode(',', array_keys(self::RESPONSE_STATUSES)),
            'call_date' => 'sometimes|date',
            'call_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ]);

        $contact = LeadContact::create([
            'lead_id' => $validated['lead_id'],
            'daily_call_made' => true,
            'call_date' => $validated['call_date'] ?? today(),
            'call_time' => $validated['call_time'] ?? now()->format('H:i'),
            'caller_id' => auth()->id(),
            'response_status' => $validated['response_status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Update lead status based on response
        $lead = Lead::find($validated['lead_id']);
        $this->updateLeadStatusFromResponse($lead, $validated['response_status']);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'contact' => $contact]);
        }

        return back()->with('success', 'Call recorded successfully.');
    }

    /**
     * Update a contact record.
     */
    public function update(Request $request, LeadContact $contact): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'response_status' => 'sometimes|string|in:'.implode(',', array_keys(self::RESPONSE_STATUSES)),
            'notes' => 'nullable|string|max:1000',
        ]);

        $contact->update($validated);

        if (isset($validated['response_status'])) {
            $this->updateLeadStatusFromResponse($contact->lead, $validated['response_status']);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Call record updated.',
                'contact' => $contact->fresh(),
            ]);
        }

        return back()->with('success', 'Call record updated.');
    }

    /**
     * Delete a contact record.
     */
    public function destroy(LeadContact $contact): RedirectResponse|JsonResponse
    {
        $contact->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Call record deleted.');
    }

    /**
     * Quick log a call from lead page.
     */
    public function quickLog(Request $request, Lead $lead): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'response_status' => 'required|string|in:'.implode(',', array_keys(self::RESPONSE_STATUSES)),
            'notes' => 'nullable|string|max:1000',
        ]);

        $contact = LeadContact::create([
            'lead_id' => $lead->id,
            'daily_call_made' => true,
            'call_date' => today(),
            'call_time' => now()->format('H:i'),
            'caller_id' => auth()->id(),
            'response_status' => $validated['response_status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $this->updateLeadStatusFromResponse($lead, $validated['response_status']);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'contact' => $contact]);
        }

        return back()->with('success', 'Call logged successfully.');
    }

    /**
     * Update lead status based on call response.
     */
    private function updateLeadStatusFromResponse(Lead $lead, string $responseStatus): void
    {
        // Map call responses to valid Lead statuses:
        // Valid statuses: New, Contacted, Qualified, Negotiation, Converted, Lost
        $statusMapping = [
            'Interested' => 'Qualified',
            '50%' => 'Contacted',
            'Yes' => 'Qualified',
            'Call Later' => 'Contacted',
            'No Response' => 'Contacted',
            'No' => 'Lost',
            'Phone off' => 'Contacted',
        ];

        if (isset($statusMapping[$responseStatus])) {
            $lead->update(['status' => $statusMapping[$responseStatus]]);
        }
    }

    /**
     * Get contacts for a specific lead (API endpoint).
     */
    public function forLead(Lead $lead): JsonResponse
    {
        $contacts = $lead->contacts()
            ->with('caller')
            ->orderBy('call_date', 'desc')
            ->orderBy('call_time', 'desc')
            ->get();

        return response()->json($contacts);
    }
}
