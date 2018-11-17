<?php

namespace App\IssueTrackers\Bitbucket;

class FakeBitbucket
{
    public $issues = [];

    public function createIssue($account, $repoSlug, $title, $content = '', $extra = [])
    {
        $this->issues[] = $title;
        return (object)[
            'id'       => 123,
            'title'    => $title,
            'state'    => 'new',
            'priority' => 'major',
            'kind'     => 'bug'
        ];
    }
}
