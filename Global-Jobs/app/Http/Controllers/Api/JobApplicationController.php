<?php

namespace App\Http\Controllers\Api;

use App\Events\JobApplicationSubmitted;
use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:global_jobs,id',
            'cover_letter' => 'required|string',
            'file' => 'required|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('uploads/resumes', 'public');
        }

        $application = JobApplication::create([
            'user_id' => auth()->id(),
            'job_id' => $request->job_id,
            'cover_letter' => $request->cover_letter,
            'file_path' => $filePath,
        ]);

        event(new JobApplicationSubmitted($application));

        return response()->json($application, 201);
    }
}
