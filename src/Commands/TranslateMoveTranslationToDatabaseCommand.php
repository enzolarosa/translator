<?php

namespace enzolarosa\Translator\Commands;

use enzolarosa\Translator\Models\Translator as Model;
use enzolarosa\Translator\Translator;
use Illuminate\Console\Command;

class TranslateMoveTranslationToDatabaseCommand extends Command
{
    public $signature = 'translate:move-file-to-database';
    protected $description = 'Move all files transaction to database';

    public function handle(): int
    {
        foreach (config('translator.supported_language', []) as $target) {
            $this->fileToDatabase("$target.json",$target);
        }

        return self::SUCCESS;
    }

    protected function fileToDatabase($path,$language)
    {
        $keys = json_decode(disk('translator')->get($path) ?? '[]', true);
        foreach ($keys as $key => $string) {
            Model::query()->firstOrCreate([
                'original' => $key,
                'language' => $language
            ], [
                'translation' => $string,
            ]);
        }
    }
}
