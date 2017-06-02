<?php

namespace Bencavens\Instagram\Http;

use Bencavens\Instagram\Exceptions\InstagramException;

class CurlTransport implements TransportContract
{
    public function get($uri, array $data = [])
    {
        $uri = $this->appendQuery($uri, $data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, true);

        // DEBUG ERROR TRACK
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        return $this->execute($ch, $uri);
    }


    public function post($uri, array $data = [])
    {
        $payload = null;

        if(!empty($data))
        {
            $payload = http_build_query($data);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, true);

        // DEBUG ERROR TRACK
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

         return $this->execute($ch, $uri);
    }

    private function execute($ch, $uri)
    {
        $result = curl_exec($ch);

//        var_dump(curl_getinfo($ch, CURLINFO_HEADER_OUT));
//        var_dump($result);
        if(curl_error($ch))
        {
            throw new InstagramException('Failed request: '.$uri.' Curl error: '.curl_error($ch));
        }

        list($header,$body) = explode("\r\n\r\n", $result, 2);

        curl_close($ch);

        return json_decode($body);
    }

    private function appendQuery($uri, array $data = [])
    {
        if(empty($data)) return $uri;

        $parsed = parse_url($uri);
        $query = http_build_query($data);

        return (isset($parsed['query'])) ? $uri.'&'.$query : $uri.'?'.$query;
    }
}