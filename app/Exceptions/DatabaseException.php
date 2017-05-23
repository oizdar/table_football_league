<?php
namespace TableFootball\League\Exceptions;

class DatabaseException extends LeagueException
{
    protected $httpCode = 400;
}
