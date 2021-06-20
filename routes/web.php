<?php

use App\Http\Controllers\ImportJiraIssuesForProject;
use App\Http\Controllers\ViewProjectController;
use App\Http\Controllers\ImportJiraProjectController;
use App\Http\Controllers\ProjectController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    /**
     * Route /projects/*
     */
    Route::prefix('projects')->group(function () {
        Route::get('/', ProjectController::class)
             ->name('projects');

        Route::post('/import/jira', ImportJiraProjectController::class)
             ->name('projects.import.jira');

        Route::get('/{projectId}', ViewProjectController::class)
             ->name('projects.view');
    });

    /**
     * Route /issues/*
     */
    Route::prefix('issues')->group(function () {
        Route::post('/import/jira', ImportJiraIssuesForProject::class)
             ->name('issues.import.jira');
    });
});

