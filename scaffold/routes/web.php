<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SponsorshipController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProjectController::class, 'index'])->name('home');
Route::get('/projects', [ProjectController::class, 'search'])->name('projects.search');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::post('/reports', [ReportController::class, 'store'])
    ->middleware('throttle:30,1')
    ->name('reports.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('dashboard/projects')->name('dashboard.projects.')->group(function () {
        Route::get('/', [DashboardController::class, 'projects'])->name('index');
        Route::get('/create', [DashboardController::class, 'createProject'])->name('create');
        Route::post('/', [ProjectController::class, 'store'])->name('store');
        Route::get('/{project}/edit', [DashboardController::class, 'editProject'])->name('edit');
        Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
        Route::post('/{project}/media', [ProjectController::class, 'storeMedia'])->name('media.store');
        Route::post('/{project}/tiers', [ProjectController::class, 'storeTier'])->name('tiers.store');
        Route::post('/{project}/updates', [ProjectController::class, 'storeUpdate'])->name('updates.store');
    });

    Route::post('/projects/{project}/sponsor', [SponsorshipController::class, 'store'])->name('projects.sponsor');
    Route::get('/sponsorships/{sponsorship}', [SponsorshipController::class, 'show'])->name('sponsorships.show');
    Route::post('/sponsorships/{sponsorship}/messages', [MessageController::class, 'store'])
        ->middleware('throttle:30,1')
        ->name('sponsorships.messages.store');
    Route::post('/sponsorships/{sponsorship}/status', [SponsorshipController::class, 'updateStatus'])
        ->name('sponsorships.status');

    Route::prefix('admin')->middleware('can:admin-access')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/reviews', [AdminController::class, 'reviews'])->name('admin.reviews');
        Route::post('/reviews/{project}', [AdminController::class, 'reviewAction'])->name('admin.reviews.action');
        Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
        Route::post('/reports/{report}/resolve', [AdminController::class, 'resolveReport'])->name('admin.reports.resolve');
        Route::get('/master', [AdminController::class, 'master'])->name('admin.master');
        Route::post('/master/categories', [AdminController::class, 'storeCategory'])->name('admin.master.categories.store');
        Route::post('/master/tags', [AdminController::class, 'storeTag'])->name('admin.master.tags.store');
        Route::get('/kpi', [AdminController::class, 'index'])->name('admin.kpi');
    });
});

require __DIR__.'/auth.php';
