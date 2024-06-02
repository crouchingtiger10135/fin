<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $path = $request->file('document')->store('documents');

        Document::create([
            'client_id' => $validated['client_id'],
            'document_name' => $request->file('document')->getClientOriginalName(),
            'document_path' => $path,
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully!');
    }

    public function destroy(Document $document)
    {
        Storage::delete($document->document_path);
        $document->delete();

        return redirect()->back()->with('success', 'Document removed successfully!');
    }
}
