<?php

namespace enzolarosa\Translator;

use Illuminate\Support\Facades\Http;

class Translator
{
    public static function translate(string $text, string $target)
    {
        return Http::asJson()->post('https://translate.enzolarosa.dev/translate', [
            'q' => $text,
            'source' => 'auto',
            'target' => $target,
            'format' => 'text',
        ])->object();
    }
}
