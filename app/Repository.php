<?php

namespace App;

use App\IssueTrackers\Bitbucket\Bitbucket;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    protected $guarded = [];

    public function createIssue($title, $content = '', $extra = [])
    {
        $issue = app(Bitbucket::class)->createIssue($this->account, $this->repo, $title, $content, $extra);
        return Issue::fromBitbucketIssue($this, $issue);
    }

    public function setupWebhook()
    {
        $url            = url('/webhook');
        $bitbucket      = (new Bitbucket);
        $alreadyCreated = collect($bitbucket->getWebhooks($this->account, $this->repo)->values)->contains(function ($webhook) use ($url) {
            return $webhook->url == $url || $webhook->description == 'Bucketdesk';
        });
        if ($alreadyCreated) {
            return;
        }
        $bitbucket->createHook($this->account, $this->repo, $url);
    }
}
