<?php

namespace Bencavens\Instagram\Tests\Sandbox;

use Bencavens\Instagram\Http\CurlTransport;
use Bencavens\Instagram\Instagram;
use stdClass;

class YouTest extends \PHPUnit_Framework_TestCase
{
    private $instagram;

    public function setUp()
    {
        parent::setUp();

        // TODO: this access_token should be set outside the git!!
        $access_token = '1083340621.65988f8.5ab760b1e30547429add8d2d45401125';
        $client_id="65988f8cab504e6d9f2d4ffbadd14dc0";
        $client_secret="3f1064a50b844254aae45663369da971";
        $this->instagram = new Instagram(new CurlTransport(), $client_id, $client_secret, $access_token);
    }

    /** @test */
    public function it_can_get_your_profile()
    {
        $raw = $this->instagram->you()->raw();
        $profile = $raw->getData();

        $this->assertEquals(200,$raw->getStatusCode());
        $this->assertInstanceOf(stdClass::class, $profile);

        // It has a proper formatted response
        $this->assertNotEmpty($profile->profile_picture);
        $this->assertNotEmpty($profile->username);
        $this->assertNotEmpty($profile->id);
    }

    /** @test */
    public function it_can_get_your_likes()
    {
        $raw = $this->instagram->you()->likes()->raw();
        $likes = $raw->getData();

        $this->assertEquals(200,$raw->getStatusCode());
        $this->assertInternalType('array',$likes);

        // For this test we assume the sandbox has at least another user
        // active which has liked at least one media
        $liked = $likes[0];

        // It has a proper formatted response
        $this->assertInstanceOf(StdClass::class, $liked->user);
        $this->assertInstanceOf(StdClass::class, $liked->images);
        $this->assertInstanceOf(StdClass::class, $liked->caption);
        $this->assertInstanceOf(StdClass::class, $liked->likes);
        $this->assertInstanceOf(StdClass::class, $liked->comments);

    }
}