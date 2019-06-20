<?php

namespace App;

use App\IssueTrackers\Bitbucket\Bitbucket;
use App\Observers\IssueObserver;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    const STATUS_NEW        = 1;
    const STATUS_OPEN       = 2;
    const STATUS_HOLD       = 3;
    const STATUS_RESOLVED   = 4;
    const STATUS_CLOSED     = 5;
    const STATUS_INVALID    = 6;
    const STATUS_DUPLICATED = 7;
    const STATUS_WONTFIX    = 8;

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

    protected $shouldIgnoreBitbucketUpdate = false;
    private $presenter;

    public static function fromBitbucketIssue($repository, $issue)
    {
        return Issue::updateOrCreate([
            'repository_id' => $repository->id,
            'issue_id'      => $issue->local_id ?? $issue->id,
        ], [
            'username' => $issue->responsible->nickname ?? ($issue->assignee['nickname'] ?? null),
            'title'    => str_limit($issue->title, 255),
            'status'   => Issue::parseStatus($issue->status ?? $issue->state),
            'priority' => Issue::parsePriority($issue->priority),
            'type'     => Issue::parseType($issue->metadata->kind ?? $issue->kind),
        ]);
    }

    public function updateBitbucketIssue()
    {
        $this->updateBitbucketWith([
            'responsible' => $this->username,
            'title'    => $this->title,
            'status'   => array_flip(static::statuses())[$this->status],
            'priority' => array_flip(static::priorities())[$this->priority],
            'type'     => array_flip(static::types())[$this->type],
        ]);
    }

    public function update(array $attributes = [], array $options = [])
    {
        if (array_has($attributes, 'tags')) {
            $this->syncTags($attributes['tags']);
        }
        return tap(parent::update(array_except($attributes, 'tags'), $options), function () {
            if ($this->shouldIgnoreBitbucketUpdate) return;
            $this->updateBitbucketIssue();
        });
    }

    public function ignoreBitbucketUpdate($ignore = true)
    {
        $this->shouldIgnoreBitbucketUpdate = $ignore;
        return $this;
    }

    public function resolve()
    {
        $this->update(['status' => static::STATUS_RESOLVED]);
    }

    public function comment($comment)
    {
        return app(Bitbucket::class)->createComment($this->repository->account, $this->repository->repo, $this->issue_id, $comment);
    }

    public function updateBitbucketWith($array)
    {
        return app(Bitbucket::class)->updateIssue($this->repository->account, $this->repository->repo, $this->issue_id, $array);
    }

    public function getRemote()
    {
        return app(Bitbucket::class)->getIssue($this->repository->account, $this->repository->repo, $this->issue_id);
    }

    public function getComments()
    {
        return app(Bitbucket::class)->getIssueComments($this->repository->account, $this->repository->repo, $this->issue_id);
    }

    public function remoteLink()
    {
        return "https://bitbucket.org/{$this->repository->account}/{$this->repository->repo}/issues/{$this->issue_id}";
    }

    public function repository()
    {
        return $this->belongsTo(Repository::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    public static function statuses()
    {
        return [
            'new'      => static::STATUS_NEW     ,
            'open'     => static::STATUS_OPEN    ,
            'on hold'  => static::STATUS_HOLD    ,
            'resolved' => static::STATUS_RESOLVED    ,
            'closed'   => static::STATUS_CLOSED  ,
            'duplicate'=> static::STATUS_DUPLICATED    ,
            'wontfix'  => static::STATUS_WONTFIX    ,
            'invalid'  => static::STATUS_INVALID ,
        ];
    }

    public static function parseStatus($statusName)
    {
        return static::statuses()[$statusName];
    }

    public static function priorities()
    {
        return [
            'trivial'  => static::PRIORITY_TRIVIAL ,
            'minor'    => static::PRIORITY_MINOR   ,
            'major'    => static::PRIORITY_MAJOR   ,
            'critical' => static::PRIORITY_CRITICAL,
            'blocker'  => static::PRIORITY_BLOCKER ,
        ];
    }

    public static function parsePriority($priority)
    {
        return static::priorities()[$priority];
    }

    public static function types()
    {
        return [
            'task'        => static::TYPE_TASK,
            'bug'         => static::TYPE_BUG,
            'enhancement' => static::TYPE_ENHANCEMENT,
            'proposal'    => static::TYPE_PROPOSAL,
        ];
    }

    public static function parseType($kind)
    {
        return static::types()[$kind];
    }

    public function presenter()
    {
        if(! $this->presenter){
            $this->presenter = new IssuePresenter($this);
        }
        return $this->presenter;
    }

    public function scopeWorkingOn($query)
    {
        return $query->whereIn('status', [static::STATUS_NEW, static::STATUS_OPEN, static::STATUS_HOLD]);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', Issue::STATUS_OPEN);
    }
}
