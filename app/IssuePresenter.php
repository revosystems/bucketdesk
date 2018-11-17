<?php

namespace App;


class IssuePresenter
{
    private $issue;

    public function __construct($issue)
    {
        $this->issue = $issue;
    }

    public function status()
    {
        return array_flip(Issue::statuses())[$this->issue->status];
    }

    public function priority()
    {
        return [
            Issue::PRIORITY_TRIVIAL  => '🌈',
            Issue::PRIORITY_MINOR    => '🥊️',
            Issue::PRIORITY_MAJOR    => '😶',
            Issue::PRIORITY_CRITICAL => '🔥',
            Issue::PRIORITY_BLOCKER  => '☠️',
        ][$this->issue->priority];
    }

    public function type()
    {
        return [
            Issue::TYPE_TASK         => '👷',
            Issue::TYPE_BUG          => '👾',
            Issue::TYPE_ENHANCEMENT  => '💅',
            Issue::TYPE_PROPOSAL     => '💡',
        ][$this->issue->type];
    }

    public function tags()
    {
        return $this->issue->tags->reduce(function ($carry, $tag) {
            return $carry . "<span class='tag'>{$tag->name}</span>";
        });
    }

    public function __get($name)
    {
        return $this->$name();
    }
}
