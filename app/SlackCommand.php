<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SlackCommand extends Model
{
    public function extractRepository(&$text, $replaceString = true)
    {
        $repoName   = explode(' ', trim($text))[0];
        $repository = Repository::where('name', $repoName)->orWhere('repo', $repoName)->first();
        if ($replaceString) {
            $text = trim(str_replace($repoName, '', $text));
        }
        return $repository ?? $repoName;
    }

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

    public function extractUser(&$string, $replaceString = true)
    {
        $userPattern = '(@\w+)';
        preg_match_all("/{$userPattern}/", $string, $users);
        $user          = null;
        $foundUsername = collect($users[0])->first(function ($username) use (&$user) {
            $username = str_replace('@', '', $username);
            $user = User::where('username', 'like', "%{$username}%")->orWhere('name', 'like', "%{$username}%")->first();
            return $user != null;
        });
        if ($foundUsername && $replaceString) {
            $string = str_replace($foundUsername, '', $string);
        }
        return $user;
    }

    public function extractPriority(&$text, $default = 'major', $replaceString = true)
    {
        return $this->extractFrom(array_flip(Issue::priorities()), $text, $default, $replaceString);
    }

    public function extractStatus(&$text, $default = 'new', $replaceString = true)
    {
        return $this->extractFrom(array_flip(Issue::statuses()), $text, $default, $replaceString);
    }

    public function extractType(&$text, $default = 'bug', $replaceString = true)
    {
        return $this->extractFrom(array_flip(Issue::types()), $text, $default, $replaceString);
    }

    public function extractFrom($options, &$text, $default, $replaceString = true)
    {
        $result = collect($options)->first(function ($option) use ($text) {
            return str_contains(":{$text}", $option);
        }, $default);
        if ($replaceString) {
            $text = trim(str_replace(":{$result}", '', $text));
        }
        return $result;
    }
}
