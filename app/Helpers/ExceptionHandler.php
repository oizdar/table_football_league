<?php
namespace TableFootball\League\Helpers;

use TableFootball\League\Core\Response;
use TableFootball\League\Exceptions\LeagueException;

class ExceptionHandler
{
    public static function handler(\Throwable $exception)
    {
        if(!($exception instanceof \Exception)) {
            restore_exception_handler();
            throw $exception;
        }
        if($exception instanceof LeagueException) {
            $httpCode = $exception->getCode();
        } else {
            $httpCode = 500;
        }

        $content = ['error' => $exception->getMessage()];
        //Prepare code string:
        $class =  explode('\\', get_class($exception));
        $code =  preg_replace( '/([a-z0-9])([A-Z])/', "$1_$2", end($class));

        echo new Response($httpCode, $content, $code);
    }
}
