<?php
namespace TableFootball\League\Core;

class AbstractController
{
    /** @var Request  */
    protected $request;

    public function __construct()
    {
        $this->request = Request::getRequest();
    }
}
