<?php

// require_once("/var/www/gw2/_php/models/Todo.php");
// require_once("/var/www/gw2/_php/models/TodoSection.php");

class EventRepository
{
    private $db;

    // private $records = [];
    // private $loaded = false;

    private $recordsCompleted = [];
    private $loadedCompleted = false;

    public $actionDataMessage;

    public function __construct(DatabaseV2 $db)
    {
        $this->db = $db;
    }

    public function getCompleted($api_key)
    {
        if ($this->loadedCompleted) {
            $recs = [];
            foreach ($this->recordsCompleted as $key => $rec) {
                if ($rec->id() > 0)
                    $recs[$key] = $rec;
            }
            return $recs;
        }

        $sql = "
            SELECT a.`identifier`
            FROM event_completion a
            WHERE a.`api_key` = ?
                AND a.`complete` = true
                AND a.created > TIMESTAMP(CURRENT_DATE)
            ORDER BY a.`identifier`
        ";

        $result = $this->db->query($sql, [
            $api_key
        ], "s");

        $this->loadedCompleted = true;
        $this->recordsCompleted = [];
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $rec) {
            array_push($this->recordsCompleted, $rec['identifier']);
        }
        return $this->recordsCompleted;
    }

    public function toggle($api_key, $identifier)
    {
        $sql = "
            SELECT a.`id`
            FROM event_completion a
            WHERE a.`api_key` = ?
                AND a.`identifier` = ?
                AND a.created > TIMESTAMP(CURRENT_DATE)
        ";

        $result = $this->db->query($sql, [
            $api_key,
            $identifier
        ], "ss");

        $id = $result->fetch_array(MYSQLI_ASSOC)['id'];

        $this->db->beginTransaction();
        if (!isset($id)) {
            $sql = "
                INSERT INTO event_completion (api_key, identifier)
                SELECT ? AS api_key, ? AS identifier
            ";
            $result = $this->db->query($sql, [
                $api_key,
                $identifier
            ], "ss");
            if (is_int($result) && $result > 0) {
                $this->db->commit();
                return true;
            }
        } else {
            $sql = "
                UPDATE event_completion
                SET complete = !complete
                WHERE api_key = ?
                    AND identifier = ?
            ";
            $result = $this->db->query($sql, [
                $api_key,
                $identifier
            ], "ss");
            if ($result !== false) {
                $this->db->commit();
                return true;
            }
        }
        $this->db->rollback();
        return false;
    }
}
