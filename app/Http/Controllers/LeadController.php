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
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $salesPersons = User::where('is_active', true)->get();

        return view('leads.create', [
            'date' => $date,
            'salesPersons' => $salesPersons,
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
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead): View
    {
        $salesPersons = User::where('is_active', true)->get();

        return view('leads.edit', [
            'lead' => $lead,
            'salesPersons' => $salesPersons,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeadRequest $request, Lead $lead): RedirectResponse
    {
        $this->leadService->updateLead($lead, $request->validated());

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
}
