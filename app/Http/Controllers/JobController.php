<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\Job;


class JobController extends Controller
{
    use AuthorizesRequests;

    // @desc Show all job listings
    // @route Get /jobs
    public function index(): View
    {
        $jobs = Job::all();

        return view('jobs.index')->with('jobs', $jobs);
    }

    // @desc Show create job listings form
    // @route Get /jobs/create
    public function create(): View
    {
        return view('jobs.create');
    }

    // @desc Save job to databse
    // @route POST /login
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|string',
            'salary' => 'required|integer',
            'tags' => 'nullable|string',
            'job_type' => 'required|string',
            'remote' => 'required|boolean',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'nullable|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            'company_name' => 'required|string',
            'company_description' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'company_website' => 'nullable|url'
        ]);

        // user ID
        $validatedData['user_id'] = auth()->user()->id;

        // Check for image
        if($request->hasFile('company_logo')){
            //Store the file and get path
            $path = $request->file('company_logo')->store('logos', 'public');

            // Add path to validated data
            $validatedData['company_logo'] = $path;
        }

        // Subit to db
        Job::create($validatedData);

        return redirect()->route('jobs.index')->with('success', 'Job listing created successfully.');
    }

    // @desc Display a single job listing
    // @route Get /jobs/show/{$id}
    public function show(Job $job): View
    {
        return view('jobs.show')->with('job', $job);
    }

    // @desc Show edit job form
    // @route Get /jobs/{$id}/edit
    public function edit(Job $job): View
    {
        // Check if user is authorized
        $this->authorize('update', $job);

        return view('jobs.edit')->with('job', $job);
    }

    // @desc Update job listing
    // @route Put /jobs/{$id}
    public function update(Request $request, Job $job): string
    {
        // Check if user is authorized
        $this->authorize('update', $job);

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|string',
            'salary' => 'required|integer',
            'tags' => 'nullable|string',
            'job_type' => 'required|string',
            'remote' => 'required|boolean',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'nullable|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            'company_name' => 'required|string',
            'company_description' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'company_website' => 'nullable|url'
        ]);

        // Check for image
        if($request->hasFile('company_logo')){
            // Delete old logo
            Storage::delete('public/logos/' . basename($job->company_logo));

            //Store the file and get path
            $path = $request->file('company_logo')->store('logos', 'public');

            // Add path to validated data
            $validatedData['company_logo'] = $path;
        }

        // Submit to db
        $job->update($validatedData);

        return redirect()->route('jobs.index')->with('success', 'Job listing updated successfully.');;
    }

    // @desc Delete a job listing
    // @route DELETE /jobs/{$id}
    public function destroy(Job $job): RedirectResponse
    {
        // Check if user is authorized
        $this->authorize('delete', $job);

        // If logo, then delete it
        if($job->company_logo){
            Storage::delete('public/logos/' . $job->company_logo);
        }

        $job->delete();

        return redirect()->route('jobs.index')->with('success', 'Job listing deleted successfully.');;
    }
}
