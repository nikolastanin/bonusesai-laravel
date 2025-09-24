<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'chat')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('chat', 'chat')
    ->middleware(['auth', 'verified'])
    ->name('chat');

// Test route for AI agent integration
Route::get('/test-agent', function () {
    $agentUrl = config('services.ai_agent.url');
    return response()->json([
        'agent_url' => $agentUrl,
        'status' => 'Agent URL configured successfully'
    ]);
})->middleware(['auth', 'verified']);

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
});

require __DIR__.'/auth.php';
