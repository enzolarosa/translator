<?php

namespace enzolarosa\Translator\Http\Requests\Translator;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'keys'       => 'required|array',
            'keys.*.key' => 'required|string',
            'keys.*.str' => 'required|string',
            'locale'     => 'required|string|in:' . implode(',', config('translator.supported_language')),
        ];
    }
}
