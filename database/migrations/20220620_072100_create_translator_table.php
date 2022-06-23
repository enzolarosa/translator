<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (config('translator.driver') == 'database') {
            return;
        }

        Schema::connection(config('translator.store.database.connection'))
            ->create(config('translator.store.database.table'), function (Blueprint $table) {
                $table->id();
                $table->uuid('external_id')->unique();

                $table->string('language');
                $table->text('original');

                $table->text('translation');

                $table->timestamps();

                $table->index(['language', 'original']);
            });
    }
};
