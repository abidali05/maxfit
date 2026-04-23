<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CalculateCompetitionRanks;
use App\Jobs\ProcessCompetitionResults;

class RunCompetitionRanking extends Command
{
    protected $signature = 'competition:calculate-ranks {competition_id}';
    protected $description = 'Calculate ranks and results for a competition';

    public function handle()
    {
        $competitionId = $this->argument('competition_id');

        ProcessCompetitionResults::dispatch($competitionId);

        $this->info("Competition ranking job dispatched for competition ID: {$competitionId}");
    }
}
