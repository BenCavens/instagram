<?php

namespace Bencavens\Instagram\Tests\Unit;

use Bencavens\Instagram\Endpoints\You;
use Bencavens\Instagram\Http\Response;
use Bencavens\Instagram\Tests\TestCase;

class YouTest extends TestCase
{
    private $you;

    public function setUp()
    {
        parent::setUp();

        $this->you = new You($this->clientMock);
        // Test correct request and params
        // Test proper auth => access_token given along
        // Test signature...
    }

    /** @test */
    public function it_can_get_your_profile()
    {
        $this->assertMockedRequest('/users/self/');

        $this->you->get();
        $this->you->first();
    }

    /** @test */
    public function it_can_get_your_recent_media()
    {
        $this->assertMockedRequest('/users/self/media/recent');

        $this->you->media()->get();
        $this->you->media()->first();
    }

    /** @test */
    public function it_can_get_your_recent_media_with_limit()
    {
        $this->assertMockedRequest('/users/self/media/recent','get',['count' => 3],3);

        $this->you->media()->limit(3)->raw();
        $this->you->media()->limit(3)->get();
        $this->you->media()->limit(3)->first();
    }

    /** @test */
    public function it_can_get_your_recent_likes()
    {
        $this->assertMockedRequest('/users/self/media/liked','get',['count' => 3]);

        $this->you->likes()->limit(3)->get();
        $this->you->likes()->limit(3)->first();
    }

    /** @test */
    public function it_can_get_your_recent_follows()
    {
        $this->assertMockedRequest('/users/self/follows','get',['count' => 25]);

        $this->you->follows()->limit(25)->get();
        $this->you->follows()->limit(25)->first();
    }

    /** @test */
    public function it_can_get_your_recent_followed_by()
    {
        $this->assertMockedRequest('/users/self/followed-by','get',['count' => 25]);

        $this->you->followedBy()->limit(25)->get();
        $this->you->followedBy()->limit(25)->first();
    }

    /** @test */
    public function it_can_get_your_recent_requested_by()
    {
        $this->assertMockedRequest('/users/self/requested-by','get',['count' => 25]);

        $this->you->requestedBy()->limit(25)->get();
        $this->you->requestedBy()->limit(25)->first();
    }
}