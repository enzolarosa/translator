<?php

namespace enzolarosa\Translator\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;

class GitPush implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle()
    {
        $commands = [
            [
                'git',
                'add',
                'lang/vendor/translator/',
            ],
            [
                'git',
                'commit',
                '-m ' . config('translator.git.message'),
            ],
            [
                'git',
                'push',
            ],
        ];

        foreach ($commands as $command) {
            $process = new Process($command, base_path(''));
            $process->mustRun();
        }
    }
}
