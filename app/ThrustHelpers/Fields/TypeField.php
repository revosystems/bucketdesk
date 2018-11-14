<?php

namespace App\ThrustHelpers\Fields;

use App\Issue;
use BadChoice\Thrust\Fields\Select;

class TypeField extends Select
{
    public function displayInIndex($object)
    {
        return [
            Issue::TYPE_TASK         => '👷',
            Issue::TYPE_BUG          => '👾',
            Issue::TYPE_ENHANCEMENT  => '💅',
            Issue::TYPE_PROPOSAL     => '💡',
        ][$this->getValue($object)];
    }

}