<?php

namespace App\Http\Controllers;

use App\Models\FieldDefinition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FieldDefinitionController extends Controller
{
    /**
     * Display field definitions for a model type.
     */
    public function index(Request $request): View
    {
        $modelType = $request->get('type', 'client');

        $fields = FieldDefinition::where('model_type', $modelType)
            ->orderBy('order')
            ->get();

        return view('field-definitions.index', compact('fields', 'modelType'));
    }

    /**
     * Show the form for creating a new field.
     */
    public function create(Request $request): View
    {
        $modelType = $request->get('type', 'client');

        return view('field-definitions.create', compact('modelType'));
    }

    /**
     * Store a newly created field.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'model_type' => 'required|in:client,demo',
            'name' => 'required|string|max:100|regex:/^[a-z_]+$/|unique:field_definitions,name,NULL,id,model_type,'.$request->model_type,
            'label' => 'required|string|max:255',
            'type' => 'required|in:text,image,link',
            'required' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['required'] = $request->boolean('required');
        $validated['order'] = $validated['order'] ?? FieldDefinition::where('model_type', $validated['model_type'])->max('order') + 1;

        FieldDefinition::create($validated);

        return redirect()
            ->route('field-definitions.index', ['type' => $validated['model_type']])
            ->with('success', 'Field created successfully.');
    }

    /**
     * Show the form for editing a field.
     */
    public function edit(FieldDefinition $fieldDefinition): View
    {
        return view('field-definitions.edit', compact('fieldDefinition'));
    }

    /**
     * Update the specified field.
     */
    public function update(Request $request, FieldDefinition $fieldDefinition): RedirectResponse
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|in:text,image,link',
            'required' => 'boolean',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['required'] = $request->boolean('required');
        $validated['is_active'] = $request->boolean('is_active');

        $fieldDefinition->update($validated);

        return redirect()
            ->route('field-definitions.index', ['type' => $fieldDefinition->model_type])
            ->with('success', 'Field updated successfully.');
    }

    /**
     * Remove the specified field (and all its values).
     */
    public function destroy(FieldDefinition $fieldDefinition): RedirectResponse
    {
        $modelType = $fieldDefinition->model_type;
        $fieldDefinition->delete();

        return redirect()
            ->route('field-definitions.index', ['type' => $modelType])
            ->with('success', 'Field deleted successfully.');
    }

    /**
     * Reorder fields via AJAX.
     */
    public function reorder(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:field_definitions,id',
        ]);

        foreach ($request->order as $position => $id) {
            FieldDefinition::where('id', $id)->update(['order' => $position]);
        }

        return response()->json(['success' => true]);
    }
}
