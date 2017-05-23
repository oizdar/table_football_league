<?php

namespace TableFootball\League\Core;

class App
{
    protected $request;

    public function __construct()
    {
        $this->request = Request::getRequest();
    }

}
