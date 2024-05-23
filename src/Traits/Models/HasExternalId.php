<?php

namespace enzolarosa\Translator\Traits\Models;

use Illuminate\Support\Str;

trait HasExternalId
{
    public static function bootHasExternalId()
    {
        static::creating(function ($model) {
            $model->external_id ??= Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'external_id';
    }
}
