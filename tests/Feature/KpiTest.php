<?php

namespace Tests\Feature;

use App\Issue;
use App\IssueKpi;
use App\IssueTrackers\Bitbucket\Bitbucket;
use App\IssueTrackers\Bitbucket\FakeBitbucket;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class KpiTest extends TestCase
{
    use RefreshDatabase;

    private $fakeBitbucket; // To avoid bitbucket sending

    public function setUp()
    {
        parent::setUp();
        $this->fakeBitbucket = new FakeBitbucket;
        $this->app->instance(Bitbucket::class, $this->fakeBitbucket);
        Notification::fake();
    }

    /** @test */
    public function it_stores_the_new_issues_kpi()
    {
        factory(Issue::class, 3)->create();
        Carbon::setTestNow(Carbon::yesterday());
        factory(Issue::class, 2)->create();
        Carbon::setTestNow();

        $this->assertEquals(2, IssueKpi::count());
        tap (IssueKpi::first(), function($kpi) {
            $this->assertEquals(IssueKpi::NEW, $kpi->type);
            $this->assertEquals(3, $kpi->value);
            $this->assertEquals(Carbon::now()->toDateString(), $kpi->date->toDateString());
        });
        tap (IssueKpi::orderBy('id','desc')->first(), function($kpi) {
            $this->assertEquals(IssueKpi::NEW, $kpi->type);
            $this->assertEquals(2, $kpi->value);
            $this->assertEquals(Carbon::yesterday()->toDateString(), $kpi->date->toDateString());
        });
    }

    /** @test */
    public function it_stores_the_resolved_issues_kpi()
    {
        $issues = factory(Issue::class, 4)->create();
        $issues[0]->update(['status' => Issue::STATUS_RESOLVED]);
        $issues[1]->update(['status' => Issue::STATUS_OPEN]);
        $issues[2]->update(['status' => Issue::STATUS_RESOLVED]);
        $issues[3]->update(['status' => Issue::STATUS_HOLD]);
        $issues[3]->update(['status' => Issue::STATUS_RESOLVED, 'username' => 'BadChoice']);

        $this->assertEquals(3, IssueKpi::count());  //1 for new and 1 for resolved

        tap (IssueKpi::orderBy('id','desc')->get()[1], function($kpi) {
            $this->assertEquals(IssueKpi::RESOLVED, $kpi->type);
            $this->assertEquals(2, $kpi->value);
            $this->assertEquals(Carbon::now()->toDateString(), $kpi->date->toDateString());
        });
    }

    /** @test */
    public function a_simple_update_does_not_increment_resolved_kpi()
    {
        $issues = factory(Issue::class, 4)->create(["status" => Issue::STATUS_RESOLVED]);
        $issues[1]->update(['status' => Issue::STATUS_OPEN]);
        $issues[2]->update(['status' => Issue::STATUS_RESOLVED]);
        $this->assertEquals(1, IssueKpi::count());  //1 for new and 1 for resolved
    }

}