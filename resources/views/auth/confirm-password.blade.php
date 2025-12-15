<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 text-white shadow-lg shadow-rose-500/30 mb-4">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Confirm Password</h2>
        <p class="text-sm text-gray-500 mt-2 max-w-xs mx-auto">
            This is a secure area. Please confirm your password before continuing.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="w-full rounded-xl border-gray-200 bg-gray-50 pl-12 pr-4 py-3 text-sm font-medium transition-all duration-200 focus:border-rose-500 focus:bg-white focus:ring-2 focus:ring-rose-500/20"
                       placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full rounded-xl bg-gradient-to-r from-rose-600 to-red-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-rose-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
            Confirm Password
        </button>
    </form>
</x-guest-layout>
