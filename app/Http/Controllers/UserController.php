<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CommissionService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private CommissionService $commissionService) {}

    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $users = $query->withCount(['leads', 'conversions'])
            ->orderBy('name')
            ->paginate(20);

        return view('users.index', [
            'users' => $users,
            'currentRole' => $request->role,
            'currentSearch' => $request->search,
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,sales_person',
            'commission_type' => 'required|in:fixed,percentage',
            'default_commission_rate' => 'required|numeric|min:0',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'commission_type' => $validated['commission_type'],
            'default_commission_rate' => $validated['default_commission_rate'],
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display user performance page.
     */
    public function show(User $user): View
    {
        $month = request()->input('month', now()->format('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        // Get monthly stats
        $monthlyLeads = $user->leads()->whereBetween('lead_date', [$startDate, $endDate])->count();
        $monthlyConversions = $user->conversions()->whereBetween('conversion_date', [$startDate, $endDate])->get();
        $monthlyCommission = $monthlyConversions->sum('commission_amount');
        $monthlyDealValue = $monthlyConversions->sum('deal_value');

        // Get commission breakdown
        $breakdown = $this->commissionService->getCommissionBreakdown($user, $month);

        // Get recent activity
        $recentLeads = $user->leads()
            ->with('conversion')
            ->orderByDesc('lead_date')
            ->limit(10)
            ->get();

        return view('users.show', [
            'user' => $user,
            'month' => $month,
            'monthlyLeads' => $monthlyLeads,
            'monthlyConversions' => $monthlyConversions->count(),
            'monthlyCommission' => $monthlyCommission,
            'monthlyDealValue' => $monthlyDealValue,
            'breakdown' => $breakdown,
            'recentLeads' => $recentLeads,
        ]);
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,sales_person',
            'commission_type' => 'required|in:fixed,percentage',
            'default_commission_rate' => 'required|numeric|min:0',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'commission_type' => $validated['commission_type'],
            'default_commission_rate' => $validated['default_commission_rate'],
        ]);

        if (! empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Check for assigned leads
        if ($user->leads()->exists()) {
            return back()->with('error', 'Cannot delete user with assigned leads. Reassign leads first.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
