<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    const STATUS_NEW     = 1;
    const STATUS_OPEN    = 2;
    const STATUS_HOLD    = 3;
    const STATUS_RESOLVED= 4;
    const STATUS_CLOSED  = 5;
    const STATUS_INVALID = 6;

    const PRIORITY_TRIVIAL  = 1;
    const PRIORITY_MINOR    = 2;
    const PRIORITY_MAJOR    = 3;
    const PRIORITY_CRITICAL = 4;
    const PRIORITY_BLOCKER  = 5;

    const TYPE_TASK         = 1;
    const TYPE_BUG          = 2;
    const TYPE_ENHANCEMENT  = 3;
    const TYPE_PROPOSAL     = 4;

    protected $guarded = [];

    use Taggable;

    public static function fromBitbucketIssue($repository, $issue)
    {
        return Issue::updateOrCreate([
            'repository_id' => $repository->id,
            'issue_id' => $issue->local_id ?? $issue->id,
        ], [
            'username' => $issue->responsible->username ?? ($issue->assignee->username ?? null),
            'title'    => str_limit($issue->title, 255),
            'status'   => Issue::parseStatus($issue->status ?? $issue->state),
            'priority' => Issue::parsePriority($issue->priority),
            'type'     => Issue::parseType($issue->metadata->kind ?? $issue->kind),
        ]);
    }

    public function repository()
    {
        return $this->belongsTo(Repository::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public static function parseStatus($statusName)
    {
        return [
            'new'     => static::STATUS_NEW     ,
            'open'    => static::STATUS_OPEN    ,
            'hold'    => static::STATUS_HOLD    ,
            'resolved'=> static::STATUS_RESOLVED    ,
            'closed'  => static::STATUS_CLOSED  ,
            'invalid' => static::STATUS_INVALID ,
        ][$statusName];
    }

    public static function parsePriority($priority)
    {
        return [
            'trivial'  => static::PRIORITY_TRIVIAL ,
            'minor'    => static::PRIORITY_MINOR   ,
            'major'    => static::PRIORITY_MAJOR   ,
            'critical' => static::PRIORITY_CRITICAL,
            'blocker'  => static::PRIORITY_BLOCKER ,
        ][$priority];
    }

    public static function parseType($kind)
    {
        return [
            'task'        => static::TYPE_TASK,
            'bug'         => static::TYPE_BUG,
            'enhancement' => static::TYPE_ENHANCEMENT,
            'proposal'    => static::TYPE_PROPOSAL,
        ][$kind];
    }
}
