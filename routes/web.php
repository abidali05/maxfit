<?php

use App\Http\Controllers\API\ChallengesController;
use App\Http\Controllers\AppVersionController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Branch\CompetitionController as BranchCompetitionController;
use App\Http\Controllers\BranchAuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\Coach\ManageCompetitionController;
use App\Http\Controllers\CoachAuthController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\competitionDetailController;
use App\Http\Controllers\CompetitionUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExerciseAssessmentController;
use App\Http\Controllers\ExerciseCategoryController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\MedicalAssessmentQuestionController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\OrganisationTypesController;
use App\Http\Controllers\PlanQuestionController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\RulesOfCountingController;
use App\Http\Controllers\SetsController;
use App\Http\Controllers\UserAssessmentController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect(route('dashboard'));
    });

    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('challenges/index', [ChallengesController::class,'index'])->name('challenge.index');
    Route::get('challenges/show/{id}', [ChallengesController::class,'show_web'])->name('challenge.show');


    Route::resource('venues', VenueController::class);
    Route::resource('users', UsersController::class);
    Route::patch('users/{id}/toggle-status', [UsersController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::resource('organisation-types', OrganisationTypesController::class);
    Route::resource('organisations', OrganisationController::class);
    Route::resource('medical-assessment-questions', MedicalAssessmentQuestionController::class);
    Route::resource('exercise-assessment', ExerciseAssessmentController::class);
    Route::resource('exercise-categories', ExerciseCategoryController::class);
    Route::resource('exercises', ExerciseController::class);
    Route::resource('plan-questions', PlanQuestionController::class);
    Route::get('competitions/available-users-count', [CompetitionController::class, 'availableUsersCount'])->name('competitions.available-users-count');
    Route::get('competitions/organizations-by-types', [CompetitionController::class, 'getOrganizationsByTypes'])->name('competitions.organizations-by-types');
    Route::get('competitions/countries-search', [CompetitionController::class, 'searchCountries'])->name('competitions.countries-search');
    Route::get('competitions/venues-by-city/{cityId}', [CompetitionController::class, 'getVenuesByCity'])->name('competitions.venues-by-city');
    Route::get('competitions/{competition}/accepted-users-by-city/{cityId}', [CompetitionController::class, 'getAcceptedUsersByCity'])->name('competitions.accepted-users-by-city');
    Route::resource('competitions', CompetitionController::class);
    Route::get('competition-details/{id}/users', [CompetitionUserController::class, 'index'])->name('competition-users.index');
    Route::get('/competition-users/{id}/edit', [CompetitionUserController::class, 'edit'])->name('competition-users.edit');
    Route::get('/competition-user-totals/{id}/edit', [CompetitionUserController::class, 'editRank'])->name('competition-user-totals.edit');
    Route::put('/competition-user-totals/{id}', [CompetitionUserController::class, 'updateRank'])->name('competition-user-totals.update');
    Route::post('/competitions/{id}/generate-results', [CompetitionUserController::class, 'generateResults'])->name('competitions.generate-results');
    Route::put('/competition-users/{id}', [CompetitionUserController::class, 'update'])->name('competition-users.update');
    Route::resource('competition-details', competitionDetailController::class);
    Route::post('/competitions/{id}/results', [CompetitionController::class, 'storeResults'])->name('competitions.results.store');
    Route::get('/get-organizations/{org_type_id}', [CompetitionController::class, 'getOrganizationsByType'])->name('get.organizations');
    Route::get('competitions-videos', [CompetitionController::class, 'competitionVideos'])->name('competitions.videos');
    Route::get('competitions-appeals', [CompetitionController::class, 'competitionAppeals'])->name('competitions.appeals');
    Route::delete('destroy-appeal/{id}', [CompetitionController::class, 'destroyAppeal'])->name('competitions.destroyAppeal');
    Route::put('update-appeal-status/{id}', [CompetitionController::class, 'updateAppealStatus'])->name('competitions.updateAppealStatus');
    Route::resource('results', ResultController::class);
    Route::get('/sets/exercises-by-criteria', [SetsController::class, 'getExercisesByCriteria'])->name('sets.exercises-by-criteria');
    Route::resource('sets', SetsController::class)->except(['show']);
    Route::resource('plans', PlansController::class);
    Route::resource('rulesof-counting', RulesOfCountingController::class);
    Route::resource('coaches', CoachController::class);
    Route::resource('branches', BranchController::class);
    Route::get('branches/{id}/users', [BranchController::class, 'users'])->name('branches.users');

    Route::get('exercise-assessment', [ExerciseAssessmentController::class, 'index'])->name('exercise-assessment.index');
    Route::get('exercise-assessment/set/{setId}', [ExerciseAssessmentController::class, 'setDetails'])->name('exercise-assessment.set-details');
    Route::get('exercise-assessment/exercise/{exerciseId}', [ExerciseAssessmentController::class, 'exerciseDetails'])->name('exercise-assessment.exercise-details');

    Route::get('user-assessments', [UserAssessmentController::class, 'index'])->name('user-assessments.index');

    Route::get('app-version', [AppVersionController::class, 'edit'])->name('app-version.edit');
    Route::put('app-version', [AppVersionController::class, 'update'])->name('app-version.update');
});


Route::get('coaches-login', [CoachAuthController::class, 'showLoginForm'])->name('coaches.login');
Route::post('coaches-login', [CoachAuthController::class, 'login'])->name('coaches.login.submit');

