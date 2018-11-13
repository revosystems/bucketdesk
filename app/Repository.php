<?php

namespace App;

use App\IssueTrackers\Bitbucket\Bitbucket;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    protected $guarded = [];

    public function createIssue($title, $content = '')
    {
        $issue = (new Bitbucket)->createIssue($this->account, $this->repo, $title, $content);
        Issue::fromBitbucketIssue($this, $issue);
    }
}
