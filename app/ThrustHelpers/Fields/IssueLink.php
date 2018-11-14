<?php

namespace App\ThrustHelpers\Fields;

use BadChoice\Thrust\Fields\Link;

class IssueLink extends Link
{
    public function __construct()
    {
        $this->displayCallback = function($object){
            return "#{$object->issue_id}";
        };
    }

    public function getUrl($issue)
    {
        return "https://bitbucket.org/{$issue->repository->account}/{$issue->repository->repo}/issues/{$issue->issue_id}";
    }

}