<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamResultController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ScheduleEventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [LoginController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('me', [LoginController::class, 'me']);
    Route::post('logout', [LoginController::class, 'logout']);
});

Route::middleware(['auth:api', 'role:Client,Manager,Admin'])->group(function () {
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/{id}', [ExamController::class, 'show'])->name('exams.show');
    Route::get('/exam-results', [ExamResultController::class, 'index'])->name('exam-results.index');
    Route::get('/exam-results/{id}', [ExamResultController::class, 'show'])->name('exam-results.show');
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/{id}', [GroupController::class, 'show'])->name('groups.show');
    Route::get('/schedule-events', [ScheduleEventController::class, 'index'])->name('schedule-events.index');
    Route::get('/schedule-events/{id}', [ScheduleEventController::class, 'show'])->name('schedule-events.show');
});

Route::middleware(['auth:api', 'role:Manager,Admin'])->group(function () {
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
    Route::put('/exams/{id}', [ExamController::class, 'update'])->name('exams.update');
    Route::post('/exam-results', [ExamResultController::class, 'store'])->name('exam-results.store');
    Route::put('/exam-results/{id}', [ExamResultController::class, 'update'])->name('exam-results.update');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::put('/groups/{id}', [GroupController::class, 'update'])->name('groups.update');
    Route::post('/schedule-events', [ScheduleEventController::class, 'store'])->name('schedule-events.store');
    Route::put('/schedule-events/{id}', [ScheduleEventController::class, 'update'])->name('schedule-events.update');
});

Route::middleware(['auth:api', 'role:Admin'])->group(function () {
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::delete('/exams/{id}', [ExamController::class, 'destroy'])->name('exams.destroy');
    Route::delete('/exam-results/{id}', [ExamResultController::class, 'destroy'])->name('exam-results.destroy');
    Route::delete('/groups/{id}', [GroupController::class, 'destroy'])->name('groups.destroy');
    Route::delete('/schedule-events/{id}', [ScheduleEventController::class, 'destroy'])->name('schedule-events.destroy');
});
