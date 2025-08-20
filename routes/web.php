<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TextToSpeechController;

Route::get('/', function () {
    return view('welcome');
});

// Rota de teste da API
Route::get('/test-api', function () {
    return view('test-api');
});

// Rotas para Text-to-Speech
Route::get('/text-to-speech', [TextToSpeechController::class, 'index'])->name('tts.index');
Route::post('/text-to-speech/convert', [TextToSpeechController::class, 'convert'])->name('tts.convert');
