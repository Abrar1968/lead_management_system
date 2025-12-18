<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Edit Field: ') }} {{ $fieldDefinition->label }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <form action="{{ route('field-definitions.update', $fieldDefinition) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Field Name (Read Only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Field Name
                        </label>
                        <div class="px-4 py-2 bg-gray-100 rounded-xl">
                            <code class="font-mono">{{ $fieldDefinition->name }}</code>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Field name cannot be changed after creation</p>
                    </div>

                    <!-- Model Type (Read Only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Model Type
                        </label>
                        <div class="px-4 py-2 bg-gray-100 rounded-xl">
                            {{ ucfirst($fieldDefinition->model_type) }}
                        </div>
                    </div>

                    <!-- Label -->
                    <div>
                        <label for="label" class="block text-sm font-medium text-gray-700 mb-2">
                            Display Label <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="label" id="label" value="{{ old('label', $fieldDefinition->label) }}"
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
                            <option value="text" {{ old('type', $fieldDefinition->type) === 'text' ? 'selected' : '' }}>Text</option>
                            <option value="image" {{ old('type', $fieldDefinition->type) === 'image' ? 'selected' : '' }}>Image</option>
                            <option value="link" {{ old('type', $fieldDefinition->type) === 'link' ? 'selected' : '' }}>Link (URL)</option>
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
                        <input type="number" name="order" id="order" value="{{ old('order', $fieldDefinition->order) }}"
                               min="0"
                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Required -->
                    <div class="flex items-center">
                        <input type="checkbox" name="required" id="required" value="1"
                               {{ old('required', $fieldDefinition->required) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <label for="required" class="ml-2 text-sm font-medium text-gray-700">
                            Required Field
                        </label>
                    </div>

                    <!-- Active -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $fieldDefinition->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                            Active (visible in forms)
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-4 pt-4 border-t">
                        <a href="{{ route('field-definitions.index', ['type' => $fieldDefinition->model_type]) }}"
                           class="px-4 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 font-semibold transition-all">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-semibold shadow-lg transition-all">
                            Update Field
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
