<?php
require_once("/var/www/gw2/_php/DataAccess/Database.php");
require_once("/var/www/gw2/_php/DataAccess/Repositories/TodoRepository.php");

class DataAccess
{
    private $db;
    private $todoRepository = [];

    public function __construct(mysqli $db = null)
    {
        $this->db = $db ?? new DatabaseV2();
    }

    public function todos()
    {
        if (!array_key_exists(0, $this->todoRepository)) {
            $this->todoRepository[0] = new TodoRepository($this->db);
        }
        return $this->todoRepository[0];
    }

    //

    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }
    public function commit()
    {
        $this->db->commit();
    }
    public function rollback()
    {
        $this->db->rollback();
    }
    public function close()
    {
        $this->db->close();
    }
    public function getDb()
    {
        return $this->db;
    }
}
