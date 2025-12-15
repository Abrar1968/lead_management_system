<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use App\Models\Lead;
use App\Services\CommissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConversionController extends Controller
{
    public function __construct(private CommissionService $commissionService) {}

    /**
     * Show conversion form for a lead.
     */
    public function create(Lead $lead): View|RedirectResponse
    {
        // Ensure user has access to this lead
        $user = auth()->user();
        if ($user->role !== 'admin' && $lead->assigned_to !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Check if already converted
        if ($lead->conversion) {
            return redirect()->route('leads.show', $lead)
                ->with('error', 'This lead has already been converted.');
        }

        return view('leads.convert', [
            'lead' => $lead,
            'user' => $user,
        ]);
    }

    /**
     * Store a new conversion.
     */
    public function store(Request $request, Lead $lead): RedirectResponse
    {
        $user = auth()->user();

        // Authorization check
        if ($user->role !== 'admin' && $lead->assigned_to !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Check if already converted
        if ($lead->conversion) {
            return back()->with('error', 'This lead has already been converted.');
        }

        $validated = $request->validate([
            'deal_value' => 'required|numeric|min:0',
            'conversion_date' => 'required|date|before_or_equal:today',
            'package_plan' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate commission using current user settings
        $commissionAmount = $this->commissionService->calculateCommission($user, $validated['deal_value']);

        // Create conversion with immutable commission data
        Conversion::create([
            'lead_id' => $lead->id,
            'converted_by' => $user->id,
            'conversion_date' => $validated['conversion_date'],
            'deal_value' => $validated['deal_value'],
            'commission_rate_used' => $user->default_commission_rate,
            'commission_type_used' => $user->commission_type,
            'commission_amount' => $commissionAmount,
            'package_plan' => $validated['package_plan'] ?? $lead->service_interested ?? 'Standard',
            'notes' => $validated['notes'] ?? null,
        ]);

        // Update lead status
        $lead->update(['status' => 'Converted']);

        return redirect()->route('leads.show', $lead)
            ->with('success', 'Lead converted successfully! Commission: ৳'.number_format($commissionAmount));
    }

    /**
     * API endpoint to calculate commission preview.
     */
    public function calculatePreview(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'deal_value' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();
        $commissionAmount = $this->commissionService->calculateCommission($user, $validated['deal_value']);

        return response()->json([
            'commission_amount' => $commissionAmount,
            'commission_type' => $user->commission_type,
            'commission_rate' => $user->default_commission_rate,
            'formatted' => '৳'.number_format($commissionAmount),
        ]);
    }
}
