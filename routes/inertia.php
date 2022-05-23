<?php

use enzolarosa\Translator\Http\Controllers\Inertia\IndexController;
use Illuminate\Support\Facades\Route;
use Laravel\Nova\Http\Requests\NovaRequest;

Route::get('/', [IndexController::class, 'index']);
