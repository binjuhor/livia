<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Project;
use App\Http\Controllers\SyncJiraController;
use App\Http\Controllers\CreateInvoiceController;
use App\Models\Issue;

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
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::post('/mailgun', function(\Illuminate\Http\Request $request) {
    info($request->toArray());
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', [
            'projects' => Project::all()
        ]);
    })->name('dashboard');

    Route::get('/projects/{id}', function ($id) {
        return Inertia::render('Project', [
            'project' => Project::query()->find($id)->load('invoices'),
            'issues' => Issue::query()
                ->where('project_id', $id)
                ->paginate(10)
        ]);
    })->name('projects.view');

    Route::post('/projects/syncJira', SyncJiraController::class)
        ->name('projects.syncJira');

    Route::post('/projects/{id}/createInvoice', CreateInvoiceController::class)
        ->name('projects.createInvoice');
});