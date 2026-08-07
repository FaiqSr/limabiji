<?php

use App\Http\Controllers\LandingPages;
use Illuminate\Support\Facades\Route;


Route::get('/', [LandingPages::class, 'index'])->name('landingpages.index');
