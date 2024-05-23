<?php

namespace enzolarosa\Translator\Models;

use enzolarosa\Translator\Traits\Models\HasExternalId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translator extends Model
{
    use HasExternalId;
    use HasFactory;

    protected $fillable = [
        'original',
        'language',
        'translation',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function __construct()
    {
        $this->connection = config('translator.store.database.connection');
        $this->table = config('translator.store.database.table');

        parent::__construct();
    }
}
