<?php

namespace App\Http\Controllers;

use App\Models\ExtraCommission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExtraCommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $status = $request->input('status');
        $userId = $request->input('user_id');

        $query = ExtraCommission::with('user')
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $commissions = $query->paginate(20);
        $users = User::where('is_active', true)->get();

        return view('admin.extra-commissions.index', [
            'commissions' => $commissions,
            'users' => $users,
            'currentStatus' => $status,
            'currentUserId' => $userId,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $users = User::where('is_active', true)->get();
        $selectedUserId = $request->input('user_id');

        return view('admin.extra-commissions.create', [
            'users' => $users,
            'selectedUserId' => $selectedUserId,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'commission_type' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string', 'max:1000'],
            'date_earned' => ['required', 'date'],
        ], [
            'user_id.required' => 'Please select a user.',
            'amount.min' => 'Amount must be greater than zero.',
        ]);

        $validated['status'] = 'Pending';

        ExtraCommission::create($validated);

        return redirect()
            ->route('admin.extra-commissions.index')
            ->with('success', 'Extra commission added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ExtraCommission $extraCommission): View
    {
        $extraCommission->load('user');

        return view('admin.extra-commissions.show', [
            'commission' => $extraCommission,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExtraCommission $extraCommission): View
    {
        $users = User::where('is_active', true)->get();

        return view('admin.extra-commissions.edit', [
            'commission' => $extraCommission,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExtraCommission $extraCommission): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['sometimes', 'exists:users,id'],
            'commission_type' => ['sometimes', 'string', 'max:100'],
            'amount' => ['sometimes', 'numeric', 'min:0.01'],
            'description' => ['sometimes', 'string', 'max:1000'],
            'date_earned' => ['sometimes', 'date'],
            'status' => ['sometimes', 'in:Pending,Approved,Paid'],
        ]);

        $extraCommission->update($validated);

        return redirect()
            ->route('admin.extra-commissions.index')
            ->with('success', 'Extra commission updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExtraCommission $extraCommission): RedirectResponse
    {
        $extraCommission->delete();

        return redirect()
            ->route('admin.extra-commissions.index')
            ->with('success', 'Extra commission deleted successfully!');
    }

    /**
     * Approve an extra commission
     */
    public function approve(ExtraCommission $extraCommission): RedirectResponse
    {
        $extraCommission->update([
            'status' => 'Approved',
            'approved_by' => auth()->id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Commission approved successfully!');
    }

    /**
     * Mark an extra commission as paid
     */
    public function markPaid(ExtraCommission $extraCommission): RedirectResponse
    {
        $extraCommission->update(['status' => 'Paid']);

        return redirect()
            ->back()
            ->with('success', 'Commission marked as paid!');
    }
}
