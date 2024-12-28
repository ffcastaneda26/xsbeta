<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified'])->group(function () {
    Route::get('/', function () {
        return redirect()->to('/admin');
     })->name('home');
    
     Route::get('/dashboard', function () {
        return redirect()->to('/admin');
    })->name('dashboard');   
});
