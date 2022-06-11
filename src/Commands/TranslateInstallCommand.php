<?php

namespace enzolarosa\Translator\Commands;

use Illuminate\Console\Command;

class TranslateInstallCommand extends Command
{
    public $signature = 'translate:install';
    protected $description = 'Install all of the Translate';

    public function handle(): int
    {
        $this->comment(localize('Publishing Translate configuration...'));
        $this->callSilent('vendor:publish', ['--tag' => 'translator-config']);

        $this->comment(localize('Publishing Translate language file...'));
        $this->callSilent('vendor:publish', ['--tag' => 'translator-lang']);

        $this->info(localize('Translate was installed successfully.'));
    }
}