Route::get('branches-login', [BranchAuthController::class, 'showLoginForm'])->name('branches.login');
Route::post('branches-login', [BranchAuthController::class, 'login'])->name('branches.login.submit');

Route::middleware('auth:coach')->prefix('coach')->as('coach.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Coach\DashboardController::class, 'index'])->name('dashboard');
    Route::get('competition-details', [ManageCompetitionController::class, 'getCompetitionDetail'])->name('competition-details');
    Route::get('competition-details/{id}/users', [ManageCompetitionController::class, 'getCompetitionDetailUser'])
        ->name('competition-detail-users');
    Route::get('/competition-detail-users/{id}', [ManageCompetitionController::class, 'getCompetitionDetailUserUpdate'])
        ->name('competition-users-update');
    Route::put('/competition-result-update/{id}', [ManageCompetitionController::class, 'getCompetitionResultUpdate'])
        ->name('competition-result-update');
    Route::post('/logout', [CoachAuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware('auth:branch')->prefix('branch')->as('branch.')->group(function () {
    Route::get('/dashboard', function () {
        return view('branch.dashboard');
    })->name('dashboard');
    Route::post('/logout', [BranchAuthController::class, 'logout'])->name('logout');
    Route::get('coaches', [CoachController::class, 'index'])->name('getCoaches');
    Route::get('competetions', [BranchCompetitionController::class, 'getCompetitions'])
        ->name('getCompetitions');
    Route::get('create-competetions', [BranchCompetitionController::class, 'createCompetitions'])
        ->name('createCompetitions');
    Route::post('store-competetions', [BranchCompetitionController::class, 'storeCompetitions'])
        ->name('storeCompetitions');
    Route::get('/get-organizations/{org_type_id}', [BranchCompetitionController::class, 'getOrganizationsByType'])
        ->name('get.organizations');
    Route::delete('/delete-competetion/{id}', [BranchCompetitionController::class, 'deleteCompetition'])
        ->name('deleteCompetition');
    Route::get('/show-competetion/{id}', [BranchCompetitionController::class, 'showCompetition'])
        ->name('showCompetition');
    Route::get('/edit-competetion/{id}', [BranchCompetitionController::class, 'editCompetition'])
        ->name('editCompetition');
    Route::put('/update-competetion/{id}', [BranchCompetitionController::class, 'updateCompetition'])
        ->name('updateCompetition');

    Route::get('competition-details', [BranchCompetitionController::class, 'getCompetitionDetail'])->name('competition-details');
    Route::get('competition-details/{id}/users', [BranchCompetitionController::class, 'getCompetitionDetailUser'])
        ->name('getCompetitionDetailUser');
    Route::get('/competition-detail-users/{id}', [BranchCompetitionController::class, 'getCompetitionDetailUserUpdate'])
        ->name('getCompetitionDetailUserUpdate');
    Route::get('/competition-detail-delete/{id}', [BranchCompetitionController::class, 'getCompetitionDetailUserUpdate'])
        ->name('getCompetitionDetailDelete');
    Route::put('/competition-result-update/{id}', [BranchCompetitionController::class, 'getCompetitionResultUpdate'])
        ->name('competition-result-update');
    Route::get('/competition-users/{id}/edit', [BranchCompetitionController::class, 'editCompetitionResultUpdate'])
        ->name('competition-users.edit');
    Route::put('/update-competition-users/{id}', [BranchCompetitionController::class, 'updateCompetitionResultUpdate'])
        ->name('competition-users.update');
    Route::get('/competition-detail-users/{id}', [BranchCompetitionController::class, 'getCompetitionDetailUserUpdate'])
        ->name('competition-users-update');

    Route::get('competitions-videos', [BranchCompetitionController::class, 'competitionVideos'])->name('competitions.videos');
    Route::get('competitions-appeals', [BranchCompetitionController::class, 'competitionAppeals'])->name('competitions.appeals');
    Route::delete('destroy-appeal/{id}', [BranchCompetitionController::class, 'destroyAppeal'])->name('competitions.destroyAppeal');
    Route::put('update-appeal-status/{id}', [BranchCompetitionController::class, 'updateAppealStatus'])->name('competitions.updateAppealStatus');
        Route::post('/competitions/{id}/generate-results', [CompetitionUserController::class, 'generateResults'])->name('competitions.generate-results');

    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


Route::get('clear-cache', function () {
    Artisan::call('optimize:clear');
    return "Cache is cleared";
});

Route::get('/run-queue', function () {
    try {
        Log::info('Queue worker started via /run-queue route.');
        Artisan::call('queue:work --stop-when-empty');
        Log::info('Queue worker completed successfully.');
        return response('Queue worker executed.', 200);
    } catch (\Exception $e) {
        Log::error('Queue worker failed: ' . $e->getMessage());
        return response('Queue worker failed: ' . $e->getMessage(), 500);
    }
});

Route::get('/force-fix-link', function () {
    $publicStorage = public_path('storage');

    if (file_exists($publicStorage) && !is_link($publicStorage)) {
        File::deleteDirectory($publicStorage);
    }

    if (is_link($publicStorage)) {
        unlink($publicStorage);
    }

    Artisan::call('storage:link');

    return '✅ Symlink force-fixed';
});

require __DIR__ . '/auth.php';
