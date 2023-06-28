<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\UserController::class, 'logout']);

Route::post('/login', [App\Http\Controllers\LoginController::class, 'login'] )->name('login');
Route::post('/attempt-login', [App\Http\Controllers\LoginController::class, 'attemptLogin'] )->name('attemptLogin');

Route::post('/register', [App\Http\Controllers\RegisterController::class, 'register'] )->name('register');
Route::post('/attempt-register', [App\Http\Controllers\RegisterController::class, 'attemptRegister'] )->name('attemptRegister');

Route::apiResources(
    [
        'users' => App\Http\Controllers\UserController::class,
    ]
);


Route::group([
    'middleware' => [
        'auth:sanctum',
        //'role:super-admin'
    ]
], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

//    Route::middleware(['auth:sanctum', 'role_or_permission:admin'])->get('/getUser', [App\Http\Controllers\UserController::class, 'getUser']);
    Route::get('/getUser', [App\Http\Controllers\UserController::class, 'getUser']);
    Route::post('/change-role', [App\Http\Controllers\UserController::class, 'changeRole']);
    Route::get('/getAudio', [App\Http\Controllers\AudioController::class, 'getAudio']);
    Route::post('/change-audio-name', [App\Http\Controllers\AudioController::class, 'editAudioName']);
    Route::get('/showAudio', [App\Http\Controllers\AudioController::class, 'showAudio']);
    Route::post('/addNewUser', [App\Http\Controllers\StatisticController::class, 'addNewUser']);
    Route::post('/addNewDiagnosticHeart', [App\Http\Controllers\StatisticController::class, 'addNewDiagnosticHeart']);
    Route::post('/addNewDiagnosticLungs', [App\Http\Controllers\StatisticController::class, 'addNewDiagnosticLungs']);
    Route::get('/getLastDiagnostic', [App\Http\Controllers\StatisticController::class, 'getLastDiagnostic']);
    Route::get('/getSymptomsByType', [App\Http\Controllers\SymptomController::class, 'getSymptomsByType']);
    Route::get('/getDiseasesByType', [App\Http\Controllers\DiseaseController::class, 'getDiseasesByType']);

    Route::get('/get-diagnostic-user/{user_id}', [App\Http\Controllers\DiagnosticController::class, 'getDiagnosticsUserId']);

    Route::apiResources(
        [
            'disease' => App\Http\Controllers\DiseaseController::class,
            'symptom' => App\Http\Controllers\SymptomController::class,
            'diagnostic' => App\Http\Controllers\DiagnosticController::class,
            'audio' => App\Http\Controllers\AudioController::class,
            'file' => App\Http\Controllers\FileController::class,
            'patient' => App\Http\Controllers\UserController::class,
            'statistic' => App\Http\Controllers\StatisticController::class,
        ]
    );
});
