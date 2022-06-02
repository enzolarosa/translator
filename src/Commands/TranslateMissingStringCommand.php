<?php

namespace enzolarosa\Translator\Commands;

use Illuminate\Console\Command;

class TranslateMissingStringCommand extends Command
{
    public $signature = 'translate-missing-string';
    protected $description = 'Translate the missing string';

    public function handle(): int
    {
        return self::SUCCESS;
    }
}
