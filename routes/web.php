<?php

use App\Http\Controllers\Admin\AgmController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ShareholderController;
use App\Http\Controllers\Admin\VotingItemController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('shareholders', ShareholderController::class);
    Route::resource('agms', AgmController::class);
    Route::post('agms/{agm}/close', [AgmController::class, 'close'])->name('agms.close');
    Route::resource('agms.voting-items', VotingItemController::class)->shallow();
    Route::post('voting-items/{votingItem}/close', [\App\Http\Controllers\Admin\VotingItemController::class, 'close'])->name('voting-items.close');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/agm/{agm}', [ReportController::class, 'agmReport'])->name('reports.agm');
    Route::get('/reports/export/{agm}', [ReportController::class, 'exportReport'])->name('reports.export');
});

// Shareholder Routes
Route::middleware(['auth', 'shareholder'])->group(function () {
    Route::get('/agms/{agm}', [VotingController::class, 'showAgm'])->name('shareholder.agms.show');
    Route::get('/voting-items/{votingItem}', [VotingController::class, 'showVotingItem'])->name('shareholder.voting-items.show');
    Route::post('/voting/{votingItem}', [VotingController::class, 'vote'])->name('voting.vote');
});

require __DIR__.'/auth.php';
