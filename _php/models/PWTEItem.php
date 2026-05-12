<?php

class PWTEItem
{
    protected $id;
    protected $created;
    protected $updated;

    protected $section;
    protected $api_id;
    protected $type;
    protected $order;

    public function __construct($rec = null)
    {
        $this->id = -1;
        if ($rec !== null) {
            $this->id = (array_key_exists("id", $rec) && $rec['id'] !== NULL) ? $rec['id'] : -1;
            $this->created = (array_key_exists("created", $rec) && $rec['created'] !== NULL) ? $rec['created'] : null;
            $this->updated = (array_key_exists("updated", $rec) && $rec['updated'] !== NULL) ? $rec['updated'] : null;
            $this->section = (array_key_exists("section", $rec) && $rec['section'] !== NULL) ? $rec['section'] : null;
            $this->api_id = (array_key_exists("api_id", $rec) && $rec['api_id'] !== NULL) ? $rec['api_id'] : null;
            $this->type = (array_key_exists("type", $rec) && $rec['type'] !== NULL) ? $rec['type'] : null;
            $this->order = (array_key_exists("order", $rec) && $rec['order'] !== NULL) ? $rec['order'] : null;
        }
    }
    public static function fromDatabase($db)
    {
        $rec1['id'] = $db['id'];
        $rec1['created'] = $db['created'];
        $rec1['updated'] = $db['updated'];
        $rec1['section'] = $db['section'];
        $rec1['api_id'] = $db['api_id'];
        $rec1['type'] = $db['type'];
        $rec1['order'] = $db['order'];
        $new = new static($rec1);
        return $new;
    }

    public function id()
    {
        return intval($this->id);
    }
    public function created()
    {
        return $this->created;
    }
    public function updated()
    {
        return $this->updated;
    }
    public function section()
    {
        return $this->section;
    }
    public function api_id()
    {
        return $this->api_id;
    }
    public function type()
    {
        return $this->type;
    }
    public function order()
    {
        return ($this->order === NULL) ? null : intval($this->order);
    }

    public function toString($pretty = false)
    {
        $obj = (object) [
            "id" => $this->id(),
            "created" => $this->created(),
            "updated" => $this->updated(),
            "section" => $this->section(),
            "api_id" => $this->api_id(),
            "type" => $this->type(),
            "order" => $this->order()
        ];

        if ($pretty === true)
            return json_encode(get_object_vars($obj), JSON_PRETTY_PRINT);

        return json_encode(get_object_vars($obj));
    }
}
