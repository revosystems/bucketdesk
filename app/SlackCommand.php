<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SlackCommand extends Model
{
    public function extractTags(&$string, $replaceString = true)
    {
        $tagsPattern = '(#\w+)';
        preg_match_all("/{$tagsPattern}/", $string, $tags);
        $finalString = trim(preg_replace("/ ?{$tagsPattern}/", '', $string));
//        dd(collect($tags[0])->map(function($tag){ return str_replace('#', '', $tag); })->toArray(), $finalString);
        if ($replaceString) {
            $string = $finalString;
        }
        return collect($tags[0])->map(function ($tag) {
            return str_replace('#', '', $tag);
        })->toArray();
    }
}
