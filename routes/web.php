<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\StepsController;
use App\Http\Controllers\InformationsController;
use App\Http\Controllers\InquiriesController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
  Route::get('/login', [UsersController::class, 'login'])->name('login');
  Route::post('login', [UsersController::class, 'login_auth']);
  Route::get('/register', [UsersController::class, 'new'])->name('user.register');
  Route::post('/register', [UsersController::class, 'create']);
  Route::get('/forgot-password', [UsersController::class, 'forgot_pass'])->name('password.forgot');
  Route::post('/forgot-password', [UsersController::class, 'forgot_pass_auth']);
  Route::get('/reset-password/{token}', [UsersController::class, 'reset_pass'])->name('password.reset');
  Route::put('/reset-password', [UsersController::class, 'reset_pass_auth'])->name('password.store');
});


Route::middleware('auth')->group(function () {
  Route::post('logout', [UsersController::class, 'logout'])->name('logout');
  Route::get('/users', [UsersController::class, 'mypage'])->name('mypage');
  Route::get('/users/password', [UsersController::class, 'pass_edit'])->name('password.edit');
  Route::put('/users/password', [UsersController::class, 'pass_update']);
  Route::get('/users/profile', [UsersController::class, 'prof_edit'])->name('profile.edit');
  Route::patch('/users/profile', [UsersController::class, 'prof_update']);
  Route::get('/users/withdraw', [UsersController::class, 'destroy_confirm'])->name('withdraw');
  Route::delete('/users/withdraw', [UsersController::class, 'destroy']);

  Route::get('/steps/new', [StepsController::class, 'new'])->name('step.register');
  Route::post('/steps/new', [StepsController::class, 'create']);
  Route::get('/users/steps/{id}/edit', [StepsController::class, 'edit'])->name('step.edit');
  Route::put('/users/steps/{id}/edit', [StepsController::class, 'update']);
  Route::delete('/users/steps/{id}/edit', [StepsController::class, 'destroy']);
  Route::get('/users/steps', [StepsController::class, 'mystep'])->name('mystep');

  Route::get('/users/challenges', [StepsController::class, 'challenges'])->name('challenges');
  Route::post('/steps/{id}/challenge', [StepsController::class, 'challenge'])->name('step.challenge');
  Route::delete('/steps/{id}/challenge', [StepsController::class, 'cancel']);
  Route::put('/steps/{id}/clear/{step}', [StepsController::class, 'clear'])->name('small_step.clear');
});


Route::get('/terms-of-service', [InformationsController::class, 'tos'])->name('tos');
Route::get('/privacy-policy', [InformationsController::class, 'privacy'])->name('privacy');
Route::get('/steps', [StepsController::class, 'index'])->name('step.index');
Route::get('/steps/{id}/show', [StepsController::class, 'show'])->name('step.show');
Route::get('/steps/{id}/show/{step}', [StepsController::class, 'show_small'])->name('step.show_small');
Route::get('/profile/{id}', [StepsController::class, 'show_profile'])->name('profile.show');
Route::get('/inquiry', [InquiriesController::class, 'new'])->name('inquiry');
Route::post('/inquiry', [InquiriesController::class, 'create']);









