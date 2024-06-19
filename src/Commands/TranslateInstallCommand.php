<?php

namespace enzolarosa\Translator\Commands;

use Illuminate\Console\Command;

class TranslateInstallCommand extends Command
{
    public $signature = 'translate:install';

    protected $description = 'Install all of the Translate resources';

    public function handle()
    {
        $this->comment(localize('translator.installation.config'));
        $this->callSilent('vendor:publish', ['--tag' => 'translator-config']);

        $this->comment(localize('translator.installation.language'));
        $this->callSilent('vendor:publish', ['--tag' => 'translator-lang']);

        $this->info(localize('translator.installation.ok'));
    }
}
