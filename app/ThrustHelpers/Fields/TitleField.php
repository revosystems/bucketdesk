<?php

namespace App\ThrustHelpers\Fields;

use BadChoice\Thrust\Fields\Link;
use BadChoice\Thrust\Fields\Text;

class TitleField extends Link
{
    public $showInEdit = true;

    public function __construct()
    {
        $this->route('issues.show')->displayCallback(function($issue){
            return $issue->title;
        })->classes('showPopup');
    }

    public function displayInEdit($object, $inline = false)
    {
        return Text::make($this->field)->displayInEdit($object, $inline);
    }


}