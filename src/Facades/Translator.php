<?php

namespace enzolarosa\Translator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \enzolarosa\Translator\Translator
 */
class Translator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'translator';
    }
}
