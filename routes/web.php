<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return view('index');
})->name('home');

Route::group(['prefix' => 'auth', 'as' => 'auth.'], static function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login-form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register-form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::get('/countries', [CityController::class, 'getCountries'])->name('get-countries');
Route::post('/cities', [CityController::class, 'getCitiesByCountry'])->name('get-cities-by-country');

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth']], static function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::post('/add-city', [ProfileController::class, 'addCity'])->name('add-city');
    Route::post('/remove-city', [ProfileController::class, 'removeCity'])->name('remove-city');
    Route::post('/add-channel', [ProfileController::class, 'addNotificationChannel'])->name('add-channel');
    Route::delete('/remove-channel/{channel}', [ProfileController::class, 'removeNotificationChannel'])->name(
        'remove-channel'
    );
    Route::post('/set-tracking-parameter/{userPreference}', [ProfileController::class, 'updateTrackingParameter'])->name('set-tracking-parameter');
    Route::post('/pause-notifications', [ProfileController::class, 'pauseNotifications'])->name('pause-notifications');
    Route::post('/resume-notifications', [ProfileController::class, 'resumeNotifications'])->name('resume-notifications');
});
