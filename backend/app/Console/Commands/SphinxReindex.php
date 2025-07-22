<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class SphinxReindex extends Command
{
    protected $signature = 'sphinx:reindex';
    protected $description = 'Trigger Sphinx reindex for posts';

    public function handle()
    {
        $process = new Process(['docker', 'exec', 'blog-sphinx', 'indexer', 'posts', '--rotate']);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('Sphinx reindex failed: ' . $process->getErrorOutput());
            return 1;
        }

        $this->info('Sphinx reindex completed: ' . $process->getOutput());
        return 0;
    }
}

