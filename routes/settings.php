<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\RoleController;

Route::middleware(['auth', 'permission:edit-settings'])->group(function() {
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::get('/settings/general', [SettingController::class, 'indexGeneral'])->name('settings.general');
    Route::post('/settings/general', [SettingController::class, 'updateGeneral'])->name('settings.general.update');
    Route::get('/settings/caching', [SettingController::class, 'indexCaching'])->name('settings.caching');
    Route::post('/settings/caching', [SettingController::class, 'updateCaching'])->name('settings.caching.update');
    Route::resource('/settings/stages', StageController::class);
    Route::resource('/settings/roles', RoleController::class);
});
