<?php
namespace TableFootball\League\Exceptions;

class RouteNotFoundException extends LeagueException
{
    protected $httpCode = 404;
}
