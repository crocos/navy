<?php

namespace Navy;

class Request
{
    const CONTENT_TYPE_JSON = 'application/json';
    const CONTENT_TYPE_FORM = 'application/x-www-form-urlencoded';

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    protected $method;

    protected $event;
    protected $delivery;
    protected $payload;

    public function __construct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->receivePost();
        } else {
            $this->receiveGet();
        }
    }

    protected function receiveGet()
    {
        $this->method = static::METHOD_GET;

        if (empty($_SERVER['REQUEST_URI'])) {
            throw new \RuntimeException('cannot get request_uri');
        }

        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parsedPath = array_filter(explode("/", $requestPath));

        $this->event = strtolower(array_shift($parsedPath));
        $this->payload = $_GET;
    }

    protected function receivePost()
    {
        $this->method = static::METHOD_POST;
        $this->event = $_SERVER['HTTP_X_GITHUB_EVENT'];
        $this->delivery = $_SERVER['HTTP_X_GITHUB_DELIVERY'];

        if (isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];
        } elseif (isset($_SERVER['HTTP_CONTENT_TYPE'])) {
            $contentType = $_SERVER['HTTP_CONTENT_TYPE'];
        } else {
            throw new \RuntimeException('not defined content-type');
        }
        $this->payload = $this->fetchPayload($contentType);
    }

    protected function fetchPayload($contentType)
    {
        switch ($contentType) {
        case static::CONTENT_TYPE_FORM:
            if (isset($_POST['payload'])) {
                $payload = $_POST['payload'];
            }
            break;
        case static::CONTENT_TYPE_JSON:
            $payload = fread(STDIN);
            break;
        default:
            break;
        }

        return json_decode($payload, true);
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function getDelivery()
    {
        return $this->delivery;
    }

    public function getPayload()
    {
        return $this->payload;
    }
}
