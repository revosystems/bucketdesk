<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InitializeRepo extends Command
{
    protected $signature = 'bucketdesk:initialize {repo}';
    protected $description = 'Import all the issues and users from a repo ';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        [$account, $repo] = explode('/', $this->argument('repo'));
        $this->info("Importing developers and issues from {$account} / {$repo}");
        \App\Jobs\InitializeRepo::dispatch($account, $repo);
        $this->info('Done');
    }
}
