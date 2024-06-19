<?php

namespace enzolarosa\Translator\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;

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
                '-m '.localize('translator.git.commit.message'),
            ],
            [
                'git',
                'push',
            ],
        ];

        foreach ($commands as $k => $command) {
            Process::path(base_path())->run($command);
        }
    }
}
