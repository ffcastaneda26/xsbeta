<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {

    if (auth()->check()) {
        return redirect()->route('dashboard');
    } else {
        return view('welcome');
    }
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {

        if(Auth::user()->isAdministrator()){
            return redirect()->to('/admin');
        }


        if(Auth::user()->isCompanyManager()){
            return redirect()->to('/company');
        }


        return view('dashboard');
    })->name('dashboard');
});
