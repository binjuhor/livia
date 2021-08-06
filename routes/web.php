<?php

use App\Http\Controllers\XeroController;
use App\Http\Controllers\CreateInvoiceController;
use App\Http\Controllers\ViewProjectController;
use App\Http\Controllers\SyncJiraController;
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
    Route::get('/dashboard', ProjectController::class)->name('dashboard');

    /**
     * Route /projects/*
     */
    Route::prefix('projects')->group(function () {
        Route::get('/', ProjectController::class)
             ->name('projects');

        Route::post('/sync/jira', SyncJiraController::class)
             ->name('projects.sync.jira');

        Route::get('/{projectId}', ViewProjectController::class)
             ->name('projects.view');

        Route::post('/{projectId}/createInvoice', CreateInvoiceController::class)
            ->name('projects.createInvoice');
    });
});

/*
 * We name this route xero.auth.success as by default the config looks for a route with this name to redirect back to
 * after authentication has succeeded. The name of this route can be changed in the config file.
 */
Route::get('/manage/xero', XeroController::class)
    ->name('xero.auth.success');

