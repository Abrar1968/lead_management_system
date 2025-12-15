<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 text-white shadow-lg shadow-cyan-500/30 mb-4">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Verify Your Email</h2>
        <p class="text-sm text-gray-500 mt-2 max-w-xs mx-auto">
            Thanks for signing up! Please verify your email address by clicking the link we sent to you.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-50 to-green-50 p-4 border border-emerald-200 shadow-lg shadow-emerald-500/10">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-lg shadow-emerald-500/30">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-emerald-800">A new verification link has been sent to your email address.</p>
            </div>
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    class="w-full rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-cyan-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full rounded-xl border-2 border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all">
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
