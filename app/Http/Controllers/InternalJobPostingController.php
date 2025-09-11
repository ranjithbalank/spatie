<?php

namespace App\Http\Controllers;


use App\Models\User;

use Illuminate\Http\Request;
use App\Models\FinalJobStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Imports\FinalStatusImport;
use App\Jobs\SendInternalJobEmail;
use Illuminate\Support\Facades\Bus;
use App\Exports\JobApplicantsExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\InternalJobApplications;
use App\Notifications\NewJobApplication;
use App\Models\InternalJobPostings; // ✅ correct import

class InternalJobPostingController extends Controller // ✅ correct class name
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $user = Auth::user();
    // Check each job's end date and set a new 'status' attribute
     $jobs = InternalJobPostings::orderBy('passing_date', 'desc')->get();

    $today = now()->format('Y-m-d');
    foreach ($jobs as $job) {
            // Get today's date and the job's end date as Carbon objects
            $today = now();
            $endDate = \Carbon\Carbon::parse($job->end_date);

            // Check if the end date is in the past
            // The `isPast()` method returns true if the date is before the current moment.
            // Check if the end date is in the past.
            // If the HR has specifically marked the job as inactive, it should be closed regardless of the date.
            if ($job->status === 'inactive') {
                $job->status = 'Closed';
            } elseif ($endDate->isBefore(today())) {
                $job->status = 'Registration closed';
            } else {
                $job->status = 'active';
            }
        }


    // All jobs for dropdown
    // $jobs = InternalJobPostings::orderBy('passing_date', 'desc')->get();

    // Jobs applied by the current user
    $applications = InternalJobApplications::where('employee_id', $user->id)
        ->pluck('job_id')
        ->toArray();

    // Final status results
    $results = FinalJobStatus::with('job', 'user')->get();

    // Base applicants query
    $applicantsQuery = InternalJobApplications::with(['user', 'job'])->latest();

    if (!$user->hasAnyRole(['hr', 'admin'])) {
        $applicantsQuery->where('employee_id', $user->id);
    }

    // Apply filter if job_id selected
    if ($request->filled('job_id')) {
        $applicantsQuery->where('job_id', $request->job_id);
    }

    $applicants = $applicantsQuery->get();

    return view('internal_jobs.index', compact(
        'jobs',
        'applications',
        'user',
        'applicants',
        'results'
    ));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jobId = InternalJobPostings::max('id') + 1;

        return view('internal_jobs.create',compact('jobId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'slot_available'  => 'required|integer|min:1',
            'qualification' => 'required|string|max:255',
            'work_experience' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'passing_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:passing_date',
            'status' => 'required|string|in:active,inactive',
        ]);

        $job=InternalJobPostings::create([
            'job_title' => $request->job_title,
            'job_description' => $request->job_description,
            'qualifications' => $request->qualification,
            'work_experience' => $request->work_experience,
            'slot_available' => $request->slot_available,
            'unit' => $request->unit,
            'division' => $request->division,
            'passing_date' => $request->passing_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);
        
         
        // Dispatch a single job to send a bulk email to all relevant users
        // This is more efficient than dispatching a job for each user.
        SendInternalJobEmail::dispatch($job);
        
        return redirect()->route('internal-jobs.index')
            ->with('success', 'Job posting created successfully!');

    }

    public function show(string $id)
    {
        $jobs = InternalJobPostings::find($id);
        $applications = InternalJobApplications::where('employee_id', Auth::id())->pluck('job_id')->toArray();
        return view('internal_jobs.show',compact('jobs', 'applications'));
    }

    public function edit(string $id)
    {
        $jobs = InternalJobPostings::find($id);
        return view('internal_jobs.edit',compact('jobs'));
        //
    }

   public function update(Request $request, $id)
    {
        // Validate data
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'slot_available'  => 'required|integer|min:1',
            'qualification' => 'required|string|max:255',
            'work_experience' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'passing_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:passing_date',
            'status' => 'required|string|in:active,inactive,closed',
        ]);

        // Find the job by ID
        $job = InternalJobpostings::findOrFail($id);

        // Update the job
        $job->update($validated);

        // Redirect or return response
        return redirect()->route('internal-jobs.index')
                        ->with('success', 'Job updated successfully!');
    }


   public function destroy(InternalJobpostings $internal_job)
    {
        $internal_job->delete();

        return redirect()->route('internal-jobs.index')
                        ->with('success', 'Job deleted successfully!');
    }

    public function apply(Request $request, $job)
    {
        // Check if user already applied BEFORE validating the file
        $existing = InternalJobApplications::where('employee_id', Auth::id())
            ->where('job_id', $job)
            ->first();

        if ($existing) {
            return redirect()->route('internal-jobs.index')
                ->with('error', 'You have already applied for this position!');
        }

        // Validate input
        $request->validate([
            'emp_qualifications' => 'required|string|max:255',
            'emp_experience' => 'required|string|max:255',
            'emp_file' => 'required|file|mimes:pdf',
            'is_interested' => 'required',
        ]);

        // Check file size warning
        $file = $request->file('emp_file');
        $sizeInMB = $file->getSize() / 1024 / 1024;

        if ($sizeInMB > 2) {
            session()->flash('warning', 'Your uploaded file is large (over 2MB). Uploading may take longer.');
        }

        // Store file
        $path = $file->store('resumes', 'public');

        InternalJobApplications::create([
            'employee_id' => Auth::id(),
            'job_id' => $job,
            'emp_qualifications' => $request->emp_qualifications,
            'emp_experience' => $request->emp_experience,
            'resume_path' => $path,
        ]);

        return redirect()->route('internal-jobs.index')
            ->with('success', 'You have successfully applied for the position! HR will reach out soon!');
    }

    public function exportApplicantsPdf()
    {
        $applications = FinalJobStatus::with('job', 'user')->get();
        $pdf = Pdf::loadView('internal_jobs.export', compact('applications'))->setPaper('A4', 'landscape');
        $filename = $job->job_title . '_IJP.pdf';
        return $pdf->download('filename');
    }


    public function exportApplicants(Request $request)
    {
        $jobId = $request->query('job_id');

        // Fetch the job details
        $job = InternalJobPostings::findOrFail($jobId);

        // Create a nice filename like "Software_Engineer_IJP.xlsx"
        $filename = str_replace(' ', '_', $job->job_title) . '_IJP.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new JobApplicantsExport($jobId),
            $filename
        );
}



    public function uploadFinalStatus(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new FinalStatusImport, $request->file('excel_file'));
            return back()->with('success', 'Final Job Status imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }


}
