<?php

require_once("/var/www/gw2/_php/models/PWTEItem.php");
require_once("/var/www/gw2/_php/models/PWTESection.php");

class PWTERepository
{
    private $db;

    private $records = [];
    private $loaded = false;

    private $recordsSection = [];
    private $loadedSection = false;

    public $actionDataMessage;

    public function __construct(DatabaseV2 $db)
    {
        $this->db = $db;
    }

    public function getSections()
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
            FROM pwte_section a
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
            $this->recordsSection[$rec['id']] = PWTESection::fromDatabase($rec);
        }
        return $this->recordsSection;
    }

    public function getItems()
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
            SELECT a.`id`, a.`created`, a.`updated`, b.`name` AS section, a.`api_id`, a.`order`
            FROM pwte_item a
                INNER JOIN pwte_section b
                    ON a.`section_id` = b.`id`
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
            $this->records[$rec['id']] = PWTEItem::fromDatabase($rec);
        }
        return $this->records;
    }
}
