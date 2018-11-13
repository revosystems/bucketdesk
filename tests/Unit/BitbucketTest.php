<?php

namespace Tests\Unit;

use App\IssueTrackers\Bitbucket\Bitbucket;
use Tests\TestCase;

/** @group cloud */
class BitbucketTest extends TestCase
{
    /** @test */
    public function can_connect_to_bitbucket()
    {
        $user = new \Bitbucket\API\User();
        $user->getClient()->addListener(
            new \Bitbucket\API\Http\Listener\BasicAuthListener(config('services.bitbucket.user'), config('services.bitbucket.password'))
        );

        // now you can access protected endpoints as $bb_user
        $response = $user->get();
        $this->assertEquals('BadChoice', json_decode($response->getContent())->user->username);
    }

    /** @test */
    public function it_can_fetch_issues()
    {
        $issues = (new Bitbucket)->getIssues('revo-pos', 'revo-back', [
            'status' => ['open', 'new']
        ]);

        $this->assertTrue(count($issues->issues) > 2);
    }

    /** @test */
    public function can_fetch_a_single_issue()
    {
        $issue = (new Bitbucket)->getIssue('revo-pos', 'revo-back', 555);
        $this->assertEquals('eduardda', $issue->responsible->username);
    }
    
    /** @test */
    public function can_initialize_webhook()
    {
        $hooks = (new Bitbucket)->getWebhooks('revo-pos', 'revo-back');
        $this->assertTrue(count($hooks->values) > 1);
    }

    /** @test */
    public function can_get_groups()
    {
        $groups = (new Bitbucket)->getGroups('revo-pos');
        dd($groups);
    }
}
