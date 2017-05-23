<?php
namespace TableFootball\League\Core;

class Response
{
    protected $headers = [
       'Content-Type' => 'application/json, charset=UTF-8'
    ];

    protected $content = [
        'code' => 'OK',
        'data' => null
    ];

    protected $httpCode;

    public function __construct(int $httpCode, $data = null, $code = null)
    {
        if(isset($data)) {
            $this->setContentData($data);
        }

        if(isset($code)) {
            $this->setContentCode($code);
        }

        $this->setHttpCode($httpCode);
    }

    public function setContentData($data)
    {
        $this->content['data'] = $data;
    }

    public function setContentCode(string $code)
    {
        $this->content['code'] = strtoupper($code);
    }

    public function setHttpCode(int $httpCode)
    {
        $this->httpCode = $httpCode;
    }

    public function __toString() : string
    {
        foreach($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }
        http_response_code($this->httpCode);

        return json_encode($this->content);
    }
}
