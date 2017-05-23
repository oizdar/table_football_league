<?php
namespace TableFootball\League\Exceptions;

class InvalidArgumentException extends LeagueException
{
    protected $httpCode = 400;
}
