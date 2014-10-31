<?php
namespace Navy;

class Response
{
    protected $statusCode = 200;
    protected $content = '';

    // todo get lazy
    protected static $statusTexts = [
        200 => 'OK',
        301 => 'Moved Parmanently',
        302 => 'Found',
        303 => 'See Other',
        307 => 'Temporary Redirect',
        403 => 'Forbidden',
        404 => 'Not Foud',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
        503 => 'Service Unavailable',
    ];

    public function setStatusCode($statusCode)
    {
        $this->statusCode = (int) $statusCode;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setContent($content)
    {
        $this->content = (string) $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function send()
    {
        header(sprintf('HTTP/1.1 %s %s', $this->statusCode, self::getStatusText($this->statusCode)));

        echo $this->content;
    }

    protected static function getStatusText($statusCode)
    {
        return isset(static::$statusTexts[$statusCode]) ? static::$statusTexts[$statusCode] : 'unknown';
    }
}
