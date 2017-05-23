<?php
namespace TableFootball\League\Exceptions;

class AppException extends LeagueException
{
    protected $httpCode = 400;
}
