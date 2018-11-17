<?php

namespace Tests\Unit;

use App\SlackCommand;
use Tests\TestCase;

class SlackCommandTest extends TestCase
{
    /** @test */
    public function it_can_extract_tags_from_string()
    {
        $string = "#starts this is an #string with #tags in various #places";
//        $string = "a [#tag] subject [#tag2]";
        $tags = (new SlackCommand)->extractTags($string);
        $this->assertEquals(["starts", "string", "tags", "places"], $tags);
        $this->assertEquals("this is an with in various", $string);
    }
}