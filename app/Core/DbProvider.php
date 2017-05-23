<?php
namespace TableFootball\League\Core;

class DbProvider
{
    protected static $instance;

    /** @var  \PDO */
    protected $connection;

    protected $host;
    protected $dbName;
    protected $username;
    protected $password;

    protected function __construct()
    {
        $this->host = getenv('DB_HOST');
        $this->dbName = getenv('DB_NAME');
        $this->username = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');
    }

    public static function getInstance()
    {
        if(!self::$instance) {
            self::$instance = new DbProvider();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        if(!$this->connection) {
            $dsn = sprintf('mysql:host=%s;dbname=%s', $this->host, $this->dbName);
            $this->connection = new \PDO($dsn, $this->username, $this->password);
        }
        return $this->connection;
    }
}
