<?php

namespace Bencavens\Instagram;

use Bencavens\Instagram\Exceptions\OAuthException;
use Bencavens\Instagram\Http\Client;

class OAuth
{
    const OAUTH_URL = 'https://api.instagram.com/oauth/authorize';
    const OAUTH_TOKEN_URL = 'https://api.instagram.com/oauth/access_token';

    /**
     * @var Client
     */
    private $client;

    private $client_id;
    private $client_secret;
    private $redirect_uri;

    public function __construct(Client $client, $client_id, $client_secret, $redirect_uri)
    {
        $this->client = $client;

        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
    }

    /**
     * Url that points to the Instagram authorization page.
     * This is the endpoint where the user has to confirm
     * your application.
     *
     * @param array $scopes
     * @param null $state
     * @return string
     */
    public function getAuthorizationUrl($scopes = ['basic'], $state = null)
    {
        $scopes = !empty($scopes) ? implode(' ',(array)$scopes) : null;

        return self::OAUTH_URL.'?'.http_build_query([
            'client_id'     => $this->client_id,
            'redirect_uri'  => $this->redirect_uri,
            'response_type' => 'code',
            'scope'         => $scopes,
            'state'         => $state
        ]);
    }

    /**
     * Retrieve the access token.
     * This token authorizes your app and indicates
     * the validity of the current session.
     *
     * @param $code - code received via the authorization callback
     * @return mixed
     * @throws OAuthException
     */
    public function getAccessToken($code)
    {
        $response = $this->client->post(self::OAUTH_TOKEN_URL,[
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $this->redirect_uri,
            'code'          => $code,
        ],false);

        return $response->getRaw()->access_token;
    }
}