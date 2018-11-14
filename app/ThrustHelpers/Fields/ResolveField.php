<?php

namespace App\ThrustHelpers\Fields;

use BadChoice\Thrust\Fields\Link;

class ResolveField extends Link
{
    public function __construct()
    {
        $this->route = 'issues.resolve';
        $this->displayCallback = function($object){
            if ($object->status < \App\Issue::STATUS_RESOLVED) {
//                return "<button><i class='fa fa-wrench'></i> RESOLVE</button>";
                return "<button class='secondary shadow-outer-3'>RESOLVE</button>";
            }
            return "";
        };
    }
}