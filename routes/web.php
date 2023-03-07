<?php

use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::post('/upload', [UploadController::class, '__invoke']);
//    ->middleware('throttle:5,1440'); // 5 requests per day
