<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StreamingController;
use App\Http\Controllers\ModeController;
use App\Http\Controllers\SimpleDemoController;

Route::get('/', function () {
    return view('simple-demo');
});

// AI Mode Switcher
Route::get('/ai/mode', [ModeController::class, 'index'])->name('ai.mode');
Route::post('/ai/mode/switch', [ModeController::class, 'switch'])->name('ai.mode.switch');
Route::post('/ai/mode/clear', [ModeController::class, 'clear'])->name('ai.mode.clear');

// Simple Demo Routes
Route::post('/chat/demo', [SimpleDemoController::class, 'chat'])->name('chat.demo');
Route::post('/image/demo', [SimpleDemoController::class, 'image'])->name('image.demo');
Route::post('/audio/demo', [SimpleDemoController::class, 'audio'])->name('audio.demo');
Route::post('/search/demo', [SimpleDemoController::class, 'search'])->name('search.demo');

// Streaming Demo Routes
Route::get('/streaming/demo', [StreamingController::class, 'demo'])->name('streaming.demo');
Route::post('/streaming/words', [StreamingController::class, 'streamWords'])->name('streaming.words');
Route::post('/streaming/tokens', [StreamingController::class, 'streamTokens'])->name('streaming.tokens');
Route::post('/streaming/regular', [StreamingController::class, 'regular'])->name('streaming.regular');

// API Routes for AI features
Route::post('/image/generate', [ImageController::class, 'generate'])->name('image.generate');
Route::post('/image/edit', [ImageController::class, 'edit'])->name('image.edit');

Route::post('/audio/synthesize', [AudioController::class, 'synthesize'])->name('audio.synthesize');
Route::post('/audio/transcribe', [AudioController::class, 'transcribe'])->name('audio.transcribe');

Route::get('/search/search', [SearchController::class, 'search'])->name('search.search');
Route::post('/search/store', [SearchController::class, 'store'])->name('search.store');
