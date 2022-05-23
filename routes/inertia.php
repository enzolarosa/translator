<?php

use enzolarosa\Translator\Http\Controllers\TranslatorController;
use Illuminate\Support\Facades\Route;

Route::controller(TranslatorController::class)
    ->name('translator.')
    ->group(function () {
        Route::get('/{locale?}', 'index')->name('index');
    });
