<?php
namespace TableFootball\League\Services;

use TableFootball\League\Core\DbProvider;
use TableFootball\League\Exceptions\DatabaseException;

class LeaguesService
{
    /** @var \PDO */
    protected $db;

    public function __construct()
    {
        $this->db = DbProvider::getInstance()->getConnection();
    }

    public function addLeague(string $name, string $description) : int
    {
        $sql = 'INSERT INTO `leagues`
            SET `name` = :name, `description` = :description';

        $stmt = $this->db->prepare($sql);
        if(!$stmt->execute(['name' => $name, 'description' => $description ])) {
            throw new DatabaseException('Database error occurred, try again later. If error repeats contact administrator.');
        };
        return $this->db->lastInsertId();
    }

    public function getAll()
    {
        $sql = 'SELECT * FROM `leagues`';
        $stmt = $this->db->prepare($sql);
        if(!$stmt->execute()) {
            throw new DatabaseException('Database error occurred, try again later. If error repeats contact administrator.');
        };
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

}
