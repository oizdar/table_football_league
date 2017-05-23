<?php

namespace TableFootball\League\Controllers;

use TableFootball\League\Core\Response;

class Leagues
{
    public function getList()
    {
        return new Response(200, 'success');
    }
}
