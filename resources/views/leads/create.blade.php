<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Add New Lead
            </h2>
            <a href="{{ route('leads.daily', ['date' => $date]) }}"
               class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                <svg class="mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Daily View
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <form action="{{ route('leads.store') }}" method="POST" class="p-6"
                      x-data="leadForm()"
                      @submit.prevent="submitForm">
                    @csrf

                    {{-- Repeat Lead Warning --}}
                    <div x-show="repeatLeadWarning" x-cloak
                         class="mb-6 rounded-lg bg-yellow-50 p-4 border border-yellow-200">
                        <div class="flex">
                            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Repeat Lead Detected!</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>This phone number has been used before:</p>
                                    <ul class="mt-2 list-disc list-inside">
                                        <template x-for="lead in previousLeads" :key="lead.id">
                                            <li>
                                                <span x-text="lead.lead_number"></span> -
                                                <span x-text="lead.customer_name || 'Unknown'"></span>
                                                (<span x-text="lead.lead_date"></span>) -
                                                <span x-text="lead.status"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        {{-- Lead Date --}}
                        <div>
                            <label for="lead_date" class="block text-sm font-medium text-gray-700">
                                Lead Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="lead_date" id="lead_date"
                                   value="{{ old('lead_date', $date) }}"
                                   max="{{ now()->format('Y-m-d') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   required>
                            @error('lead_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone Number --}}
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone_number" id="phone_number"
                                   x-model="phoneNumber"
                                   @blur="checkRepeatLead()"
                                   value="{{ old('phone_number') }}"
                                   placeholder="01XXXXXXXXX"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   required>
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Customer Name --}}
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700">
                                Customer Name
                            </label>
                            <input type="text" name="customer_name" id="customer_name"
                                   value="{{ old('customer_name') }}"
                                   placeholder="John Doe"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('customer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Email
                            </label>
                            <input type="email" name="email" id="email"
                                   value="{{ old('email') }}"
                                   placeholder="john@example.com"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Source --}}
                        <div>
                            <label for="source" class="block text-sm font-medium text-gray-700">
                                Source <span class="text-red-500">*</span>
                            </label>
                            <select name="source" id="source"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required>
                                <option value="">Select Source</option>
                                <option value="WhatsApp" {{ old('source') === 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                                <option value="Messenger" {{ old('source') === 'Messenger' ? 'selected' : '' }}>Messenger</option>
                                <option value="Website" {{ old('source') === 'Website' ? 'selected' : '' }}>Website</option>
                            </select>
                            @error('source')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Service Interested --}}
                        <div>
                            <label for="service_interested" class="block text-sm font-medium text-gray-700">
                                Service Interested <span class="text-red-500">*</span>
                            </label>
                            <select name="service_interested" id="service_interested"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required>
                                <option value="">Select Service</option>
                                <option value="Website" {{ old('service_interested') === 'Website' ? 'selected' : '' }}>Website</option>
                                <option value="Software" {{ old('service_interested') === 'Software' ? 'selected' : '' }}>Software</option>
                                <option value="CRM" {{ old('service_interested') === 'CRM' ? 'selected' : '' }}>CRM</option>
                                <option value="Marketing" {{ old('service_interested') === 'Marketing' ? 'selected' : '' }}>Marketing</option>
                            </select>
                            @error('service_interested')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Priority --}}
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700">
                                Priority
                            </label>
                            <select name="priority" id="priority"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="Low" {{ old('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                                <option value="Medium" {{ old('priority', 'Medium') === 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Assigned To (Admin only) --}}
                        @if(auth()->user()->isAdmin())
                            <div>
                                <label for="assigned_to" class="block text-sm font-medium text-gray-700">
                                    Assign To
                                </label>
                                <select name="assigned_to" id="assigned_to"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Unassigned</option>
                                    @foreach($salesPersons as $person)
                                        <option value="{{ $person->id }}" {{ old('assigned_to') == $person->id ? 'selected' : '' }}>
                                            {{ $person->name }} ({{ $person->role }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>

                    {{-- Initial Remarks --}}
                    <div class="mt-6">
                        <label for="initial_remarks" class="block text-sm font-medium text-gray-700">
                            Initial Remarks
                        </label>
                        <textarea name="initial_remarks" id="initial_remarks" rows="3"
                                  placeholder="Any initial notes about this lead..."
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('initial_remarks') }}</textarea>
                        @error('initial_remarks')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Form Actions --}}
                    <div class="mt-6 flex items-center justify-end gap-3">
                        <a href="{{ route('leads.daily', ['date' => $date]) }}"
                           class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                            Cancel
                        </a>
                        <button type="submit"
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
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
