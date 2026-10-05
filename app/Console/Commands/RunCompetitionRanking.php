<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessCompetitionResults;
use App\Models\Competition;
use Illuminate\Support\Facades\Log;

class RunCompetitionRanking extends Command
{
    protected $signature = 'competition:calculate-ranks {competition_id?}';
    protected $description = 'Calculate ranks and results for a single competition or all competitions if no ID is provided';

    public function handle()
    {
        $competitionId = $this->argument('competition_id');

        if ($competitionId) {
            $this->info("Calculating ranks for competition ID: {$competitionId}...");
            ProcessCompetitionResults::dispatchSync($competitionId);
            $this->info("Completed ranking calculation for competition ID: {$competitionId}");
            Log::info("competition:calculate-ranks completed for competition ID: {$competitionId}");
        } else {
            $this->info("Calculating ranks for all competitions...");
            $competitions = Competition::all();

            $count = 0;
            foreach ($competitions as $competition) {
                try {
                    ProcessCompetitionResults::dispatchSync($competition->id);
                    $count++;
                } catch (\Throwable $e) {
                    $this->error("Error calculating ranks for competition ID {$competition->id}: " . $e->getMessage());
                    Log::error("Failed to calculate ranks for competition ID {$competition->id}: " . $e->getMessage());
                }
            }

            $this->info("Completed rank & result calculations for {$count} competitions.");
            Log::info("competition:calculate-ranks completed for {$count} competitions.");
        }

        return Command::SUCCESS;
    }
}
