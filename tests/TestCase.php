<?php

namespace Bencavens\Instagram\Tests;

use Bencavens\Instagram\Http\Client;
use Bencavens\Instagram\Http\Response;
use PHPUnit_Framework_TestCase;
use Mockery;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    protected $clientMock;

    public function setUp()
    {
        parent::setUp();

        $this->clientMock = Mockery::mock(Client::class);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    protected function assertMockedRequest($endpoint, $method = 'get', $parameters = [], $times = 2)
    {
        $this->clientMock->shouldReceive($method)->times($times)->withArgs([
            $endpoint,
            $parameters,
            true
        ])->andReturn(new Response((object)(['data' => 'foobar'])));
    }
}