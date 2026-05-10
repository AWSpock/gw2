<?php

class PWTESection
{
    protected $id;
    protected $created;
    protected $updated;

    protected $name;
    protected $url;
    protected $order;

    public function __construct($rec = null)
    {
        $this->id = -1;
        if ($rec !== null) {
            $this->id = (array_key_exists("id", $rec) && $rec['id'] !== NULL) ? $rec['id'] : -1;
            $this->created = (array_key_exists("created", $rec) && $rec['created'] !== NULL) ? $rec['created'] : null;
            $this->updated = (array_key_exists("updated", $rec) && $rec['updated'] !== NULL) ? $rec['updated'] : null;
            $this->name = (array_key_exists("name", $rec) && $rec['name'] !== NULL) ? $rec['name'] : null;
            $this->url = (array_key_exists("url", $rec) && $rec['url'] !== NULL) ? $rec['url'] : null;
            $this->order = (array_key_exists("order", $rec) && $rec['order'] !== NULL) ? $rec['order'] : null;
        }
    }

    // public static function fromPost($post)
    // {
    //     $rec1['id'] = !empty($post['vehicle_id']) ? $post['vehicle_id'] : -1;
    //     $rec1['name'] = $post['vehicle_name'];
    //     $rec1['make'] = $post['vehicle_make'];
    //     $rec1['model'] = $post['vehicle_model'];
    //     $rec1['year'] = $post['vehicle_year'];
    //     $rec1['color'] = $post['vehicle_color'];
    //     $rec1['tank_capacity'] = $post['vehicle_tank_capacity'];
    //     $rec1['purchase_date'] = $post['vehicle_purchase_date'];
    //     $rec1['sell_date'] = !empty($post['vehicle_sell_date']) ? $post['vehicle_sell_date'] : null;
    //     $rec1['purchase_price'] = $post['vehicle_purchase_price'];
    //     $rec1['sell_price'] = !empty($post['vehicle_sell_price']) ? $post['vehicle_sell_price'] : null;
    //     $rec1['purchase_odometer'] = $post['vehicle_purchase_odometer'];
    //     $rec1['sell_odometer'] = !empty($post['vehicle_sell_odometer']) ? $post['vehicle_sell_odometer'] : null;
    //     $new = new static($rec1);
    //     return $new;
    // }

    public static function fromDatabase($db)
    {
        $rec1['id'] = $db['id'];
        $rec1['created'] = $db['created'];
        $rec1['updated'] = $db['updated'];
        $rec1['name'] = $db['name'];
        $rec1['url'] = $db['url'];
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
    public function name()
    {
        return $this->name;
    }
    public function url()
    {
        return $this->url;
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
            "name" => $this->name(),
            "url" => $this->url(),
            "order" => $this->order()
        ];

        if ($pretty === true)
            return json_encode(get_object_vars($obj), JSON_PRETTY_PRINT);

        return json_encode(get_object_vars($obj));
    }
}
