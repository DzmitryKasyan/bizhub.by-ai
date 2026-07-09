<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\ListingDocument;
use App\Services\ListingDealService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListingDocumentController extends Controller
{
    public function __construct(private readonly ListingDealService $dealService)
    {
    }

    /**
     * Download a listing document with access control.
     * Public documents are available to everyone.
     * Confidential documents require NDA or ownership/moderation.
     */
    public function download(Request $request, Listing $listing, ListingDocument $document): StreamedResponse
    {
        abort_if($document->listing_id !== $listing->id, 404);

        $user = $request->user();

        if ($document->is_confidential) {
            abort_unless(
                $this->dealService->canAccessDataRoom($listing, $user),
                403,
                'Доступ к документу требует подписания NDA.'
            );
        }

        abort_unless(Storage::disk('documents')->exists($document->path), 404);

        return Storage::disk('documents')->download(
            $document->path,
            $document->original_name
        );
    }

    /**
     * Store new documents for a listing (seller or moderator).
     */
    public function store(Request $request, Listing $listing): \Illuminate\Http\RedirectResponse
    {
        abort_unless($listing->isOwnedBy($request->user()) || $request->user()->isModerator(), 403);

        $validated = $request->validate([
            'documents' => 'required|array|max:10',
            'documents.*' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,txt,jpg,jpeg,png,webp|max:10240',
            'document_type' => 'nullable|string|in:financial,legal,presentation,other',
        ]);

        foreach ($request->file('documents') as $file) {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = \Illuminate\Support\Str::uuid() . '.' . $extension;

            $path = Storage::disk('documents')->putFileAs('', $file, $filename);

            $listing->documents()->create([
                'path' => $path,
                'original_name' => $originalName,
                'type' => $validated['document_type'] ?? 'other',
                'is_confidential' => (bool) $request->input('is_confidential', false),
            ]);
        }

        return back()->with('success', 'Документы загружены.');
    }

    /**
     * Delete a document (seller or moderator).
     */
    public function destroy(Request $request, Listing $listing, ListingDocument $document): \Illuminate\Http\RedirectResponse
    {
        abort_if($document->listing_id !== $listing->id, 404);
        abort_unless($listing->isOwnedBy($request->user()) || $request->user()->isModerator(), 403);

        Storage::disk('documents')->delete($document->path);
        $document->delete();

        return back()->with('success', 'Документ удалён.');
    }
}
