<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/agenda/{slug}', [AgendaController::class, 'show'])->name('agenda.show');
Route::get('/agenda/{slug}/slots', [AgendaController::class, 'slots'])->name('agenda.slots');
Route::post('/agenda/{slug}/agendar', [AgendaController::class, 'store'])->name('agenda.store');