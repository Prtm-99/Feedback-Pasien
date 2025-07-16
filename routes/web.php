<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\UnitLayananController;
use App\Http\Controllers\Admin\IdentitasController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\FeedbackController;

use App\Models\UnitLayanan;

// Public route
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Profile routes (with auth)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes (with auth)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/feedback/rekap', [FeedbackController::class, 'rekapBulan'])->name('feedback.rekap');

    // Unit
    Route::resource('unit', UnitLayananController::class);
    Route::get('unit/{id}/dokter', [UnitLayananController::class, 'showDokter'])->name('unit.dokter');

    // Dokter
    Route::get('dokter', [DokterController::class, 'index'])->name('dokter.index');
    Route::get('dokter/create', [DokterController::class, 'create'])->name('dokter.create');
    Route::post('dokter', [DokterController::class, 'store'])->name('dokter.store');
    
    Route::get('dokter/{id}/edit', [DokterController::class, 'edit'])->name('dokter.edit');

    // Topic & Question
    Route::resource('topic', TopicController::class);
    Route::resource('question', QuestionController::class);
});

    // Feedback routes (can be public or protected)
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('{identitas}/start', [FeedbackController::class, 'start'])->name('start');
        Route::post('{identitas}/submit/{topic}', [FeedbackController::class, 'store'])->name('store');
        Route::get('{identitas}/result', [FeedbackController::class, 'result'])->name('result');

        Route::get('form', [FeedbackController::class, 'form'])->name('form');
    });

    // Non middleware //
    Route::prefix(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dokter/{unit}', [DokterController::class, 'show'])->name('dokter.show');
    Route::put('dokter/{id}', [DokterController::class, 'update'])->name('dokter.update');
    Route::delete('dokter/{id}', [DokterController::class, 'destroy'])->name('dokter.destroy');
    });

    Route::prefix(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('identitas/create/{dokterId}', [IdentitasController::class, 'create'])->name('identitas.create');
    Route::resource('identitas', IdentitasController::class)->except(['create']);
    });
// Auth (breeze/jetstream/etc.)
require __DIR__.'/auth.php';
