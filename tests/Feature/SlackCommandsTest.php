<?php

namespace Tests\Feature;

use App\Issue;
use App\IssueTrackers\Bitbucket\Bitbucket;
use App\IssueTrackers\Bitbucket\FakeBitbucket;
use App\Repository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class SlackCommandsTest extends TestCase
{
    use RefreshDatabase;

    private $fakeBitbucket;

    public function setUp()
    {
        parent::setUp();
        $this->fakeBitbucket = new FakeBitbucket;
        $this->app->instance(Bitbucket::class, $this->fakeBitbucket);
    }

    /** @test */
    public function it_can_create_an_issue_from_slack()
    {
        $this->withoutExceptionHandling();
        factory(Repository::class)->create(['name' => 'xef-back']);
        $response = $this->post('slack', ['text' => 'xef-back hello baby']);

        $response->assertStatus(200);
        $this->assertEquals(1, Issue::count());

        tap (Issue::first(), function($issue){
            $this->assertEquals("hello baby", $issue->title);
            $this->assertCount(1, $this->fakeBitbucket->issues);
            $this->assertEquals(1, $issue->repository_id);
            //$this->assertEquals("tag1, tag2", $issue->tagsString());
        });
    }

    /** @test */
    public function repository_is_needed_to_create_an_issue()
    {
        factory(Repository::class)->create(['name' => 'xef-back']);
        $response = $this->post('slack', ['text' => 'non-existing-repo hello baby']);

        $response->assertStatus(200);
        $this->assertEquals(0, Issue::count());
    }

    /** @test */
    public function it_can_extract_tags_from_title()
    {

    }
}