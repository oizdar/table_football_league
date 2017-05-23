<?php

namespace TableFootball\League\Controllers;

use TableFootball\League\Core\AbstractController;
use TableFootball\League\Core\Response;

class Leagues extends AbstractController
{
    public function getList()
    {
        return new Response(200, 'success');
    }
}
