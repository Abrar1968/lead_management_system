<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Follow-up Rules</h2>
                <p class="mt-1 text-sm text-gray-500">Create rules to automatically suggest leads for follow-up</p>
            </div>
            <a href="{{ route('follow-up-rules.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-semibold shadow-lg shadow-blue-500/30 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Rule
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4">
                    <div class="flex items-center gap-2 text-emerald-800">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Rules Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($rules as $rule)
                    <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden hover:shadow-xl transition-all">
                        <div class="p-6">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-gray-900">{{ $rule->name }}</h3>
                                        @if($rule->user_id === null)
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Global</span>
                                        @endif
                                    </div>
                                    @if($rule->description)
                                        <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $rule->description }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($rule->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Inactive</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 space-y-3">
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="text-gray-500">Logic:</span>
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded bg-blue-100 text-blue-800">{{ $rule->logic_type }}</span>
                                    <span class="text-gray-500">Priority:</span>
                                    <span class="font-semibold text-gray-900">{{ $rule->priority }}</span>
                                </div>

                                <div class="text-sm text-gray-600">
                                    <span class="font-medium">{{ $rule->conditions->count() }} condition(s):</span>
                                    <div class="mt-2 space-y-1">
                                        @foreach($rule->conditions->take(3) as $condition)
                                            <div class="flex items-center gap-1 text-xs bg-gray-50 rounded-lg px-2 py-1">
                                                <span class="font-medium text-gray-700">{{ $condition->field }}</span>
                                                <span class="text-gray-400">{{ $condition->operator }}</span>
                                                <span class="text-gray-900">{{ is_array($condition->value) ? implode(', ', $condition->value) : $condition->value }}</span>
                                            </div>
                                        @endforeach
                                        @if($rule->conditions->count() > 3)
                                            <div class="text-xs text-gray-400">+{{ $rule->conditions->count() - 3 }} more...</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                            <a href="{{ route('follow-up-rules.preview', $rule) }}"
                               class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                Preview Matches
                            </a>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('follow-up-rules.edit', $rule) }}"
                                   class="p-1.5 text-gray-400 hover:text-indigo-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('follow-up-rules.destroy', $rule) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete this rule?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 lg:col-span-3 text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <p class="mt-4 text-lg font-medium text-gray-600">No rules created yet</p>
                        <p class="mt-1 text-sm text-gray-500">Create your first follow-up rule to start getting smart suggestions</p>
                        <a href="{{ route('follow-up-rules.create') }}"
                           class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create First Rule
                        </a>
                    </div>
                @endforelse
            </div>

            @if($rules->hasPages())
                <div class="mt-6">
                    {{ $rules->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
