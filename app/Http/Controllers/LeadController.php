<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Lead;
use App\Models\User;
use App\Services\LeadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function __construct(
        private LeadService $leadService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $leads = $this->leadService->getRecentLeads(50, $user);

        return view('leads.index', [
            'leads' => $leads,
            'users' => User::where('is_active', true)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $salesPersons = User::where('is_active', true)->get();
        $services = \App\Models\Service::active()->get();

        return view('leads.create', [
            'date' => $date,
            'salesPersons' => $salesPersons,
            'services' => $services,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeadRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // If no assigned_to provided and user is sales person, assign to self
        if (! isset($data['assigned_to']) && $request->user()->isSalesPerson()) {
            $data['assigned_to'] = $request->user()->id;
        }

        $lead = $this->leadService->createLead($data);

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Lead created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead): View
    {
        $lead = $this->leadService->getLeadWithDetails($lead->id);

        return view('leads.show', [
            'lead' => $lead,
            'statuses' => \App\Http\Controllers\LeadContactController::RESPONSE_STATUSES,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead): View
    {
        $salesPersons = User::where('is_active', true)->get();
        $services = \App\Models\Service::active()->get();

        return view('leads.edit', [
            'lead' => $lead,
            'salesPersons' => $salesPersons,
            'services' => $services,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeadRequest $request, Lead $lead): RedirectResponse|JsonResponse
    {
        $this->leadService->updateLead($lead, $request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'lead' => $lead->fresh(),
                'message' => 'Lead updated successfully!'
            ]);
        }

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Lead updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead): RedirectResponse
    {
        $leadDate = $lead->lead_date->format('Y-m-d');
        $lead->delete();

        return redirect()
            ->route('leads.daily', ['date' => $leadDate])
            ->with('success', 'Lead deleted successfully!');
    }

    /**
     * Check if phone number is a repeat lead (AJAX endpoint)
     */
    public function checkRepeat(Request $request): JsonResponse
    {
        $request->validate([
            'phone_number' => ['required', 'string'],
            'exclude_lead_id' => ['nullable', 'integer'],
        ]);

        $result = $this->leadService->checkRepeatLead(
            $request->input('phone_number'),
            $request->input('exclude_lead_id')
        );

        return response()->json($result);
    }

    /**
     * Bulk delete leads (Admin only)
     */
    public function bulkDelete(Request $request): RedirectResponse|JsonResponse
    {
        // Verify admin role
        if (! $request->user()->isAdmin()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            return back()->with('error', 'Only admins can perform bulk operations.');
        }

        $validated = $request->validate([
            'lead_ids' => 'required|array|min:1',
            'lead_ids.*' => 'exists:leads,id',
        ]);

        $count = Lead::whereIn('id', $validated['lead_ids'])->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$count} leads deleted successfully.",
            ]);
        }

        return back()->with('success', "{$count} leads deleted successfully.");
    }

    /**
     * Bulk reassign leads (Admin only)
     */
    public function bulkReassign(Request $request): RedirectResponse|JsonResponse
    {
        // Verify admin role
        if (! $request->user()->isAdmin()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            return back()->with('error', 'Only admins can perform bulk operations.');
        }

        $validated = $request->validate([
            'lead_ids' => 'required|array|min:1',
            'lead_ids.*' => 'exists:leads,id',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($validated['assigned_to']);
        $count = Lead::whereIn('id', $validated['lead_ids'])
            ->update(['assigned_to' => $validated['assigned_to']]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$count} leads reassigned to {$user->name}.",
            ]);
        }

        return back()->with('success', "{$count} leads reassigned to {$user->name}.");
    }

    /**
     * Bulk update lead status (Admin only)
     */
    public function bulkUpdateStatus(Request $request): RedirectResponse|JsonResponse
    {
        // Verify admin role
        if (! $request->user()->isAdmin()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            return back()->with('error', 'Only admins can perform bulk operations.');
        }

        $validated = $request->validate([
            'lead_ids' => 'required|array|min:1',
            'lead_ids.*' => 'exists:leads,id',
            'status' => 'required|'.Lead::getStatusValidationRule(),
        ]);

        $count = Lead::whereIn('id', $validated['lead_ids'])
            ->update(['status' => $validated['status']]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$count} leads updated to {$validated['status']}.",
            ]);
        }

        return back()->with('success', "{$count} leads updated to {$validated['status']}.");
    }
}
