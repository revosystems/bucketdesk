<?php

namespace Tests\Feature;

use App\Issue;
use App\Jobs\InitializeRepo;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** @group cloud */
class InitializeRepoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_initialize_a_repo(){
        InitializeRepo::dispatch('revo-pos', 'revo-app');

        $this->assertTrue(User::count() > 2);
        $this->assertTrue(Issue::count() > 0);
    }
}
