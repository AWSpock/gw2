<?php

require_once("/var/www/gw2/_php/models/Todo.php");
require_once("/var/www/gw2/_php/models/TodoSection.php");

class TodoRepository
{
    private $db;

    private $records = [];
    private $loaded = false;

    private $recordsSection = [];
    private $loadedSection = false;

    private $recordsCompleted = [];
    private $loadedCompleted = false;

    public $actionDataMessage;

    public function __construct(DatabaseV2 $db)
    {
        $this->db = $db;
    }

    // public function getRecordById($id)
    // {
    //     if (!array_key_exists($id, $this->records)) {
    //         $sql = "
    //             SELECT a.`id`, a.`created`, a.`updated`, a.`name`, a.`make`, a.`model`, a.`year`, a.`color`, a.`tank_capacity`, a.`purchase_date`, a.`sell_date`, a.`purchase_price`, a.`sell_price`, a.`purchase_odometer`, a.`sell_odometer`, if(isnull(b.`vehicle_id`),'No','Yes') AS `favorite`, ifnull(c.`role`,'Owner') AS `role`
    //             FROM vehicle a
    //                 LEFT OUTER JOIN vehicle_favorite b
    //                     ON a.`id` = b.`vehicle_id`
    //                     AND b.`userid` = ?
    //                 LEFT OUTER JOIN vehicle_share c
    //                     ON a.`id` = c.`vehicle_id`
    //                     AND c.`userid` = ?
    //             WHERE a.`id` = ? 
    //                 AND (
    //                     a.`userid` = ?
    //                     OR a.`id` IN (
    //                         SELECT `vehicle_id`
    //                         FROM vehicle_share
    //                         WHERE `userid` = ?
    //                     )
    //                 )
    //         ";

    //         $result = $this->db->query($sql, [
    //             $this->userid,
    //             $this->userid,
    //             $id,
    //             $this->userid,
    //             $this->userid,
    //         ], "iiiii");

    //         if ($result) {
    //             $rec = Vehicle::fromDatabase($result->fetch_array(MYSQLI_ASSOC));
    //             $this->records[$id] = $rec;
    //         } else {
    //             $this->records[$id] = null;
    //         }
    //     }
    //     return $this->records[$id];
    // }

