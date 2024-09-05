<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/phpinfo', function () {
    // phpinfo() çıktısını göstermek için
    return response()->make(phpinfo(), 200, ['Content-Type' => 'text/html']);
});
