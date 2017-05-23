<?php
namespace TableFootball\League\Core;

class AbstractController
{
    /** @var Request  */
    protected $request;

    /** @var \PDO */
    protected $pdo;

    public function __construct()
    {
        $this->request = Request::getRequest();
        $this->pdo = DbProvider::getInstance()->getConnection();
    }
}
