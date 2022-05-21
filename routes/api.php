<?php

use enzolarosa\Translator\Http\Controllers\TranslatorController;
use Illuminate\Support\Facades\Route;

Route::controller(TranslatorController::class)
    ->group(function () {
        Route::get('current-locale', 'currentLocale');
        Route::get('available-locales', 'availableLocales');
        Route::get('receive/{locale}', 'receive');

        Route::post('write/{locale}', 'write');
    });
