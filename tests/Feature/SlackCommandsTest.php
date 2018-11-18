<?php

namespace Tests\Feature;

use App\Issue;
use App\IssueTrackers\Bitbucket\Bitbucket;
use App\IssueTrackers\Bitbucket\FakeBitbucket;
use App\Repository;
use App\User;
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
        factory(Repository::class)->create(['name' => 'xef-back', 'account' => 'revo-pos', 'repo' => 'revo-back']);
        $response = $this->post('slack', ['text' => 'xef-back hello baby']);

        $response->assertStatus(200);
        $this->assertEquals(1, Issue::count());
        $response->assertJsonFragment([
            "text" => "https://bitbucket.org/revo-pos/revo-xef/issues/123",
        ]);

        tap (Issue::first(), function($issue){
            $this->assertEquals("hello baby", $issue->title);
            $this->assertCount(1, $this->fakeBitbucket->issues);
            $this->assertEquals(1, $issue->repository_id);
            $this->assertCount(0, $issue->tags);
        });
    }

    /** @test */
    public function repository_is_needed_to_create_an_issue()
    {
        $this->withoutExceptionHandling();
        factory(Repository::class)->create(['name' => 'xef-back']);
        $response = $this->post('slack', ['text' => 'non-existing-repo hello baby']);

        $response->assertStatus(200);
        $this->assertEquals(0, Issue::count());
    }

    /** @test */
    public function it_can_extract_tags_from_title()
    {
        $this->withoutExceptionHandling();
        factory(Repository::class)->create(['name' => 'xef-back', 'account' => 'revo-pos', 'repo' => 'revo-xef']);
        $response = $this->post('slack', ['text' => 'xef-back hello #tag1 baby with #tag2']);

        $response->assertStatus(200);
        $this->assertEquals(1, Issue::count());
        tap (Issue::first(), function($issue){
            $this->assertCount(2, $issue->tags);
            $this->assertEquals("tag1,tag2", $issue->tagsString());
        });
    }

    /** @test */
    public function it_can_set_the_status_if_status_is_in_the_text()
    {
        $this->withoutExceptionHandling();
        factory(Repository::class)->create(['name' => 'xef-back', 'account' => 'revo-pos', 'repo' => 'revo-xef']);
        $response = $this->post('slack', ['text' => 'xef-back hello :open :task :blocker']);

        $response->assertStatus(200);
        $this->assertEquals(1, Issue::count());
        tap (Issue::first(), function($issue){
            $this->assertEquals('hello', $issue->title);
            $this->assertEquals(Issue::STATUS_OPEN, $issue->status);
            $this->assertEquals(Issue::TYPE_TASK, $issue->type);
            $this->assertEquals(Issue::PRIORITY_BLOCKER, $issue->priority);
        });
    }

    /** @test */
    public function it_can_assign_the_new_issue_to_an_user(){
        $this->withoutExceptionHandling();
        factory(Repository::class)->create(['name' => 'xef-back', 'account' => 'revo-pos', 'repo' => 'revo-xef']);
        factory(User::class)->create(['username' => 'pepe']);

        $response = $this->post('slack', ['text' => 'xef-back hello @pep @amazon :open']);

        $response->assertStatus(200);
        tap (Issue::first(), function($issue){
            $this->assertEquals('hello  @amazon', $issue->title);
            $this->assertEquals('pepe', $issue->username);
            $this->assertEquals(Issue::STATUS_OPEN, $issue->status);
        });
    }
}