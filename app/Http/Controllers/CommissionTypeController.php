<?php

namespace App\Http\Controllers;

use App\Models\CommissionType;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CommissionTypeController extends Controller
{
    /**
     * Display all commission types
     */
    public function index(): View
    {
        $commissionTypes = CommissionType::withCount('users')
            ->orderBy('name')
            ->get();

        return view('admin.commission-types.index', [
            'commissionTypes' => $commissionTypes,
        ]);
    }

    /**
     * Show create form
     */
    public function create(): View
    {
        return view('admin.commission-types.create');
    }

    /**
     * Store new commission type
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:commission_types,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'calculation_type' => ['required', 'in:fixed,percentage'],
            'default_rate' => ['required', 'numeric', 'min:0', 'max:100000'],
            'is_active' => ['boolean'],
            'is_default' => ['boolean'],
        ], [
            'name.required' => 'Please enter a commission type name.',
            'name.unique' => 'A commission type with this name already exists.',
            'default_rate.required' => 'Please enter a default rate.',
        ]);

        // Additional validation for percentage
        if ($validated['calculation_type'] === 'percentage' && $validated['default_rate'] > 100) {
            return back()->withErrors(['default_rate' => 'Percentage cannot exceed 100%.'])->withInput();
        }

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_default'] = $request->boolean('is_default', false);

        CommissionType::create($validated);

        return redirect()
            ->route('admin.commission-types.index')
            ->with('success', 'Commission type created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit(CommissionType $commissionType): View
    {
        $commissionType->loadCount('users');

        return view('admin.commission-types.edit', [
            'commissionType' => $commissionType,
        ]);
    }

    /**
     * Update commission type
     */
    public function update(Request $request, CommissionType $commissionType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:commission_types,name,'.$commissionType->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'calculation_type' => ['required', 'in:fixed,percentage'],
            'default_rate' => ['required', 'numeric', 'min:0', 'max:100000'],
            'is_active' => ['boolean'],
            'is_default' => ['boolean'],
        ]);

        // Additional validation for percentage
        if ($validated['calculation_type'] === 'percentage' && $validated['default_rate'] > 100) {
            return back()->withErrors(['default_rate' => 'Percentage cannot exceed 100%.'])->withInput();
        }

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_default'] = $request->boolean('is_default', false);

        $commissionType->update($validated);

        return redirect()
            ->route('admin.commission-types.index')
            ->with('success', 'Commission type updated successfully!');
    }

    /**
     * Delete commission type
     */
    public function destroy(CommissionType $commissionType): RedirectResponse
    {
        // Check if any users are assigned
        if ($commissionType->users()->count() > 0) {
            return back()->with('error', 'Cannot delete a commission type that is assigned to users.');
        }

        $commissionType->delete();

        return redirect()
            ->route('admin.commission-types.index')
            ->with('success', 'Commission type deleted successfully!');
    }

    /**
     * Assign commission type to a user
     */
    public function assignToUser(Request $request, CommissionType $commissionType): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'custom_rate' => ['nullable', 'numeric', 'min:0'],
            'is_primary' => ['boolean'],
        ]);

        $user = User::findOrFail($validated['user_id']);

        // Check if user is already assigned to this commission type
        if ($commissionType->users()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['user_id' => 'This user is already assigned to this commission type.'])->withInput();
        }

        $isPrimary = $request->boolean('is_primary', false);

        // If making this primary, unset other primaries for this user
        if ($isPrimary) {
            $user->commissionTypes()->updateExistingPivot(
                $user->commissionTypes()->pluck('commission_types.id'),
                ['is_primary' => false]
            );
        }

        // Attach the commission type
        $user->commissionTypes()->attach($commissionType->id, [
            'custom_rate' => $validated['custom_rate'] ?? null,
            'is_primary' => $isPrimary,
        ]);

        return redirect()
            ->route('admin.commission-types.users', $commissionType)
            ->with('success', "Commission type assigned to {$user->name} successfully!");
    }

    /**
     * Remove commission type from a user
     */
    public function removeFromUser(CommissionType $commissionType, User $user): RedirectResponse
    {
        $user->commissionTypes()->detach($commissionType->id);

        return redirect()
            ->route('admin.commission-types.users', $commissionType)
            ->with('success', "Commission type removed from {$user->name} successfully!");
    }

    /**
     * Show users assigned to this commission type
     */
    public function users(CommissionType $commissionType): View
    {
        $assignedUsers = $commissionType->users()->orderBy('name')->get();
        $availableUsers = User::where('is_active', true)
            ->whereNotIn('id', $assignedUsers->pluck('id'))
            ->orderBy('name')
            ->get();

        return view('admin.commission-types.users', [
            'commissionType' => $commissionType,
            'assignedUsers' => $assignedUsers,
            'availableUsers' => $availableUsers,
        ]);
    }
}
