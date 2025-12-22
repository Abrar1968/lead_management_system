<?php

namespace App\Http\Controllers;

use App\Models\ClientDetail;
use App\Models\FieldDefinition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    /**
     * Display a listing of clients (converted leads).
     */
    public function index(Request $request): View
    {
        $query = ClientDetail::query()
            ->with(['conversion.lead.assignedTo', 'conversion.convertedBy', 'fieldValues.fieldDefinition']);

        // Filter by sales person if not admin
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->isSalesPerson()) {
            $query->whereHas('conversion.lead', fn ($q) => $q->where('assigned_to', $user->id));
        }

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('conversion.lead', fn ($lq) => $lq->where('client_name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%"))
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('support_contact_person', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate(15)->withQueryString();
        $dynamicFields = FieldDefinition::forClient()->get();

        return view('clients.index', compact('clients', 'dynamicFields'));
    }

    /**
     * Display the specified client.
     */
    public function show(ClientDetail $client): View
    {
        $client->load(['conversion.lead.assignedTo', 'conversion.convertedBy', 'fieldValues.fieldDefinition']);
        $dynamicFields = FieldDefinition::forClient()->get();

        return view('clients.show', compact('client', 'dynamicFields'));
    }

    /**
     * Show the form for editing the client.
     */
    public function edit(ClientDetail $client): View
    {
        $client->load(['conversion.lead', 'fieldValues.fieldDefinition']);
        $dynamicFields = FieldDefinition::forClient()->get();

        return view('clients.edit', compact('client', 'dynamicFields'));
    }

    /**
     * Update the specified client.
     */
    public function update(Request $request, ClientDetail $client): RedirectResponse
    {
        // Base validation
        $rules = [
            'address' => 'nullable|string|max:500',
            'billing_info' => 'nullable|string|max:1000',
            'support_contact_person' => 'nullable|string|max:255',
            'whatsapp_group_created' => 'boolean',
            'feedback' => 'nullable|string|max:1000',
            'remarketing_eligible' => 'boolean',
        ];

        // Dynamic field validation
        $dynamicFields = FieldDefinition::forClient()->get();
        foreach ($dynamicFields as $field) {
            $fieldKey = 'dynamic_'.$field->name;
            if ($field->type === 'image') {
                $rules[$fieldKey] = ($field->required ? 'required|' : 'nullable|').'image|max:2048';
            } elseif ($field->type === 'document') {
                $rules[$fieldKey] = ($field->required ? 'required|' : 'nullable|').'file|mimes:pdf,doc,docx,xls,xlsx,txt|max:5120'; // 5MB max
            } elseif ($field->type === 'link') {
                $rules[$fieldKey] = ($field->required ? 'required|' : 'nullable|').'url|max:500';
            } else {
                $rules[$fieldKey] = ($field->required ? 'required|' : 'nullable|').'string|max:500';
            }
        }

        $validated = $request->validate($rules);

        // Update base fields
        $client->update([
            'address' => $validated['address'] ?? null,
            'billing_info' => $validated['billing_info'] ?? null,
            'support_contact_person' => $validated['support_contact_person'] ?? null,
            'whatsapp_group_created' => $request->boolean('whatsapp_group_created'),
            'feedback' => $validated['feedback'] ?? null,
            'remarketing_eligible' => $request->boolean('remarketing_eligible'),
        ]);

        // Update dynamic fields
        foreach ($dynamicFields as $field) {
            $fieldKey = 'dynamic_'.$field->name;
            $value = null;

            if ($field->type === 'image' && $request->hasFile($fieldKey)) {
                // Handle image upload using GD
                $file = $request->file($fieldKey);
                $path = $this->processImageUpload($file, $client->id, $field->name);
                $value = $path;
            } elseif ($field->type === 'document' && $request->hasFile($fieldKey)) {
                // Handle document upload
                $file = $request->file($fieldKey);
                $extension = $file->getClientOriginalExtension();
                $filename = "client_{$client->id}_{$field->name}_".time().'.'.$extension;
                $path = $file->storeAs('clients/documents', $filename, 'public');
                $value = $path;
            } elseif ($field->type !== 'image' && $field->type !== 'document') {
                $value = $validated[$fieldKey] ?? null;
            } else {
                // Keep existing image if no new upload
                continue;
            }

            $client->setFieldValue($field->id, $value);

            // Check if this field represents Deal Value/Price and update conversion/commission
            $normalizedFieldName = strtolower(str_replace([' ', '_', '-'], '', $field->name));
            if (in_array($normalizedFieldName, ['price', 'dealvalue', 'amount', 'cost', 'packageprice', 'value'])) {
                // If value is numeric, update conversion
                $numericValue = (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                if ($numericValue > 0) {
                    $conversion = $client->conversion;
                    $convertedBy = $conversion->convertedBy;

                    if ($convertedBy) {
                        $commissionService = app(\App\Services\CommissionService::class);
                        $newCommission = $commissionService->calculateCommission($convertedBy, $numericValue);

                        $conversion->update([
                            'deal_value' => $numericValue,
                            'commission_amount' => $newCommission,
                        ]);
                    } else {
                        $conversion->update([
                            'deal_value' => $numericValue,
                        ]);
                    }
                }
            }
        }

        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Process image upload using GD extension (no facade).
     */
    private function processImageUpload($file, int $clientId, string $fieldName): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = "client_{$clientId}_{$fieldName}_".time().'.'.$extension;
        $directory = storage_path('app/public/clients');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $targetPath = $directory.'/'.$filename;

        // Load the image based on type
        $sourceImage = match (strtolower($extension)) {
            'jpg', 'jpeg' => \imagecreatefromjpeg($file->getPathname()),
            'png' => \imagecreatefrompng($file->getPathname()),
            'gif' => \imagecreatefromgif($file->getPathname()),
            'webp' => \imagecreatefromwebp($file->getPathname()),
            default => throw new \Exception('Unsupported image format'),
        };

        // Resize if larger than 800px
        $width = \imagesx($sourceImage);
        $height = \imagesy($sourceImage);
        $maxDimension = 800;

        if ($width > $maxDimension || $height > $maxDimension) {
            $ratio = min($maxDimension / $width, $maxDimension / $height);
            $newWidth = (int) ($width * $ratio);
            $newHeight = (int) ($height * $ratio);

            $resizedImage = \imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG/GIF
            if (in_array(strtolower($extension), ['png', 'gif'])) {
                \imagealphablending($resizedImage, false);
                \imagesavealpha($resizedImage, true);
            }

            \imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            \imagedestroy($sourceImage);
            $sourceImage = $resizedImage;
        }

        // Save the image
        match (strtolower($extension)) {
            'jpg', 'jpeg' => \imagejpeg($sourceImage, $targetPath, 85),
            'png' => \imagepng($sourceImage, $targetPath, 8),
            'gif' => \imagegif($sourceImage, $targetPath),
            'webp' => \imagewebp($sourceImage, $targetPath, 85),
        };

        \imagedestroy($sourceImage);

        return 'clients/'.$filename;
    }

    /**
     * Remove a dynamic field image.
     */
    public function removeImage(Request $request, ClientDetail $client): RedirectResponse
    {
        $fieldId = $request->input('field_id');
        $fieldValue = $client->fieldValues()->where('field_definition_id', $fieldId)->first();

        if ($fieldValue && $fieldValue->value) {
            // Delete the file
            $path = storage_path('app/public/'.$fieldValue->value);
            if (file_exists($path)) {
                unlink($path);
            }
            $fieldValue->delete();
        }

        return back()->with('success', 'Image removed successfully.');
    }
}
