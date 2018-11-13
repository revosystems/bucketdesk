<?php

namespace App\ThrustHelpers\Actions;

use App\Repository;
use BadChoice\Thrust\Actions\MainAction;

class QuickCreateIssue extends MainAction
{
    public function display($resourceName, $parent_id = null){
        return view('components.actions.quickCreateIssue', [
            'repositories' => Repository::all()
        ])->render();
    }
}