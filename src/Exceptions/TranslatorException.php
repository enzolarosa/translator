<?php

namespace enzolarosa\Translator\Exceptions;

use Exception;

class TranslatorException extends Exception
{
    public static function localeNotSupported(string $locale)
    {
        return new self(localize('The selected locale `:locale` wasn\'t found in your configuration.', [
            'locale' => $locale,
        ]));
    }
}
