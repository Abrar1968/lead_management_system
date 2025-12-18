<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Add New Field for ') }} {{ ucfirst($modelType) }}s
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <form action="{{ route('field-definitions.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="model_type" value="{{ $modelType }}">

                    <!-- Field Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Field Name (lowercase, underscores only) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               pattern="[a-z_]+" required
                               placeholder="e.g., company_website"
                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Use snake_case format (e.g., company_website, logo_image)</p>
                    </div>

                    <!-- Label -->
                    <div>
                        <label for="label" class="block text-sm font-medium text-gray-700 mb-2">
                            Display Label <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="label" id="label" value="{{ old('label') }}"
                               required placeholder="e.g., Company Website"
                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('label')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Field Type <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="text" {{ old('type') === 'text' ? 'selected' : '' }}>Text</option>
                            <option value="image" {{ old('type') === 'image' ? 'selected' : '' }}>Image</option>
                            <option value="link" {{ old('type') === 'link' ? 'selected' : '' }}>Link (URL)</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Display Order
                        </label>
                        <input type="number" name="order" id="order" value="{{ old('order', 0) }}"
                               min="0"
                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Required -->
                    <div class="flex items-center">
                        <input type="checkbox" name="required" id="required" value="1"
                               {{ old('required') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <label for="required" class="ml-2 text-sm font-medium text-gray-700">
                            Required Field
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-4 pt-4 border-t">
                        <a href="{{ route('field-definitions.index', ['type' => $modelType]) }}"
                           class="px-4 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 font-semibold transition-all">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-semibold shadow-lg transition-all">
                            Create Field
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
