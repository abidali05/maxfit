<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\CompetitionUser;
use App\Models\CompetitionDetail;
use Illuminate\Support\Facades\Log;
use App\Models\CompetitionUserTotal;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessCompetitionResults implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $competitionId;

    public function __construct($competitionId)
    {
        $this->competitionId = $competitionId;
    }

    public function handle()
    {
        Log::info("Starting rank & result calculation for competition_id: {$this->competitionId}");

        // Get all competition_detail IDs for this competition
        $detailIds = CompetitionDetail::where('competition_id', $this->competitionId)->pluck('id');

        // Get all competition users (joined to results)
        $users = CompetitionUser::with(['total', 'user.physical_assessment'])
            ->whereIn('competition_detail_id', $detailIds)
            ->withSum('results', 'score')
            ->get()
            ->sortByDesc('results_sum_score')
            ->values();

        Log::info($users);

        foreach ($users as $index => $user) {
            $score = $user->results_sum_score ?? 0;
            $rank = $index + 1;

            CompetitionUserTotal::updateOrCreate(
                ['competition_user_id' => $user->id],
                [
                    'total_score' => $score,
                    'rank' => $rank,
                ]
            );

            // Update top 10 users with Novice to Expert
            if ($rank <= 10 && $user->user && $user->user->physicalAssessment && $user->user->physicalAssessment->exercise_type === 'Novice') {
                $user->user->physicalAssessment->update(['exercise_type' => 'Expert']);
                Log::info("Updated user to Expert", ['user_id' => $user->user->id]);
            }

            Log::info("Updated user total", [
                'competition_user_id' => $user->id,
                'total_score' => $score,
                'rank' => $rank,
            ]);
        }

        Log::info("Completed rank & result calculation for competition_id: {$this->competitionId}");
    }
}
