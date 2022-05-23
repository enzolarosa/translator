<?php

use enzolarosa\Translator\Http\Controllers\ApiTranslatorController;
use Illuminate\Support\Facades\Route;

Route::controller(ApiTranslatorController::class)
    ->name('translator.api.')
    ->group(function () {
        Route::post('/', 'store')->name('store');
    });
