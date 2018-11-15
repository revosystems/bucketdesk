<?php

namespace App\IssueTrackers\Bitbucket;

// https://gentlero.bitbucket.io/bitbucket-api/1.0/examples/repositories/issues.html
class Bitbucket
{
    protected $auth;
    protected static $oauthParameters;

    public static function setOAuth($parameters){
        static::$oauthParameters = $parameters;
    }

    public function __construct()
    {
        $this->auth = new \Bitbucket\API\Authentication\Basic(config('services.bitbucket.user'), config('services.bitbucket.password'));
    }

    public function getIssues($account, $repoSlug, $options = [])
    {
        $issue = new \Bitbucket\API\Repositories\Issues();
        $this->setAuth($issue);

        return $this->parseResponse(
            $issue->all($account, $repoSlug, $options)
        );
    }

    public function getIssue($account, $repoSlug, $id)
    {
        $issue = new \Bitbucket\API\Repositories\Issues();
        $this->setAuth($issue);
        return $this->parseResponse(
            $issue->get($account, $repoSlug, $id)
        );
    }

    public function updateIssue($account, $repoSlug, $id, $fields)
    {
        $issue = new \Bitbucket\API\Repositories\Issues();
        $this->setAuth($issue);
        return $this->parseResponse(
            $issue->update($account, $repoSlug, $id, $fields)
        );
    }

    public function createIssue($account, $repoSlug, $title, $content = '', $extra = [])
    {
        $issue = new \Bitbucket\API\Repositories\Issues();
        $this->setAuth($issue);
        return $this->parseResponse(
            $issue->create($account, $repoSlug, array_merge([
                'title'     => $title,
                'content'   => $content,
                'kind'      => 'task',
                'priority'  => 'major'
            ], $extra))
        );
    }

    public function getIssueComments($account, $repoSlug, $id)
    {
        $issue = new \Bitbucket\API\Repositories\Issues();
        $this->setAuth($issue);
        return $this->parseResponse(
            $issue->comments()->all($account, $repoSlug, $id)
        );
    }

    public function createComment($account, $repoSlug, $id, $comment)
    {
        $issue = new \Bitbucket\API\Repositories\Issues();
        $this->setAuth($issue);
        return $this->parseResponse(
            $issue->comments()->create($account, $repoSlug, $id, $comment)
        );
    }

    public function parseResponse($response)
    {
        return json_decode($response->getContent());
    }

    public function getWebhooks($account, $repoSlug)
    {
        $hooks  = new \Bitbucket\API\Repositories\Hooks();
        $this->setAuth($hooks);

        return $this->parseResponse(
            $hooks->all($account, $repoSlug)
        );
    }

    public function createHook($account, $repoSlug, $url)
    {
        $hook  = new \Bitbucket\API\Repositories\Hooks();
        $this->setAuth($hook);

        $response = $hook->create($account, $repoSlug, [
            'description' => 'Bucketdesk',
            'url' => $url,
            'active' => true,
            'events' => [
                'issue:created',
                'issue:updated'
            ]
        ]);
        return $this->parseResponse($response);
    }

    public function getGroups($account)
    {
        $groups = new \Bitbucket\API\Groups();
        $this->setAuth($groups);

        return $this->parseResponse(
            $groups->get($account)
        );
    }

    private function setAuth($class){
        //$issue->setCredentials($this->auth);
        $class->getClient()->addListener(
            new \Bitbucket\API\Http\Listener\OAuth2Listener(static::$oauthParameters ?? [
                'client_id'         => config('services.bitbucket.oauth.key'),
                'client_secret'     => config('services.bitbucket.oauth.secret'),
            ])
        );
    }
}
