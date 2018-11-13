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

    private $account;
    private $repo;

    public function __construct($account, $repo)
    {
        $this->account = $account;
        $this->repo    = $repo;
    }

    public function handle()
    {
        $this->createRepository();
        $this->importDevelopers();
        $this->importIssues();
    }

    private function parseIssues($start, $limit)
    {
        $issues = (new Bitbucket)->getIssues($this->account, $this->repo, [
            'status' => ['open', 'new'],
            'start'  => $start,
            'limit'  => $limit,
        ]);
        foreach ($issues->issues as $id => $issue) {
            Issue::updateOrCreate([
                'account'  => $this->account,
                'repo'     => $this->repo,
                'issue_id' => $issue->local_id,
                ], [
                'username' => $issue->responsible->username ?? null,
                'title'    => str_limit($issue->title, 255),
                'status'   => Issue::parseStatus($issue->status),
                'priority' => Issue::parsePriority($issue->priority),
                'type'     => Issue::parseType($issue->metadata->kind),
            ]);
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
        Repository::firstOrCreate([
            'account' => $this->account,
            'repo'    => $this->repo,
        ]);
    }
}
