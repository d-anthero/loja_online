<?php

namespace Danthero;

use Danthero\DB\Sql;
use \Danthero\Model;

class Products extends Model{
    public static function listAll()
    {
        $sql = new Sql();
        return $sql->select("SELECT * FROM products ORDER BY name");
    }

    public function save()
    {
        $sql = new Sql();
        $results = $sql->select("INSERT INTO products (name, description, price) VALUES (:NAME, :DESCRIPTION, :PRICE)", array(
            ":NAME"=>$this->getname(),
            ":DESCRIPTION"=>$this->getdescription(),
            ":PRICE"=>$this->getprice()
        ));

       
    }

    public function get($id)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM products WHERE id = :ID", array(
            ":ID"=>$id
        ));

        if (count($results) > 0) {
            $this->setData($results[0]);
        }
    }

    public function update()
    {
        $sql = new Sql();
        $sql->query("UPDATE products SET name = :NAME, description = :DESCRIPTION, price = :PRICE WHERE id = :ID", array(
            ":NAME"=>$this->getname(),
            ":DESCRIPTION"=>$this->getdescription(),
            ":PRICE"=>$this->getprice(),
            ":ID"=>$this->getid()
        ));
    }

    public function delete()
    {
        $sql = new Sql();
        $sql->query("DELETE FROM products WHERE id = :ID", array(
            ":ID"=>$this->getid()
        ));
    }

}





?>