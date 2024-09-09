<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/phpinfo', function () {
    return response()->make(phpinfo(), 200, ['Content-Type' => 'text/html']);
});
