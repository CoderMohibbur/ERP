<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDocument;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmployeeDocumentController extends Controller
{
    /**
     * Display a listing of employee documents.
     */
    public function index(): View
    {
        $documents = EmployeeDocument::with(['employee', 'uploader', 'verifier'])
            ->latest()
            ->paginate(20);

        return view('employee-documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create(): View
    {
        $employees = Employee::pluck('name', 'id');

        return view('employee-documents.create', compact('employees'));
    }

    /**
     * Store a newly uploaded employee document.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type'        => 'required|string|max:50',
            'title'       => 'nullable|string|max:255',
            'visibility'  => 'required|in:admin,employee,private',
            'expires_at'  => 'nullable|date',
            'notes'       => 'nullable|string',
            'file'        => 'required|file|max:10240', // Max 10MB
        ]);

        // Handle File Upload
        $file = $request->file('file');
        $path = $file->store('employee-documents');
        $hash = hash_file('sha256', $file->getRealPath());

        // Save metadata
        $document = EmployeeDocument::create([
            'employee_id'  => $validated['employee_id'],
            'type'         => $validated['type'],
            'title'        => $validated['title'],
            'file_path'    => $path,
            'file_type'    => $file->getClientOriginalExtension(),
            'file_size'    => round($file->getSize() / 1024), // in KB
            'file_hash'    => $hash,
            'visibility'   => $validated['visibility'],
            'expires_at'   => $validated['expires_at'],
            'notes'        => $validated['notes'],
            'uploaded_by'  => auth()->id(),
        ]);

        return redirect()->route('employee-documents.index')
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * Show the form for editing a document.
     */
    public function edit(EmployeeDocument $employeeDocument): View
    {
        $employees = Employee::pluck('name', 'id');

        return view('employee-documents.edit', compact('employeeDocument', 'employees'));
    }

    /**
     * Update an existing document's metadata.
     */
    public function update(Request $request, EmployeeDocument $employeeDocument): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type'        => 'required|string|max:50',
            'title'       => 'nullable|string|max:255',
            'visibility'  => 'required|in:admin,employee,private',
            'expires_at'  => 'nullable|date',
            'notes'       => 'nullable|string',
        ]);

        $employeeDocument->update(array_merge($validated, [
            'verified_by' => auth()->id(),
            'is_verified' => true, // assuming update means verification
        ]));

        return redirect()->route('employee-documents.index')
            ->with('success', 'Document updated & verified.');
    }

    /**
     * Soft delete the document and file from storage.
     */
    public function destroy(EmployeeDocument $employeeDocument): RedirectResponse
    {
        if (Storage::exists($employeeDocument->file_path)) {
            Storage::delete($employeeDocument->file_path);
        }

        $employeeDocument->delete();

        return redirect()->route('employee-documents.index')
            ->with('success', 'Document deleted.');
    }
}
