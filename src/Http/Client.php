<?php

namespace Bencavens\Instagram\Http;

use Bencavens\Instagram\Exceptions\ExceptionResolver;
use Bencavens\Instagram\Exceptions\InstagramException;
use Exception;

class Client
{
    const ENDPOINT = 'https://api.instagram.com/v1';

    /**
     * @var TransportContract
     */
    private $client;

    private $client_secret;
    private $access_token;

    /**
     * request info for exception handling
     * e.g. GET /user/self/media
     * @var string
     */
    private $requestInfo;

    public function __construct(TransportContract $client, $client_secret, $access_token = null)
    {
        $this->client = $client;
        $this->client_secret = $client_secret;
        $this->access_token = $access_token;
    }

    /**
     * GET Request
     *
     * @param $endpoint
     * @param array $params
     * @param bool $auth
     * @return Response
     * @throws InstagramException
     */
    public function get($endpoint, array $params = [], $auth = true): Response
    {
        return $this->makeRequest('GET',$endpoint, $params, $auth);
    }

    public function post($endpoint, array $params = [], $auth = true): Response
    {
        return $this->makeRequest('POST',$endpoint, $params, $auth);
    }

    /**
     * @param $method
     * @param $endpoint
     * @param array $params
     * @param bool $auth
     * @return Response
     * @throws \Bencavens\Instagram\Exceptions\InstagramException
     */
    private function makeRequest($method, $endpoint, array $params = [], $auth = true)
    {
        $method = strtolower($method);
        $endpoint = rtrim($endpoint, '/'); // Valid signature disallows trailing slash
        $uri = 0 === strpos($endpoint,'http') ? $endpoint : static::ENDPOINT.$endpoint;

        // Keep requestInfo so our exceptions contain this info as well.
        $this->requestInfo = '['.strtoupper($method).' '.$endpoint.'] ';

        if($auth)
        {
            $uri = $uri.'?access_token='.$this->access_token;

            // We will always sign the request. This improves security
            // in case the developer has enforced signed requests in his app settings.
            // This will only work on valid endpoint requests, not on full uri (oauth)
            $uri .= '&sig='.$this->generateSignature($endpoint, array_merge($params,['access_token' => $this->access_token]), $this->client_secret);
        }

        try{
            $raw = $this->client->{$method}($uri,$params);

            $this->validateRawResponse($raw);

            return new Response($raw);
        }
        catch(InstagramException $e)
        {
            throw $e;
        }
        catch(Exception $e)
        {
            ExceptionResolver::resolve('InstagramException', $e->getCode(), $e->getMessage(), $this->requestInfo);
        }
    }

    /**
     * @param $raw
     * @throws InstagramException
     */
    private function validateRawResponse($raw)
    {
        if(!$raw)
        {
            ExceptionResolver::resolve('InstagramException',500,'Empty response due to unknown error or invalid request.', $this->requestInfo);
        }

        ExceptionResolver::resolveFromRawResponse($raw, $this->requestInfo);
    }

    /**
     * @param $endpoint
     * @param array $params
     * @param $client_secret
     * @return string
     */
    private static function generateSignature($endpoint, array $params = [], $client_secret)
    {
        $signature = $endpoint;

        ksort($params);
        foreach($params as $k => $v) $signature .= "|$k=$v";

        return hash_hmac('sha256', $signature, $client_secret, false);
    }
}