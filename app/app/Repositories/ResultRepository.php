<?php

namespace App\Repositories;

use App\Models\CompetitionResult;
use App\Models\CompetitionUser;
use App\Repositories\Contracts\ResultRepositoryInterface;
use Illuminate\Support\Facades\File;

class ResultRepository implements ResultRepositoryInterface
{
    protected $model;
    protected $resultModel;

    public function __construct(CompetitionUser $model, CompetitionResult $resultModel)
    {
        $this->model = $model;
        $this->resultModel = $resultModel;
    }

    public function get_results()
    {
        return $this->resultModel::with('competitionUser','competitionUser.competition')->get();
    }
}
