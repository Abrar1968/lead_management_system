<?php

namespace App\Http\Controllers;

use App\Models\Demo;
use App\Models\FieldDefinition;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DemoController extends Controller
{
    /**
     * Display a listing of demos.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        $query = Demo::with(['lead', 'createdBy', 'fieldValues.fieldDefinition']);

        // Filter by user if sales person
        if ($user->isSalesPerson()) {
            $query->where('created_by', $user->id);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter by date
        if ($date = $request->input('date')) {
            $query->whereDate('demo_date', $date);
        }

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('lead', fn ($lq) => $lq->where('client_name', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%"));
            });
        }

        $demos = $query->orderByDesc('demo_date')->orderByDesc('demo_time')->paginate(15)->withQueryString();
        $dynamicFields = FieldDefinition::forDemo()->get();

        return view('demos.index', compact('demos', 'dynamicFields'));
    }

    /**
     * Show the form for creating a new demo.
     */
    public function create(Request $request): View
    {
        $leads = Lead::query()
            ->where('status', '!=', 'Converted')
            ->orderByDesc('created_at')
            ->limit(100)
            ->get(['id', 'client_name', 'phone_number']);

        $dynamicFields = FieldDefinition::forDemo()->get();
        $selectedLead = $request->input('lead_id');

        return view('demos.create', compact('leads', 'dynamicFields', 'selectedLead'));
    }

    /**
     * Store a newly created demo.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'lead_id' => 'nullable|exists:leads,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'demo_date' => 'required|date',
            'demo_time' => 'nullable|date_format:H:i',
            'type' => 'required|in:Online,Physical',
            'meeting_link' => 'nullable|url|max:500',
            'location' => 'nullable|string|max:500',
        ];

        // Dynamic field validation
        $dynamicFields = FieldDefinition::forDemo()->get();
        foreach ($dynamicFields as $field) {
            $fieldKey = 'dynamic_'.$field->name;
            if ($field->type === 'image') {
                $rules[$fieldKey] = ($field->required ? 'required|' : 'nullable|').'image|max:2048';
            } elseif ($field->type === 'link') {
                $rules[$fieldKey] = ($field->required ? 'required|' : 'nullable|').'url|max:500';
            } else {
                $rules[$fieldKey] = ($field->required ? 'required|' : 'nullable|').'string|max:500';
            }
        }

        $validated = $request->validate($rules);

        $demo = Demo::create([
            'lead_id' => $validated['lead_id'] ?? null,
            'created_by' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'demo_date' => $validated['demo_date'],
            'demo_time' => $validated['demo_time'] ?? null,
            'type' => $validated['type'],
            'meeting_link' => $validated['meeting_link'] ?? null,
            'location' => $validated['location'] ?? null,
            'status' => 'Scheduled',
        ]);

        // Save dynamic fields
        foreach ($dynamicFields as $field) {
            $fieldKey = 'dynamic_'.$field->name;
            $value = null;

            if ($field->type === 'image' && $request->hasFile($fieldKey)) {
                $value = $this->processImageUpload($request->file($fieldKey), $demo->id, $field->name);
            } elseif ($field->type !== 'image') {
                $value = $validated[$fieldKey] ?? null;
            }

            if ($value !== null) {
                $demo->setFieldValue($field->id, $value);
            }
        }

        return redirect()
            ->route('demos.show', $demo)
            ->with('success', 'Demo scheduled successfully.');
    }

    /**
     * Display the specified demo.
     */
    public function show(Demo $demo): View
    {
        $demo->load(['lead', 'createdBy', 'fieldValues.fieldDefinition']);
        $dynamicFields = FieldDefinition::forDemo()->get();

        return view('demos.show', compact('demo', 'dynamicFields'));
    }

    /**
     * Show the form for editing the demo.
     */
    public function edit(Demo $demo): View
    {
        $leads = Lead::query()
            ->where('status', '!=', 'Converted')
            ->orderByDesc('created_at')
            ->limit(100)
            ->get(['id', 'client_name', 'phone_number']);

        $demo->load(['fieldValues.fieldDefinition']);
        $dynamicFields = FieldDefinition::forDemo()->get();

        return view('demos.edit', compact('demo', 'leads', 'dynamicFields'));
    }

    /**
     * Update the specified demo.
     */
    public function update(Request $request, Demo $demo): RedirectResponse
    {
        $rules = [
            'lead_id' => 'nullable|exists:leads,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'demo_date' => 'required|date',
            'demo_time' => 'nullable|date_format:H:i',
            'type' => 'required|in:Online,Physical',
            'status' => 'required|in:Scheduled,Completed,Cancelled,Rescheduled',
            'outcome_notes' => 'nullable|string|max:1000',
            'meeting_link' => 'nullable|url|max:500',
            'location' => 'nullable|string|max:500',
        ];

        // Dynamic field validation
        $dynamicFields = FieldDefinition::forDemo()->get();
        foreach ($dynamicFields as $field) {
            $fieldKey = 'dynamic_'.$field->name;
            if ($field->type === 'image') {
                $rules[$fieldKey] = ($field->required ? 'required|' : 'nullable|').'image|max:2048';
            } elseif ($field->type === 'link') {
                $rules[$fieldKey] = ($field->required ? 'required|' : 'nullable|').'url|max:500';
            } else {
                $rules[$fieldKey] = ($field->required ? 'required|' : 'nullable|').'string|max:500';
            }
        }

        $validated = $request->validate($rules);

        $demo->update([
            'lead_id' => $validated['lead_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'demo_date' => $validated['demo_date'],
            'demo_time' => $validated['demo_time'] ?? null,
            'type' => $validated['type'],
            'status' => $validated['status'],
            'outcome_notes' => $validated['outcome_notes'] ?? null,
            'meeting_link' => $validated['meeting_link'] ?? null,
            'location' => $validated['location'] ?? null,
        ]);

        // Update dynamic fields
        foreach ($dynamicFields as $field) {
            $fieldKey = 'dynamic_'.$field->name;
            $value = null;

            if ($field->type === 'image' && $request->hasFile($fieldKey)) {
                $value = $this->processImageUpload($request->file($fieldKey), $demo->id, $field->name);
            } elseif ($field->type !== 'image') {
                $value = $validated[$fieldKey] ?? null;
            } else {
                continue; // Keep existing image
            }

            $demo->setFieldValue($field->id, $value);
        }

        return redirect()
            ->route('demos.show', $demo)
            ->with('success', 'Demo updated successfully.');
    }

    /**
     * Remove the specified demo.
     */
    public function destroy(Demo $demo): RedirectResponse
    {
        $demo->delete();

        return redirect()
            ->route('demos.index')
            ->with('success', 'Demo deleted successfully.');
    }

    /**
     * Process image upload using GD extension.
     */
    private function processImageUpload($file, int $demoId, string $fieldName): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = "demo_{$demoId}_{$fieldName}_".time().'.'.$extension;
        $directory = storage_path('app/public/demos');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $targetPath = $directory.'/'.$filename;

        // Load the image based on type
        $sourceImage = match (strtolower($extension)) {
            'jpg', 'jpeg' => imagecreatefromjpeg($file->getPathname()),
            'png' => imagecreatefrompng($file->getPathname()),
            'gif' => imagecreatefromgif($file->getPathname()),
            'webp' => imagecreatefromwebp($file->getPathname()),
            default => throw new \Exception('Unsupported image format'),
        };

        // Resize if larger than 800px
        $width = imagesx($sourceImage);
        $height = imagesy($sourceImage);
        $maxDimension = 800;

        if ($width > $maxDimension || $height > $maxDimension) {
            $ratio = min($maxDimension / $width, $maxDimension / $height);
            $newWidth = (int) ($width * $ratio);
            $newHeight = (int) ($height * $ratio);

            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

            if (in_array(strtolower($extension), ['png', 'gif'])) {
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
            }

            imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($sourceImage);
            $sourceImage = $resizedImage;
        }

        match (strtolower($extension)) {
            'jpg', 'jpeg' => imagejpeg($sourceImage, $targetPath, 85),
            'png' => imagepng($sourceImage, $targetPath, 8),
            'gif' => imagegif($sourceImage, $targetPath),
            'webp' => imagewebp($sourceImage, $targetPath, 85),
        };

        imagedestroy($sourceImage);

        return 'demos/'.$filename;
    }

    /**
     * Remove a dynamic field image.
     */
    public function removeImage(Request $request, Demo $demo): RedirectResponse
    {
        $fieldId = $request->input('field_id');
        $fieldValue = $demo->fieldValues()->where('field_definition_id', $fieldId)->first();

        if ($fieldValue && $fieldValue->value) {
            $path = storage_path('app/public/'.$fieldValue->value);
            if (file_exists($path)) {
                unlink($path);
            }
            $fieldValue->delete();
        }

        return back()->with('success', 'Image removed successfully.');
    }
}
