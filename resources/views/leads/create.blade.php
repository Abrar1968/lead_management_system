<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Add New Lead
                </h2>
                <p class="mt-1 text-sm text-gray-500">Create a new lead entry in the system</p>
            </div>
            <a href="{{ route('leads.daily', ['date' => $date]) }}"
                class="group inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-300 hover:bg-gray-200 hover:-translate-x-1">
                <svg class="h-5 w-5 transition-transform duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Daily View
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Lead Information</h3>
                            <p class="text-sm text-white/70">Fill in the details below</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('leads.store') }}" method="POST" class="p-6" x-data="leadForm()"
                    @submit.prevent="submitForm">
                    @csrf

                    {{-- Repeat Lead Warning --}}
                    <div x-show="repeatLeadWarning" x-cloak
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                        class="mb-6 rounded-2xl bg-gradient-to-r from-amber-50 to-orange-50 p-5 border border-amber-200 shadow-lg shadow-amber-500/10">
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/30">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-bold text-amber-800">Repeat Lead Detected!</h3>
                                <div class="mt-2 text-sm text-amber-700">
                                    <p class="font-medium">This phone number has been used before:</p>
                                    <ul class="mt-2 space-y-1">
                                        <template x-for="lead in previousLeads" :key="lead.id">
                                            <li class="flex items-center gap-2 rounded-lg bg-white/50 px-3 py-2">
                                                <span class="font-semibold text-amber-900" x-text="lead.lead_number"></span>
                                                <span class="text-amber-700">-</span>
                                                <span x-text="lead.customer_name || 'Unknown'"></span>
                                                <span class="text-amber-600">(<span x-text="lead.lead_date"></span>)</span>
                                                <span class="rounded-lg bg-amber-200 px-2 py-0.5 text-xs font-semibold" x-text="lead.status"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        {{-- Lead Date --}}
                        <div class="space-y-2">
                            <label for="lead_date" class="block text-sm font-semibold text-gray-700">
                                Lead Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="lead_date" id="lead_date" value="{{ old('lead_date', $date) }}"
                                max="{{ now()->format('Y-m-d') }}"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                                required>
                            @error('lead_date')
                                <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone Number --}}
                        <div class="space-y-2">
                            <label for="phone_number" class="block text-sm font-semibold text-gray-700">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </span>
                                <input type="tel" name="phone_number" id="phone_number" x-model="phoneNumber"
                                    @blur="checkRepeatLead()" value="{{ old('phone_number') }}" placeholder="01XXXXXXXXX"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 pl-12 pr-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                                    required>
                            </div>
                            @error('phone_number')
                                <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Customer Name --}}
                        <div class="space-y-2">
                            <label for="client_name" class="block text-sm font-semibold text-gray-700">
                                Client Name
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </span>
                                <input type="text" name="client_name" id="client_name" value="{{ old('client_name') }}"
                                    placeholder="John Doe"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 pl-12 pr-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                            </div>
                            @error('client_name')
                                <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-semibold text-gray-700">
                                Email
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    placeholder="john@example.com"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 pl-12 pr-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                            </div>
                            @error('email')
                                <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Source --}}
                        <div class="space-y-2">
                            <label for="source" class="block text-sm font-semibold text-gray-700">
                                Source <span class="text-red-500">*</span>
                            </label>
                            <select name="source" id="source"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                                required>
                                <option value="">Select Source</option>
                                <option value="WhatsApp" {{ old('source') === 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                                <option value="Messenger" {{ old('source') === 'Messenger' ? 'selected' : '' }}>Messenger</option>
                                <option value="Website" {{ old('source') === 'Website' ? 'selected' : '' }}>Website</option>
                            </select>
                            @error('source')
                                <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Service Interested --}}
                        <div class="space-y-2">
                            <label for="service_interested" class="block text-sm font-semibold text-gray-700">
                                Service Interested <span class="text-red-500">*</span>
                            </label>
                            <select name="service_interested" id="service_interested"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                                required>
                                <option value="">Select Service</option>
                                <option value="Website" {{ old('service_interested') === 'Website' ? 'selected' : '' }}>Website</option>
                                <option value="Software" {{ old('service_interested') === 'Software' ? 'selected' : '' }}>Software</option>
                                <option value="CRM" {{ old('service_interested') === 'CRM' ? 'selected' : '' }}>CRM</option>
                                <option value="Marketing" {{ old('service_interested') === 'Marketing' ? 'selected' : '' }}>Marketing</option>
                            </select>
                            @error('service_interested')
                                <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Priority --}}
                        <div class="space-y-2">
                            <label for="priority" class="block text-sm font-semibold text-gray-700">
                                Priority
                            </label>
                            <select name="priority" id="priority"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                                <option value="Low" {{ old('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                                <option value="Medium" {{ old('priority', 'Medium') === 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')
                                <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Assigned To (Admin only) --}}
                        @if (auth()->user()->isAdmin())
                            <div class="space-y-2">
                                <label for="assigned_to" class="block text-sm font-semibold text-gray-700">
                                    Assign To
                                </label>
                                <select name="assigned_to" id="assigned_to"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                                    <option value="">Unassigned</option>
                                    @foreach ($salesPersons as $person)
                                        <option value="{{ $person->id }}" {{ old('assigned_to') == $person->id ? 'selected' : '' }}>
                                            {{ $person->name }} ({{ $person->role }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>

                    {{-- Initial Remarks --}}
                    <div class="mt-6 space-y-2">
                        <label for="initial_remarks" class="block text-sm font-semibold text-gray-700">
                            Initial Remarks
                        </label>
                        <textarea name="initial_remarks" id="initial_remarks" rows="4"
                            placeholder="Any initial notes about this lead..."
                            class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20">{{ old('initial_remarks') }}</textarea>
                        @error('initial_remarks')
                            <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Form Actions --}}
                    <div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('leads.daily', ['date' => $date]) }}"
                            class="rounded-xl bg-gray-100 px-6 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-200">
                            Cancel
                        </a>
                        <button type="submit"
                            class="group inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/40 hover:-translate-y-0.5">
                            <svg class="h-5 w-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Create Lead
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function leadForm() {
                return {
                    phoneNumber: '{{ old('phone_number') }}',
                    repeatLeadWarning: false,
                    previousLeads: [],

                    async checkRepeatLead() {
                        if (!this.phoneNumber || this.phoneNumber.length < 5) {
                            this.repeatLeadWarning = false;
                            return;
                        }

                        try {
                            const response = await fetch('{{ route('leads.check-repeat') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                body: JSON.stringify({
                                    phone_number: this.phoneNumber,
                                }),
                            });

                            const data = await response.json();
                            this.repeatLeadWarning = data.is_repeat;
                            this.previousLeads = data.previous_leads || [];
                        } catch (error) {
                            console.error('Error checking repeat lead:', error);
                        }
                    },

                    submitForm() {
                        this.$el.submit();
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
