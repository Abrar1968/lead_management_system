<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Edit Lead
                </h2>
                <p class="mt-1 text-sm font-medium text-gray-500">
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-gray-100 px-3 py-1">
                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                        </svg>
                        {{ $lead->lead_number }}
                    </span>
                </p>
            </div>
            <a href="{{ route('leads.show', $lead) }}"
                class="group inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-300 hover:bg-gray-200 hover:-translate-x-1">
                <svg class="h-5 w-5 transition-transform duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">Update Lead Information</h3>
                            <p class="text-sm text-white/70">Modify the lead details below</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('leads.update', $lead) }}" method="POST" class="p-6" x-data="leadEditForm()">
                    @csrf
                    @method('PUT')

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
                                    <p class="font-medium">This phone number is used by other leads:</p>
                                    <ul class="mt-2 space-y-1">
                                        <template x-for="lead in previousLeads" :key="lead.id">
                                            <li class="flex items-center gap-2 rounded-lg bg-white/50 px-3 py-2">
                                                <span class="font-semibold text-amber-900" x-text="lead.lead_number"></span>
                                                <span class="text-amber-700">-</span>
                                                <span x-text="lead.client_name || 'Unknown'"></span>
                                                <span class="text-amber-600">(<span x-text="lead.lead_date"></span>)</span>
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
                            <input type="date" name="lead_date" id="lead_date"
                                value="{{ old('lead_date', $lead->lead_date->format('Y-m-d')) }}"
                                max="{{ now()->format('Y-m-d') }}"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20"
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
                                    @blur="checkRepeatLead()" value="{{ old('phone_number', $lead->phone_number) }}"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 pl-12 pr-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20"
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
                                <input type="text" name="client_name" id="client_name"
                                    value="{{ old('client_name', $lead->client_name) }}"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 pl-12 pr-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
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
                                <input type="email" name="email" id="email"
                                    value="{{ old('email', $lead->email) }}"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 pl-12 pr-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
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
                                class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20"
                                required>
                                <option value="WhatsApp" {{ old('source', $lead->source) === 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                                <option value="Messenger" {{ old('source', $lead->source) === 'Messenger' ? 'selected' : '' }}>Messenger</option>
                                <option value="Website" {{ old('source', $lead->source) === 'Website' ? 'selected' : '' }}>Website</option>
                            </select>
                            @error('source')
                                <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Service Interested --}}
                        <div class="space-y-2">
                            <label for="service_id" class="block text-sm font-semibold text-gray-700">
                                Service Interested <span class="text-red-500">*</span>
                            </label>
                            <select name="service_id" id="service_id"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20"
                                required>
                                <option value="">Select Service</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id', $lead->service_id) == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-semibold text-gray-700">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20"
                                required>
                                <option value="New" {{ old('status', $lead->status) === 'New' ? 'selected' : '' }}>New</option>
                                <option value="Contacted" {{ old('status', $lead->status) === 'Contacted' ? 'selected' : '' }}>Contacted</option>
                                <option value="Qualified" {{ old('status', $lead->status) === 'Qualified' ? 'selected' : '' }}>Qualified</option>
                                <option value="Negotiation" {{ old('status', $lead->status) === 'Negotiation' ? 'selected' : '' }}>Negotiation</option>
                                <option value="Converted" {{ old('status', $lead->status) === 'Converted' ? 'selected' : '' }}>Converted</option>
                                <option value="Lost" {{ old('status', $lead->status) === 'Lost' ? 'selected' : '' }}>Lost</option>
                            </select>
                            @error('status')
                                <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Priority --}}
                        <div class="space-y-2">
                            <label for="priority" class="block text-sm font-semibold text-gray-700">
                                Priority
                            </label>
                            <select name="priority" id="priority"
                                class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                                <option value="Low" {{ old('priority', $lead->priority) === 'Low' ? 'selected' : '' }}>Low</option>
                                <option value="Medium" {{ old('priority', $lead->priority) === 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="High" {{ old('priority', $lead->priority) === 'High' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')
                                <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Assigned To (Admin only) --}}
                        @if (auth()->user()->isAdmin())
                            <div class="sm:col-span-2 space-y-2">
                                <label for="assigned_to" class="block text-sm font-semibold text-gray-700">
                                    Assign To
                                </label>
                                <select name="assigned_to" id="assigned_to"
                                    class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                                    <option value="">Unassigned</option>
                                    @foreach ($salesPersons as $person)
                                        <option value="{{ $person->id }}" {{ old('assigned_to', $lead->assigned_to) == $person->id ? 'selected' : '' }}>
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
                            class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">{{ old('initial_remarks', $lead->initial_remarks) }}</textarea>
                        @error('initial_remarks')
                            <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Form Actions --}}
                    <div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('leads.show', $lead) }}"
                            class="rounded-xl bg-gray-100 px-6 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-200">
                            Cancel
                        </a>
                        <button type="submit"
                            class="group inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-300 hover:shadow-xl hover:shadow-indigo-500/40 hover:-translate-y-0.5">
                            <svg class="h-5 w-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Lead
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function leadEditForm() {
                return {
                    phoneNumber: '{{ old('phone_number', $lead->phone_number) }}',
                    originalPhone: '{{ $lead->phone_number }}',
                    repeatLeadWarning: false,
                    previousLeads: [],

                    async checkRepeatLead() {
                        // Don't check if phone hasn't changed
                        if (this.phoneNumber === this.originalPhone) {
                            this.repeatLeadWarning = false;
                            return;
                        }

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
                                    exclude_lead_id: {{ $lead->id }},
                                }),
                            });

                            const data = await response.json();
                            this.repeatLeadWarning = data.is_repeat;
                            this.previousLeads = data.previous_leads || [];
                        } catch (error) {
                            console.error('Error checking repeat lead:', error);
                        }
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
