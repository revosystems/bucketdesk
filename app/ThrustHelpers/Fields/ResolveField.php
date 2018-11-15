<?php

namespace App\ThrustHelpers\Fields;

use BadChoice\Thrust\Fields\Link;

class ResolveField extends Link
{
    public function __construct()
    {
        $this->classes = 'button secondary shadow-outer-3';
        $this->route = 'issues.resolve';
        $this->displayCallback = function($object){
            if ($object->status < \App\Issue::STATUS_RESOLVED) {
//                return "<button><i class='fa fa-wrench'></i> RESOLVE</button>";
                return "RESOLVE";
            }
            return "";
        };
    }
}