<?php

namespace App\Jobs;

use App\Issue;
use App\IssueTrackers\Bitbucket\Bitbucket;
use App\Repository;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class InitializeRepo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $account;
    public $repo;
    public $setupWebhook;
    private $repository;

    public function __construct($account, $repo, $setupWebhook = true)
    {
        $this->account      = $account;
        $this->repo         = $repo;
        $this->setupWebhook = $setupWebhook;
    }

    public function handle()
    {
        $this->createRepository();
        $this->importDevelopers();
        $this->importIssues();
        $this->setupWebhook();
    }

    private function parseIssues($start, $limit)
    {
        $issues = (new Bitbucket)->getIssues($this->account, $this->repo, [
            'status' => ['open', 'new'],
            'start'  => $start,
            'limit'  => $limit,
        ]);
        foreach ($issues->issues as $id => $issue) {
            Issue::fromBitbucketIssue($this->repository, $issue);
        }
        return $issues;
    }

    public function importIssues(): void
    {
        $start  = 0;
        $issues = $this->parseIssues($start, 50);
        while (count($issues->issues) == 50) {
            $issues = $this->parseIssues($start, 50);
            $start += 50;
        }
    }

    private function importDevelopers()
    {
        $groups     = (new Bitbucket)->getGroups('revo-pos');
        $developers = collect($groups)->firstWhere('name', config('services.bitbucket.developersGroup'))->members;
        collect($developers)->each(function ($developer) {
            User::firstOrCreate([
                'username' => $developer->username
            ], [
                'name'     => $developer->display_name,
                'password' => bcrypt(str_random(8)),
                'email'    => str_random(4) . '_tobefilled@email.com'
            ]);
        });
    }

    public function createRepository(): void
    {
        $this->repository = Repository::firstOrCreate([
            'name'    => $this->repo,
            'account' => $this->account,
            'repo'    => $this->repo,
        ]);
    }

    private function setupWebhook()
    {
        if (! $this->setupWebhook) {
            return;
        }
        $this->repository->setupWebhook();
    }
}
