<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

// Course routes (public access)
Route::get('/', [CourseController::class, 'index'])->name('courses.index');
Route::resource('courses', CourseController::class);

// Dashboard (protected by auth)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes (protected by auth)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/academic-details', [ProfileController::class, 'updateAcademicDetails'])->name('profile.updateAcademicDetails');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Goal routes
    Route::post('/goals', [\App\Http\Controllers\GoalController::class, 'store'])->name('goals.store');
    Route::delete('/goals/{goal}', [\App\Http\Controllers\GoalController::class, 'destroy'])->name('goals.destroy');

    // PDF Download routes
    Route::get('/courses/download/all', [CourseController::class, 'downloadAllPDF'])->name('courses.download.all');
    Route::get('/courses/download/{academic_year}/{semester}', [CourseController::class, 'downloadPDF'])->name('courses.download')->where('academic_year', '.+');
});

require __DIR__.'/auth.php';
