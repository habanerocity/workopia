<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApplicantSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_submit_application(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $job = Job::factory()->create();

        $payload = [
            'full_name' => 'Test Applicant',
            'contact_phone' => '555-555-5555',
            'contact_email' => 'applicant@example.com',
            'message' => 'Looking forward to this opportunity.',
            'location' => 'Remote',
            'resume' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ];

        $response = $this->actingAs($user)
            ->post(route('applicant.store', $job), $payload);

        $response->assertRedirect(route('jobs.show', $job));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('applicants', [
            'job_id' => $job->id,
            'user_id' => $user->id,
            'full_name' => 'Test Applicant',
            'contact_phone' => '555-555-5555',
            'contact_email' => 'applicant@example.com',
        ]);

        Storage::disk('public')->assertExists(Applicant::first()->resume_path);
    }

    public function test_resume_is_required(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $job = Job::factory()->create();

        $payload = [
            'full_name' => 'Test Applicant',
            'contact_email' => 'applicant@example.com',
        ];

        $response = $this->actingAs($user)
            ->post(route('applicant.store', $job), $payload);

        $response->assertSessionHasErrors('resume');
        $this->assertDatabaseCount('applicants', 0);
    }
}
