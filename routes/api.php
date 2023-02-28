<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SoundController;
use App\Http\Controllers\Api\TimerController as ApiTimerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::get('account-type', [AuthController::class, 'account_type']);
Route::get('how-did-you-find-us', [AuthController::class, 'find_us']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forget-password', [AuthController::class, 'forget_password']);
Route::post('update-forget-password', [AuthController::class, 'update_forget_password']);
Route::post('verify-email/', [AuthController::class, 'verify_email_with_code']);
Route::middleware('auth:api')->group(function(){
    //AUTHENTICATION ROUTE
    Route::get('user-info',[AuthController::class, 'user_info']);
    Route::get('edit-account',[AuthController::class, 'edit_account']);
    Route::post('edit-account-action',[AuthController::class, 'edit_account_action']);
    Route::post('delete-account',[AuthController::class, 'delete_account_action']);
    Route::get('send-verify-email/{email}', [AuthController::class, 'verify_email']);
    Route::post('logout',[AuthController::class, 'logout']);
    
    //TIMER ROUTE
    Route::get('/timer-list', [ApiTimerController::class, 'timer_list'])->name('timer_list');
    Route::post('/add-timer-action', [ApiTimerController::class, 'add_timer_action'])->name('add_timer_action');
    Route::post('/delete-timer', [ApiTimerController::class, 'delete_timer'])->name('delete_timer');
    Route::post('/duplicate-timer', [ApiTimerController::class, 'duplicate_timer'])->name('duplicate_timer');
    Route::post('/favourite', [ApiTimerController::class, 'favourite'])->name('favourite');


    //NOTIFICATIONS ROUTE
    Route::get('/notification-list',[NotificationController::class,'notification_list'])->name('notification_list');
    Route::post('/notification-action',[NotificationController::class,'notification_action'])->name('notification_action');

    //SOUND ROUTE
    Route::get('sound-list',[SoundController::class,'sound_list'])->name('sound_list');
    Route::post('select-sound', [SoundController::class,'select_sound'])->name('select_sound');
});
