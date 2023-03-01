<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SoundController;
use App\Http\Controllers\Admin\TimerController;
use App\Http\Controllers\Admin\UserController;

//ADMIN ROUTE
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/get-session-data', [AdminAuthController::class, 'get_session_data'])->name('get_session_data');

    //PROFILE ROUTE
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/update-profile', [ProfileController::class, 'update_profile'])->name('update_profile');
    Route::post('/update-password', [ProfileController::class, 'update_password'])->name('update_password');

    //USER ROUTE
    Route::get('/user', [UserController::class, 'user'])->name('user');
    Route::get('/add-user', [UserController::class, 'add_user'])->name('add_user');
    Route::post('/add-user-action', [UserController::class, 'add_user_action'])->name('add_user_action');
    Route::get('/edit-user/{id}', [UserController::class, 'edit_user'])->name('edit_user');
    Route::post('/edit-user-action/{id}', [UserController::class, 'edit_user_action'])->name('edit_user_action');
    Route::get('/view-user-details/{id}', [UserController::class, 'view_user_details'])->name('view_user_details');
    Route::get('/delete-user/{id}', [UserController::class, 'delete_user'])->name('delete_user');
    Route::get('/edit-user-status/{id}', [UserController::class, 'edit_user_status'])->name('edit_user_status');

    //TIMER ROUTE
    Route::get('/timer-list', [TimerController::class, 'timer_list'])->name('timer_list');
    Route::get('/add-timer', [TimerController::class, 'add_timer'])->name('add_timer');
    Route::post('/add-timer-action', [TimerController::class, 'add_timer_action'])->name('add_timer_action');
    Route::get('/edit-timer/{id}', [TimerController::class, 'edit_timer'])->name('edit_timer');
    Route::get('/edit-timer-action/{id}', [TimerController::class, 'edit_timer_action'])->name('edit_timer_action');

    //SOUND ROUTE
    Route::get('sound-list', [SoundController::class, 'sound_list'])->name('sound_list');
    Route::get('add-sound', [SoundController::class, 'add_sound'])->name('add_sound');
    Route::post('add-sound-action', [SoundController::class, 'add_sound_action'])->name('add_sound_action');
    Route::get('edit-sound/{id}', [SoundController::class, 'edit_sound'])->name('edit_sound');
    Route::post('edit-sound-action/{id}', [SoundController::class, 'edit_sound_action'])->name('edit_sound_action');
    Route::get('delete-sound/{id}', [SoundController::class, 'delete_sound'])->name('delete_sound');
    Route::get('edit-sound-status/{id}', [SoundController::class, 'edit_sound_status'])->name('edit_sound_status');

});

//FRONTEND ROUTE
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [AdminAuthController::class, 'admin_login'])->name('login');
    Route::post('/admin-login-action', [AdminAuthController::class, 'admin_login_action'])->name('admin.login.action');
    Route::get('/reset-password', [AdminAuthController::class, 'reset_password_load'])->name('reset_password_load');
    Route::post('/reset-password', [AdminAuthController::class, 'reset_password'])->name('reset_password');

});
