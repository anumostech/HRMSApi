<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class DocumentApiController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $type = $request->get('type');
        $folder = $request->get('folder');
        $perPage = $request->get('per_page', 15);

        $query = Document::with(['party', 'shareWith'])->latest();

        if ($type) {
            $query->where('type', $type);
        }

        if ($folder) {
            $query->where('folder', $folder);
        }

        $documents = $query->paginate($perPage);

        return $this->success($documents);
    }

    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB limit
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('temp', 'public');

            return $this->success([
                'path' => $path,
                'filename' => $file->getClientOriginalName()
            ], 'File uploaded to temporary storage');
        }

        return $this->error('No file uploaded', 400);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:organization,agreement,hr,others',
            'description' => 'nullable|string',
            'file_path' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'folder' => 'required|string',
            'party_id' => 'nullable|exists:parties,id',
            'share_with' => 'nullable|exists:users,id',
            'expiry_date' => 'nullable|date_format:d-m-Y|after:today'
        ]);

        $folder = $request->folder;
        $file = $request->file('file_path');
        $tempPath = $file->store('temp', 'public');

        // Ensure temporary file exists
        if (!Storage::disk('public')->exists($tempPath)) {
            return $this->error('Temporary file not found', 404);
        }

        // Create folder if not exists
        if (!Storage::disk('public')->exists('documents/' . $folder)) {
            Storage::disk('public')->makeDirectory('documents/' . $folder);
        }

        $filename = basename($tempPath);
        $newPath = 'documents/' . $folder . '/' . $filename;

        // Move file from temp to final destination
        Storage::disk('public')->move($tempPath, $newPath);

        $document = Document::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'file_path' => $newPath,
            'folder' => $folder,
            'party_id' => $request->party_id,
            'share_with' => $request->share_with,
            'expiry_date' => $request->expiry_date
        ]);

        return $this->success($document, 'Document created successfully', 201);
    }

    public function show(Document $document): JsonResponse
    {
        return $this->success($document->load(['party', 'shareWith']));
    }

    public function update(Request $request, Document $document): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:organization,agreement,hr,others',
            'description' => 'nullable|string',
            'party_id' => 'nullable|exists:parties,id',
            'share_with' => 'nullable|exists:users,id',
            'expiry_date' => 'nullable|date_format:d-m-Y'
        ]);

        $document->update($request->only([
            'name',
            'type',
            'description',
            'party_id',
            'share_with',
            'expiry_date'
        ]));

        return $this->success($document, 'Document context updated successfully');
    }

    public function destroy(Document $document): JsonResponse
    {
        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();
        return $this->success(null, 'Document deleted successfully');
    }

    public function getFolders(): JsonResponse
    {
        $folders = Document::select('folder')
            ->distinct()
            ->pluck('folder');

        return $this->success($folders);
    }

    public function getShareableUsers(): JsonResponse
    {
        $users = \App\Models\User::whereIn('type', ['admin', 'manager'])
            ->with('employee')
            ->get(['id', 'username', 'type']);

        $formatted = $users->map(function ($user) {
            $name = $user->employee
                ? $user->employee->first_name . ' ' . $user->employee->last_name
                : $user->username;

            return [
                'id' => $user->id,
                'name' => trim($name),
                'type' => $user->type
            ];
        });

        return $this->success($formatted);
    }
}