    public function getTodoSections()
    {
        if ($this->loadedSection) {
            $recs = [];
            foreach ($this->recordsSection as $key => $rec) {
                if ($rec->id() > 0)
                    $recs[$key] = $rec;
            }
            return $recs;
        }

        $sql = "
            SELECT a.`id`, a.`created`, a.`updated`, a.`name`, a.`url`, a.`order`
            FROM todo_section a
            ORDER BY a.`order`
        ";

        // $result = $this->db->query($sql, [
        //     $this->userid,
        //     $this->userid,
        //     $this->userid,
        //     $this->userid
        // ], "iiii");

        $result = $this->db->query($sql);

        $this->loadedSection = true;
        $this->recordsSection = [];
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $rec) {
            $this->recordsSection[$rec['id']] = TodoSection::fromDatabase($rec);
        }
        return $this->recordsSection;
    }

    public function getTodos()
    {
        if ($this->loaded) {
            $recs = [];
            foreach ($this->records as $key => $rec) {
                if ($rec->id() > 0)
                    $recs[$key] = $rec;
            }
            return $recs;
        }

        $sql = "
            SELECT a.`id`, a.`created`, a.`updated`, a.`type`, b.`name` AS section, a.`name`, a.`identifier`, a.`order`
            FROM todo a
                INNER JOIN todo_section b
                    ON a.`todo_section_id` = b.`id`
            ORDER BY b.`order`, a.`order`
        ";

        // $result = $this->db->query($sql, [
        //     $this->userid,
        //     $this->userid,
        //     $this->userid,
        //     $this->userid
        // ], "iiii");

        $result = $this->db->query($sql);

        $this->loaded = true;
        $this->records = [];
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $rec) {
            $this->records[$rec['id']] = Todo::fromDatabase($rec);
        }
        return $this->records;
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
            SELECT b.`identifier`
            FROM todo_completion a
                INNER JOIN todo b ON a.`todo_id` = b.`id`
            WHERE a.`api_key` = ?
                AND a.`complete` = true
                AND (
                    (
                        b.`type` = 'daily'
                        AND a.created > TIMESTAMP(CURRENT_DATE)
                    )
                    OR (
                        b.`type` = 'weekly'
	                    AND a.created > TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), '7:30')
                    )
                )
            ORDER BY b.`identifier`
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
            FROM todo_completion a
                INNER JOIN todo b ON a.`todo_id` = b.`id`
            WHERE a.`api_key` = ?
                AND b.`identifier` = ?
                AND (
                    (
                        b.`type` = 'daily'
                        AND a.created > TIMESTAMP(CURRENT_DATE)
                    )
                    OR (
                        b.`type` = 'weekly'
	                    AND a.created > TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), '7:30')
                    )
                )
        ";

        $result = $this->db->query($sql, [
            $api_key,
            $identifier
        ], "ss");

        $id = $result->fetch_array(MYSQLI_ASSOC)['id'];

        $this->db->beginTransaction();
        if (!isset($id)) {
            $sql = "
                INSERT INTO todo_completion (api_key, todo_id)
                SELECT ? AS api_key, id
                FROM todo
                WHERE identifier = ?
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
                UPDATE todo_completion
                SET complete = !complete
                WHERE api_key = ?
                    AND todo_id = ( SELECT id FROM todo WHERE identifier = ? )
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

    // public function insertRecord(Vehicle $rec)
    // {
    //     $this->actionDataMessage = "Failed to insert Vehicle";

    //     if (empty($rec->name())) {
    //         $this->actionDataMessage = "Name is required to insert Vehicle";
    //         return 0;
    //     }

    //     $this->db->beginTransaction();

    //     $sql = "
    //         INSERT INTO vehicle (`name`,`make`,`model`,`year`,`color`,`tank_capacity`,`purchase_date`,`sell_date`,`purchase_price`,`sell_price`,`purchase_odometer`,`sell_odometer`,`userid`)
    //         VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)
    //     ";

    //     $result = $this->db->query($sql, [
    //         $rec->name(),
    //         $rec->make(),
    //         $rec->model(),
    //         $rec->year(),
    //         $rec->color(),
    //         $rec->tank_capacity(),
    //         $rec->purchase_date(),
    //         $rec->sell_date(),
    //         $rec->purchase_price(),
    //         $rec->sell_price(),
    //         $rec->purchase_odometer(),
    //         $rec->sell_odometer(),
    //         $this->userid
    //     ], "sssisdssddiii");

    //     if (is_int($result) && $result > 0) {
    //         $this->actionDataMessage = "Vehicle Inserted";
    //         $this->db->commit();
    //         return $result;
    //     }
    //     $this->db->rollback();
    //     return 0;
    // }

    // public function updateRecord(Vehicle $rec)
    // {
    //     $this->actionDataMessage = "Failed to update Vehicle";

    //     if (empty($rec->name())) {
    //         $this->actionDataMessage = "Name is required to update Vehicle";
    //         return 0;
    //     }

    //     $this->db->beginTransaction();

    //     $sql = "
    //         UPDATE vehicle 
    //         SET `name` = ?, 
    //             `make` = ?,
    //             `model` = ?,
    //             `year` = ?,
    //             `color` = ?,
    //             `tank_capacity` = ?,
    //             `purchase_date` = ?,
    //             `sell_date` = ?,
    //             `purchase_price` = ?,
    //             `sell_price` = ?,
    //             `purchase_odometer` = ?,
    //             `sell_odometer` = ?
    //         WHERE `id` = ? 
    //         AND `userid` = ?
    //     ";

    //     $result = $this->db->query($sql, [
    //         $rec->name(),
    //         $rec->make(),
    //         $rec->model(),
    //         $rec->year(),
    //         $rec->color(),
    //         $rec->tank_capacity(),
    //         $rec->purchase_date(),
    //         $rec->sell_date(),
    //         $rec->purchase_price(),
    //         $rec->sell_price(),
    //         $rec->purchase_odometer(),
    //         $rec->sell_odometer(),
    //         $rec->id(),
    //         $this->userid
    //     ], "sssisdssddiiii");

    //     if ($result !== false) {
    //         if ($result !== 1) {
    //             $this->actionDataMessage = "Vehicle Unchanged";
    //             return 2;
    //         }
    //         $this->actionDataMessage = "Vehicle Updated";
    //         $this->db->commit();
    //         return 1;
    //     }

    //     $this->db->rollback();
    //     return false;
    // }

    // public function deleteRecord(Vehicle $rec)
    // {
    //     $this->actionDataMessage = "Failed to delete Vehicle";

    //     $this->db->beginTransaction();

    //     $sql = "
    //         DELETE a, b, c, d, e
    //         FROM vehicle a
    //             LEFT OUTER JOIN vehicle_favorite b ON a.`id` = b.`vehicle_id`
    //             LEFT OUTER JOIN vehicle_share c ON a.`id` = b.`vehicle_id`
    //             LEFT OUTER JOIN fillup d ON a.`id` = d.`vehicle_id`
    //             LEFT OUTER JOIN maintenance e ON a.`id` = e.`vehicle_id`
    //         WHERE a.`id` = ? 
    //         AND a.`userid` = ?
    //     ";

    //     $result = $this->db->query($sql, [
    //         $rec->id(),
    //         $this->userid
    //     ], "ii");

    //     if (is_int($result) && $result > 0) {
    //         $this->actionDataMessage = "Vehicle Deleted";
    //         $this->db->commit();
    //         return 1;
    //     }
    //     $this->db->rollback();
    //     return 0;
    // }
}
