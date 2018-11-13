<?php

namespace Tests\Feature;

use App\Issue;
use App\Jobs\InitializeRepo;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** @group cloud */
class BitbucketWebhookTest extends TestCase
{
    use RefreshDatabase;

   /** @test */
   public function it_can_crate_an_issue(){

   }

   /** @test */
   public function an_issue_already_created_is_just_updated_when_receiving_the_created_event(){

   }

   /** @test */
   public function an_issue_can_be_updated(){

   }
}
