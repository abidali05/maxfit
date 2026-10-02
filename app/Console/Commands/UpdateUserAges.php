<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateUserAges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-age';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update users age, Genz categories (Mother Fit vs Father Fit), and transition flags based on Date of Birth (DOB)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting automatic user age and Genz category synchronization...');

        $users = User::whereNotNull('dob')->where('dob', '!=', '')->get();
        $updatedCount = 0;
        $transitionedCount = 0;

        foreach ($users as $user) {
            try {
                $dob = Carbon::parse($user->dob);
                $currentAge = $dob->age;

                // Determine target genz category based on current age (< 14 is Mother Fit, >= 14 is Father Fit)
                $expectedGenz = $currentAge < 14 ? 'Mother Fit' : 'Father Fit';
                $updates = [];

                if ($user->genz !== $expectedGenz) {
                    $updates['genz'] = $expectedGenz;
                }

                $ageAtRegistration = $user->age_at_registration;
                if ($ageAtRegistration === null && $user->created_at) {
                    $ageAtRegistration = $dob->copy()->diffInYears(Carbon::parse($user->created_at));
                    $updates['age_at_registration'] = $ageAtRegistration;
                }

                if ($ageAtRegistration !== null) {
                    $hasCrossedToFatherFit = ((int) $ageAtRegistration) < 14 && $currentAge >= 14;

                    if ($hasCrossedToFatherFit && !$user->profile_updated_for_father_fit) {
                        $updates['assessment'] = false;
                        $updates['goal_setting'] = false;
                        $updates['profile_updated_for_father_fit'] = true;
                        $transitionedCount++;
                        $this->line("User ID {$user->id} ({$user->name}) transitioned to Father Fit (Age: {$currentAge}).");
                    }
                }

                if (!empty($updates)) {
                    User::where('id', $user->id)->update($updates);
                    $updatedCount++;
                }
            } catch (\Exception $e) {
                Log::error("Failed to update age for user ID {$user->id}: " . $e->getMessage());
                $this->error("Error processing user ID {$user->id}: " . $e->getMessage());
            }
        }

        $this->info("Completed. {$updatedCount} users updated, {$transitionedCount} users transitioned to Father Fit.");
        Log::info("users:update-age command finished. Total users checked: {$users->count()}, Updated: {$updatedCount}, Transitioned to Father Fit: {$transitionedCount}");

        return Command::SUCCESS;
    }
}
