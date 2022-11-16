<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\InvitationController;

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

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/projects', ProjectController::class);
    Route::resource('/tasks', TaskController::class);
    Route::resource('/members', MemberController::class);
    Route::resource('/users', UserController::class);
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings')->middleware('permission:edit-settings');
    Route::get('/settings/caching', [SettingController::class, 'indexCaching'])->name('settings.caching')->middleware('permission:edit-settings');
    Route::post('/settings/caching', [SettingController::class, 'updateCaching'])->name('settings.caching.update')->middleware('permission:edit-settings');
    Route::resource('/settings/stages', StageController::class)->middleware('permission:edit-settings');
    Route::post('/comments/create', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::patch('/change-stage/{task}', [TaskController::class, 'updateStage']);
    Route::resource('/invitations', InvitationController::class)->except([
        'edit', 'show'
    ])->middleware('permission:edit-invitations');
});
