<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Mail\ProfileReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CleanIncompleteProfiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-incomplete-profiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminds users with incomplete profiles after 24 hours, and deletes them if still incomplete after another 24 hours.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting incomplete profiles check...');
        Log::info('CleanIncompleteProfiles: Starting incomplete profiles check.');

        // 1. Send reminders (24 hours after registration)
        $this->info('Checking for users to notify (registered > 24 hours ago, not notified)...');
        $usersToNotify = User::where('role', 'user')
            ->where('created_at', '<=', now()->subHours(24))
            ->whereNull('profile_reminder_sent_at')
            ->get();

        Log::info("CleanIncompleteProfiles: Found " . count($usersToNotify) . " registered users with role 'user' > 24 hours ago who have not been notified yet.");

        $notifiedCount = 0;
        foreach ($usersToNotify as $user) {
            $missing = $this->getMissingFields($user);
            if (!empty($missing)) {
                $this->line("Notifying user ID {$user->id} ({$user->email}) of missing information.");
                Log::info("CleanIncompleteProfiles: Notifying user ID {$user->id} ({$user->email}) of missing information: " . implode(', ', $missing));
                try {
                    Mail::to($user->email)->send(new ProfileReminderMail($user, $missing));
                    $user->update(['profile_reminder_sent_at' => now()]);
                    $notifiedCount++;
                } catch (\Throwable $e) {
                    $this->error("Failed to notify user ID {$user->id}: " . $e->getMessage());
                    Log::error("CleanIncompleteProfiles: Failed to notify user ID {$user->id}: " . $e->getMessage());
                }
            }
        }
        $this->info("Sent {$notifiedCount} reminders.");
        Log::info("CleanIncompleteProfiles: Sent {$notifiedCount} reminders.");

        // 2. Delete incomplete accounts (24 hours after notification)
        $this->info('Checking for accounts to clean up (notified > 24 hours ago, still incomplete)...');
        $usersToDelete = User::where('role', 'user')
            ->where('profile_reminder_sent_at', '<=', now()->subHours(24))
            ->get();

        Log::info("CleanIncompleteProfiles: Found " . count($usersToDelete) . " users with role 'user' notified > 24 hours ago.");

        $deletedCount = 0;
        foreach ($usersToDelete as $user) {
            $missing = $this->getMissingFields($user);
            if (!empty($missing)) {
                $this->warn("Deleting incomplete user account ID {$user->id} ({$user->email}). Missing: " . implode(', ', $missing));
                Log::warning("CleanIncompleteProfiles: Deleting incomplete user ID {$user->id} ({$user->email}). Missing fields: " . implode(', ', $missing));

                try {
                    DB::transaction(function () use ($user) {
                        // Delete all user related information from tables
                        DB::table('physical_assessments')->where('user_id', $user->id)->delete();
                        DB::table('user_exercise_assessment')->where('user_id', $user->id)->delete();
                        DB::table('goals')->where('user_id', $user->id)->delete();
                        DB::table('plan_answers')->where('user_id', $user->id)->delete();
                        DB::table('competition_appeals')->where('user_id', $user->id)->delete();
                        DB::table('daily_assessments')->where('user_id', $user->id)->delete();
                        DB::table('medical_assessment_answers')->where('user_id', $user->id)->delete();

                        // Delete Sanctum tokens
                        DB::table('personal_access_tokens')
                            ->where('tokenable_id', $user->id)
                            ->where('tokenable_type', User::class)
                            ->delete();

                        // Finally delete the user
                        $user->delete();
                    });

                    $deletedCount++;
                } catch (\Throwable $e) {
                    $this->error("Failed to delete user ID {$user->id}: " . $e->getMessage());
                    Log::error("CleanIncompleteProfiles: Failed to delete user ID {$user->id}: " . $e->getMessage());
                }
            }
        }
        $this->info("Deleted {$deletedCount} incomplete accounts.");
        Log::info("CleanIncompleteProfiles: Deleted {$deletedCount} incomplete accounts.");
        Log::info('CleanIncompleteProfiles: Incomplete profiles check completed.');
    }

    /**
     * Check if user is missing required profile or assessment information.
     */
    private function getMissingFields(User $user): array
    {
        $missing = [];

        // Required fields on users table
        $userFields = [
            'name' => 'Full Name',
            'cnic' => 'CNIC',
            'dob' => 'Date of Birth',
            'organisation_type' => 'Organisation Type',
            'organisation_id' => 'Organisation ID',
            'class' => 'Class',
            'guardian_name' => 'Guardian Name',
            'address' => 'Address',
            'guardian_cnic' => 'Guardian CNIC'
        ];

        foreach ($userFields as $field => $label) {
            if ($user->$field === null || $user->$field === '') {
                $missing[] = $label;
            }
        }

        // Check physical assessment
        $assessment = $user->latestPhysicalAssessment()->first();
        if (!$assessment) {
            $missing[] = 'Physical Assessment (Height, Weight, BMI, Gender, Exercise Type)';
        } else {
            $assessmentFields = [
                'height_cm' => 'Height',
                'weight_kg' => 'Weight',
                'bmi' => 'BMI',
                'gender' => 'Gender',
                'exercise_type' => 'Exercise Type'
            ];
            foreach ($assessmentFields as $field => $label) {
                if ($assessment->$field === null || $assessment->$field === '') {
                    $missing[] = "Physical Assessment - $label";
                }
            }
        }

        return $missing;
    }
}
