<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PlanController;
use App\Http\Controllers\API\VenueController;
use App\Http\Controllers\API\IdCardController;
use App\Http\Controllers\API\AppVersionController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ExercisesController;
use App\Http\Controllers\API\ChallengesController;
use App\Http\Controllers\API\PlanAnswerController;
use App\Http\Controllers\API\CompetitionController;
use App\Http\Controllers\API\GoogleVisionController;
use App\Http\Controllers\API\PersonalInfoController;
use App\Http\Controllers\API\PlanQuestionController;
use App\Http\Controllers\API\ForgetPasswordController;
use App\Http\Controllers\API\UserAssessmentExerciseController;
use App\Http\Controllers\API\MedicalAssessmentAnswerController;
use App\Http\Controllers\API\MedicalAssessmentQuestionController;
use App\Http\Controllers\API\UserAllCompetitionResultsController;

Route::post('vision-test', function (Illuminate\Http\Request $request) {
    $image = base64_encode(file_get_contents($request->file('image')));
    return $image; // Just testing
});

    Route::post('id-card/google-scan', [GoogleVisionController::class, 'scanIdCard']);

    Route::post('id-scanner', [GoogleVisionController::class, 'idScanner']);



// ==========================================================================public routes=================================================================
Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::get('get-branches', [AuthController::class, 'getBranches']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('resend-otp', [AuthController::class, 'resendOtp']);


    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgot-password']);
    Route::post('reset-password', [AuthController::class, 'reset-password']);
    Route::get('organisation-types', [PersonalInfoController::class, 'getOrganisationTypes']);
    Route::get('organisations/{id}', [PersonalInfoController::class, 'getOrganisations']);
    Route::get('countries', [PersonalInfoController::class, 'get_countries']);
    Route::get('provinces/{id}', [PersonalInfoController::class, 'get_provinces']);
    Route::get('cities/{id}', [PersonalInfoController::class, 'get_cities']);
    Route::get('venues', [VenueController::class, 'index']);
    Route::post('upload', [GoogleVisionController::class, 'upload']);

    // forget password
    Route::post('send-otp', [ForgetPasswordController::class, 'sendotp']);
    Route::post('check-otp', [ForgetPasswordController::class, 'checkOtp']);
    Route::post('forget-update-password', [ForgetPasswordController::class, 'forgetUpdatePassword']);

    // ID card scanning
    Route::post('id-card/scan', [IdCardController::class, 'scan']);
    Route::get('app-version', [AppVersionController::class, 'show']);
    // Route::get('/get-all-users/{id}', [ExercisesController::class, 'getAllUsers']);

});


// ==========================================================================protected routes==============================================================

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);


    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/update-info', [PersonalInfoController::class, 'updateInfo']);
    Route::post('/user-personal-info', [PersonalInfoController::class, 'profile']);
    Route::post('/update-profile', [ProfileController::class, 'updateProfile']);
    Route::post('/check-username', [ProfileController::class, 'checkUserName']);
    Route::post('update-password', [ProfileController::class, 'updatePassword']);
    Route::post('/physical-assessment', [PersonalInfoController::class, 'physical_assessment']);
    Route::post('/medical-assessment-answers', MedicalAssessmentAnswerController::class);
    Route::get('/medical-assessment-questions', MedicalAssessmentQuestionController::class);
    Route::get('/exercises', ExercisesController::class);
    Route::get('/assesment-exercises', [ExercisesController::class, 'assesmentExercises']);
    Route::get('/get-category', [ExercisesController::class, 'getCategory']);
    Route::get('/get-category-exercises/{id}', [ExercisesController::class, 'getCategoryExercises']);
    Route::post('/user-assessment-exercises', UserAssessmentExerciseController::class);
    Route::post('/get-assesment', [GoalController::class, 'getAssesments']);
    Route::post('/store-goal', [GoalController::class, 'store']);
    Route::post('/store-goal-with-date', [GoalController::class, 'storeGoalWithDate']);
    Route::get('/plan-questions', PlanQuestionController::class);
    Route::post('/plan-answers', PlanAnswerController::class);
    Route::get('/plans', PlanController::class);

    //usman daily assesments
    Route::post('/daily-assesment', [ProfileController::class, 'daily_assesments_index']);
    Route::get('/daily-assesment-index/{id}', [ProfileController::class, 'daily_assesments']);
    Route::get('/today-goals', [GoalController::class, 'getTodayGoals']);
    Route::get('/get-goals', [GoalController::class, 'getGoals']);


    Route::get('/get-competitions', [CompetitionController::class, 'getCompetition']);
    Route::get('/competition-detail/{id}', [CompetitionController::class, 'competitionDetail']);
    Route::get('/get-competitions-data/{status}', [CompetitionController::class, 'getCompetitionStatus']);
    Route::get('/get-competition-detail/{id}', [CompetitionController::class, 'getCompetitionDetail']);

    Route::post('/accept-or-reject/{id}', [CompetitionController::class, 'acceptOrReject']);

    Route::get('/get-results', [CompetitionController::class, 'getResult']);

    Route::post('/store-appeal', [CompetitionController::class, 'writeAppeal']);
    Route::get('/get-appeal/{id}', [CompetitionController::class, 'getAppeal']);

    Route::get('/view-result/{id}', [CompetitionController::class, 'viewResult']);
    Route::get('/rules-of-count', [CompetitionController::class, 'RulesOfCount']);
    Route::get('/rules-of-count-detail/{id}', [CompetitionController::class, 'RulesOfCountDetail']);

    // Google Cloud Vision ID card scanning


    //usman apis developed
    Route::get('/get-all-users', [ProfileController::class,'getAllUsers']);
        //challenges related api
    Route::post('/challenge-store', [ChallengesController::class,'store']);
    Route::get('/challenges-i-make', [ChallengesController::class,'challenges_i_make']);
    Route::get('/challenges-i-get', [ChallengesController::class,'challenges_i_get']);
    Route::get('/challenges-i-get', [ChallengesController::class,'challenges_i_get']);
    Route::post('/challenges-status/{id}', [ChallengesController::class,'updateStatus']);
    Route::get('/user-result-graph', [ChallengesController::class,'userResultGraph']);

    Route::get('/users/{user}/competitions/results', [UserAllCompetitionResultsController::class, 'index']);

});
