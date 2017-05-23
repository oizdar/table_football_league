<?php
namespace TableFootball\League\Exceptions;

class LeagueException extends \Exception
{
    protected $httpCode = 400;

    public function getHttpCode()
    {
        return $this->httpCode;
    }
}
