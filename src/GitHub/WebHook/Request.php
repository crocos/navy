<?php

namespace Navy\GitHub\WebHook;

class Request
{
    const USER_AGENT = 'User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)';
    const BASIC_AUTH_FORMAT = 'Authorization: Basic %s:x-oauth-basic';

    protected $token;

    public function __construct($token = null)
    {
        $this->token = $token;
    }

    public function request($url)
    {
        $headers = $this->makeHeaders();
        $context = $this->makeStreamContext($headers);

        return file_get_contents($url, false, $context);
    }

    protected function makeHeaders()
    {
        $headers = [];

        array_push($headers, static::USER_AGENT);

        if (! is_null($this->token)) {
            array_push($headers, sprintf(static::BASIC_AUTH_FORMAT, base64_encode($this->token)));
        }

        return $headers;
    }

    protected function makeStreamContext(array $headers, $method = 'GET')
    {
        return stream_context_create(['http' => [
            'method' => $method,
            'header' => implode("\r\n", $headers),
            'ignore_errors' => true,
        ]]);
    }
}
