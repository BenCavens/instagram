<?php

namespace Bencavens\Instagram\Http;

interface TransportContract
{
    public function post($uri, array $data = []);

    public function get($uri, array $data = []);
}