<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Actions\ProductManager; // <-- IMPORTANTE: No olvides importar el componente aquí abajo
use App\Models\Product;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Cambiamos la ruta estática por el componente interactivo de Livewire
    Route::get('/dashboard', ProductManager::class)->name('dashboard');
});

require __DIR__.'/settings.php';