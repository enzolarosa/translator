<?php

namespace enzolarosa\Translator\Commands;

use Illuminate\Console\Command;

class TranslateInstallCommand extends Command
{
    public $signature = 'translate:install';
    protected $description = 'Install all of the Translate resources';

    public function handle()
    {
        $this->comment(localize('Publishing Translate configuration...'));
        $this->callSilent('vendor:publish', ['--tag' => 'translator-config']);

        $this->comment(localize('Publishing Translate language file...'));
        $this->callSilent('vendor:publish', ['--tag' => 'translator-lang']);

        $this->comment('Publishing Translate migrations...');
        $this->callSilent('vendor:publish', ['--tag' => 'translator-migrations']);

        $this->info(localize('Translate was installed successfully.'));
    }
}
