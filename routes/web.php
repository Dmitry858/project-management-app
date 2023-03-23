<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\AttachmentController;

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
require __DIR__.'/settings.php';

Route::middleware(['auth'])->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/projects', ProjectController::class);
    Route::resource('/tasks', TaskController::class);
    Route::resource('/members', MemberController::class);
    Route::post('/members/delete', [MemberController::class, 'destroyGroup'])->name('members.destroy-group');
    Route::resource('/users', UserController::class);
    Route::post('/users/delete', [UserController::class, 'destroyGroup'])->name('users.destroy-group');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/comments/create', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::patch('/change-stage/{task}', [TaskController::class, 'updateStage'])->name('tasks.update-stage');
    Route::resource('/invitations', InvitationController::class)->except([
        'edit', 'update', 'show'
    ])->middleware('permission:edit-invitations');
    Route::post('/invitations/{invitation}/send', [InvitationController::class, 'send'])->name('invitations.send')->middleware('permission:edit-invitations');
    Route::post('/invitations/delete', [InvitationController::class, 'destroyGroup'])->name('invitations.destroy-group')->middleware('permission:edit-invitations');
    Route::get('/attachments/{attachment}', [AttachmentController::class, 'show'])->name('attachment');
});
