<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UnitLayananController;
use App\Http\Controllers\Admin\IdentitasController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\FeedbackController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Public routes for identity and feedback
Route::get('unit/{unit}/identitas/create', [IdentitasController::class, 'create'])
    ->name('identitas.create');
Route::post('identitas', [IdentitasController::class, 'store'])
    ->name('identitas.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ADMIN ROUTES
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Feedback admin
    Route::get('feedback/rekap', [FeedbackController::class, 'rekapBulan'])->name('feedback.rekap');
    Route::get('feedback/export-excel', [FeedbackController::class, 'exportExcel'])->name('feedback.export.excel');

        // Route Feedback index 
    Route::get('feedback', [FeedbackController::class, 'index'])->name('feedback.index');

    // Unit layanan
    Route::resource('unit', UnitLayananController::class);
    Route::get('unit/{id}/dokter', [UnitLayananController::class, 'showDokter'])->name('unit.dokter');

    // Identitas
    Route::resource('identitas', IdentitasController::class)->except(['create']);

    // Topic dan Question
    Route::resource('topic', TopicController::class);
    Route::resource('question', QuestionController::class);
});

// FEEDBACK ROUTES
Route::prefix('feedback')->name('feedback.')->group(function () {
    Route::get('{identitas}/start', [FeedbackController::class, 'start'])->name('start');
    Route::post('{identitas}/submit/{topic}', [FeedbackController::class, 'store'])->name('store');
    Route::get('{identitas}/result', [FeedbackController::class, 'result'])->name('result');
    Route::get('form', [FeedbackController::class, 'form'])->name('form');
});

require __DIR__.'/auth.php';