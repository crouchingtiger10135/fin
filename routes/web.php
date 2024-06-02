<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DocumentController;

Route::get('/', [ClientController::class, 'index'])->name('home'); // Changed name from 'clients.index' to 'home'
Route::get('/clients', [ClientController::class, 'index'])->name('clients.index'); // Ensure this is not duplicated
Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy'); // Add this line
Route::post('/clients/{client}/verify', [ClientController::class, 'createVerificationSession'])->name('clients.verify');
Route::get('/clients/verification/callback', [ClientController::class, 'handleVerificationCallback'])->name('clients.verification.callback');
Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
