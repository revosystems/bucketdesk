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
        return $issue->remoteLink();
    }

}