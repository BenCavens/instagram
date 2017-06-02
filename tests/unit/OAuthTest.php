<?php

namespace Bencavens\Instagram\tests;

use Bencavens\Instagram\Http\Client;
use Bencavens\Instagram\Http\Response;
use Bencavens\Instagram\OAuth;
use Mockery;

class OAuthTest extends \PHPUnit_Framework_TestCase
{
    private $client;
    private $oauth;
    private $client_id;
    private $client_secret;
    private $redirect_uri;

    public function setUp()
    {
        parent::setUp();

        $this->createOAuth();
    }

    public function tearDown()
    {
        Mockery::close();
    }

    private function createOAuth()
    {
        $this->client_id = 'id';
        $this->client_secret = 'secret';
        $this->redirect_uri = 'example.com/callback';

        $this->client = Mockery::mock(Client::class);
        $this->oauth = new OAuth($this->client, $this->client_id, $this->client_secret, $this->redirect_uri);
    }

    private function getBaseAuthorizationUrl()
    {
        return 'https://api.instagram.com/oauth/authorize?client_id='.$this->client_id.'&redirect_uri='.urlencode($this->redirect_uri).'&response_type=code';
    }

    /** @test */
    public function it_can_construct_authorization_url_with_default_scope()
    {
        $this->assertEquals(
            $this->getBaseAuthorizationUrl().'&scope='.urlencode('basic'),
            $this->oauth->getAuthorizationUrl()
        );
    }

    /** @test */
    public function it_can_construct_authorization_url_without_scope()
    {
        $this->assertEquals(
            $this->getBaseAuthorizationUrl(),
            $this->oauth->getAuthorizationUrl(null)
        );
    }

    /** @test */
    public function it_can_construct_authorization_url_with_scope_as_array()
    {
        $this->assertEquals(
            $this->getBaseAuthorizationUrl().'&scope='.urlencode('basic likes'),
            $this->oauth->getAuthorizationUrl(['basic','likes'])
        );
    }

    /** @test */
    public function it_can_construct_authorization_url_with_scope_as_string()
    {
        $this->assertEquals(
            $this->getBaseAuthorizationUrl().'&scope='.urlencode('basic likes'),
            $this->oauth->getAuthorizationUrl('basic likes')
        );
    }

    /** @test */
    public function it_can_construct_authorization_url_with_state()
    {
        $this->assertEquals(
            $this->getBaseAuthorizationUrl().'&scope='.urlencode('basic public_profile').'&state='.urlencode('foo&bar'),
            $this->oauth->getAuthorizationUrl(['basic', 'public_profile'],'foo&bar')
        );
    }

    /** @test */
    public function it_can_construct_access_token_request()
    {
        $code = '123345';

        $this->client->shouldReceive('post')->withArgs([
            'https://api.instagram.com/oauth/access_token'
            ,[
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'grant_type'    => 'authorization_code',
                'redirect_uri'  => $this->redirect_uri,
                'code'          => $code,
            ],
            false
        ])->andReturn(new Response((object)['access_token' => 'foobar']));

        $this->oauth->getAccessToken($code);
    }

    /** @test */
    public function access_token_request_returns_token()
    {
        $this->client->shouldReceive('post')->andReturn(new Response((object)['access_token' => 'foobar']));

        $this->assertEquals('foobar',$this->oauth->getAccessToken('xxx'));
    }
}

