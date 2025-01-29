<?php

use Illuminate\Support\Facades\Route;

Route::prefix('reports')->name('reports.')->group(function () {
    Route::view('/', 'reports.index')->name('index');
});
