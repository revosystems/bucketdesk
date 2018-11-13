<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IssueTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function issue_can_have_tags()
    {
        $issue = factory(\App\Issue::class)->create();
        $issue->attachTags(["customer", "project"]);

        $this->assertCount(2, $issue->fresh()->tags);
    }

}
