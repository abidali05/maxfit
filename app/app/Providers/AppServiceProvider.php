<?php

namespace App\Providers;

use App\Repositories\PlanRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\API\AuthRepository;
use App\Repositories\ExerciseRepository;
use App\Repositories\PlanAnswerRepository;
use App\Repositories\PlanQuestionRepository;
use App\Repositories\API\OrganisationRepository;
use App\Repositories\ExerciseCategoryRepository;
use App\Repositories\OrganisationTypesRepository;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\MedicalAssessmentQuestionRepository;
use App\Repositories\API\MedicalAssessmentAnswerRepository;
use App\Repositories\CompetitionRepository;
use App\Repositories\Contracts\API\AuthRepositoryInterface;
use App\Repositories\Contracts\ExerciseRepositoryInterface;
use App\Repositories\Contracts\PlanAnswerRepositoryInterface;
use App\Repositories\Contracts\PlanQuestionRepositoryInterface;
use App\Repositories\Contracts\API\OrganisationRepositoryInterface;
use App\Repositories\Contracts\ExerciseCategoryRepositoryInterface;
use App\Repositories\Contracts\API\MedicalAssessmentAnswerInterface;
use App\Repositories\Contracts\CompetitionRepositoryInterface;
use App\Repositories\Contracts\OrganisationTypesRepositoryInterface;
use App\Repositories\OrganisationRepository as OrganisationRepositoryy;
use App\Repositories\Contracts\MedicalAssessmentQuestionRepositoryInterface;
use App\Repositories\Contracts\OrganisationRepositoryInterface as OrganisationRepositoryInterfaces;
use App\Repositories\Contracts\ResultRepositoryInterface;
use App\Repositories\ResultRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AuthRepositoryInterface::class,AuthRepository::class);
        $this->app->bind(OrganisationRepositoryInterface::class,OrganisationRepository::class);
        $this->app->bind(OrganisationTypesRepositoryInterface::class,OrganisationTypesRepository::class);
        $this->app->bind(OrganisationRepositoryInterfaces::class,OrganisationRepositoryy::class);
        $this->app->bind(MedicalAssessmentQuestionRepositoryInterface::class,MedicalAssessmentQuestionRepository::class);
        $this->app->bind(PlanQuestionRepositoryInterface::class,PlanQuestionRepository::class);
        $this->app->bind(MedicalAssessmentAnswerInterface::class,MedicalAssessmentAnswerRepository::class);
        $this->app->bind(PlanAnswerRepositoryInterface::class,PlanAnswerRepository::class);
        $this->app->bind(ExerciseCategoryRepositoryInterface::class,ExerciseCategoryRepository::class);
        $this->app->bind(ExerciseRepositoryInterface::class,ExerciseRepository::class);
        $this->app->bind(CompetitionRepositoryInterface::class,CompetitionRepository::class);
        $this->app->bind(ResultRepositoryInterface::class,ResultRepository::class);
        $this->app->bind(PlanRepositoryInterface::class,PlanRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
