<?php
use TableFootball\League\Core\App;


include(__DIR__ . '/autoload.php');

// Check is development mode enabled
$dev = filter_var(getenv('DEVELOPMENT'), FILTER_VALIDATE_BOOLEAN);
if($dev) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

set_exception_handler(['TableFootball\League\Helper\ExceptionHandler', 'handler']);

$app = new App();
echo $app->execute();

