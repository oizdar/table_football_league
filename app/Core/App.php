<?php

namespace TableFootball\League\Core;

class App
{
    protected $request;

    public function __construct()
    {
        $this->request = Request::getRequest();
    }

    public function execute()
    {
        return new Response(200, 'test', 'OK');
    }

}
