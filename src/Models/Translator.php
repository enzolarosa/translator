<?php

namespace enzolarosa\Translator\Models;

use enzolarosa\MqttBroadcast\Traits\Models\ExternalId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translator extends Model
{
    use HasFactory;
    use ExternalId;

    public function __construct()
    {
        $this->connection = config('translator.store.database.connection');
        $this->table = config('translator.store.database.table');

        parent::__construct();
    }

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


}
