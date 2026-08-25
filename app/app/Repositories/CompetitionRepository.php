<?php

namespace App\Repositories;

use App\Models\Competition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\CompetitionRepositoryInterface;

class CompetitionRepository implements CompetitionRepositoryInterface
{
    protected $model;

    public function __construct(Competition $model)
    {
        $this->model = $model;
    }

    public function get_competitions()
    {
        return $this->model::with(['details', 'organisationType', 'organizationTypes', 'organisation', 'countryRelation'])->get();
    }

    public function get_branch_competitions()
    {
        return $this->model::where('user_id', Auth::guard('branch')->user()->id)
            ->with(['details', 'organisationType', 'organizationTypes', 'organisation', 'countryRelation'])->get();
    }

    public function store_competition(array $data)
    {
        $competitions = [];
        foreach ($data as $competitionData) {
            $competitions[] = $this->model::create($competitionData);
        }
        return $competitions;
    }

    public function get_competition($id)
    {
        return $this->model::find($id);
    }

    public function view_competition($id)
    {
        return $this->model::with('competitionUsers', 'competitionUsers.competitionResult')->find($id);
    }


    public function update_competition($id, array $data, $youtubeLinks = null)
    {
        $competition = $this->model::with('videos')->findOrFail($id);

        $competition->update($data);

        if ($youtubeLinks && count($youtubeLinks) > 0) {
            // Delete old videos
            foreach ($competition->videos as $video) {
                $video->delete();
            }

            // Save new YouTube links
            foreach ($youtubeLinks as $link) {
                if (!empty($link)) {
                    $competition->videos()->create([
                        'video_file' => $link
                    ]);
                }
            }
        }

        return $competition;
    }

    public function delete_competition($id)
    {
        return $this->model::where('id', $id)->delete();
    }
}
