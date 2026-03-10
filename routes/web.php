<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});
// Route::middleware(['web'])->group(function () {
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::post('/logout', [AuthController::class, 'logout']);
// });

Route::get('/test-email', function () {
    return view('emails.test-ticket');
});

Route::get('/send-mail', function () {
    Mail::send('emails.test-outlook', [], function ($message) {
        $message->to('recipient@example.com')
            ->subject('Test Email with Blade View');
    });

    return 'Email sent!';
});
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
