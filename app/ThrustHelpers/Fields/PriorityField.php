<?php

namespace App\ThrustHelpers\Fields;

use App\Issue;
use BadChoice\Thrust\Fields\Select;

class PriorityField extends Select
{
    public function displayInIndex($object)
    {
        return [
            Issue::PRIORITY_TRIVIAL  => '🌈',
            Issue::PRIORITY_MINOR    => '🥊️',
            Issue::PRIORITY_MAJOR    => '😶',
            Issue::PRIORITY_CRITICAL => '🔥',
            Issue::PRIORITY_BLOCKER  => '☠️',
        ][$this->getValue($object)];
    }

}