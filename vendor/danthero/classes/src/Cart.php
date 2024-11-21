<?php

namespace Danthero;

use \Danthero\DB\Sql;
use \Danthero\Model;
use \Danthero\Products;

class Cart extends Model {

    const SESSION = "Cart";
    const SESSION_ERROR = "CartError";

    public static function getFromSession()
    {
 

        $cart = new Cart();
 
        if (isset($_SESSION[Cart::SESSION]) && (int)$_SESSION[Cart::SESSION]['idcart'] > 0) {
            $cart->get((int)$_SESSION[Cart::SESSION]['idcart']);
        } else {
            $cart->getFromSessionID();

 
            if (!(int)$cart->getidcart() > 0) {
                $data = [
                    'sessionid' => session_id()
                ];

                $cart->setData($data);
                $cart->save();
                $cart->setToSession();
            }
        }

        return $cart;
    }

    public function setToSession()
    {
        $_SESSION[Cart::SESSION] = $this->getValues();
    }

    public function get(int $idcart)
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM carts WHERE idcart = :idcart", [
            ':idcart' => $idcart
        ]);

        if (count($results) > 0) {
            $this->setData($results[0]);
        }
    }

    public function getFromSessionID()
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM carts WHERE sessionid = :sessionid", [
            ':sessionid' => session_id()
        ]);

        if (count($results) > 0) {
            $this->setData($results[0]);
        }
    }

    public function save()
    {
        $sql = new Sql();
    
        $sql->query("INSERT INTO carts (sessionid) VALUES (:SESSIONID)", [
            ':SESSIONID' => $this->getsessionid()
        ]);
    
        $results = $sql->select("SELECT last_insert_rowid() AS id");
        
        if (isset($results[0]['id'])) {
            $this->setidcart($results[0]['id']);
        } else {
            throw new Exception("Falhou em recuperar o id.");
        }

    }

    public function addProduct(Products $product, int $quantity = 1)
    {
        $sql = new Sql();

        $idproduct = $product->getid();
        $price = $product->getprice();

 
        $results = $sql->select("SELECT * FROM cart_products WHERE idcart = :idcart AND idproduct = :idproduct", [
            ':idcart' => $this->getidcart(),
            ':idproduct' => $idproduct
        ]);

        if (count($results) > 0) {
 
            $sql->query("
                UPDATE cart_products 
                SET quantity = quantity + :quantity, price = price + :price 
                WHERE idcart = :idcart AND idproduct = :idproduct", [
                ':quantity' => $quantity,
                ':price' => $price * $quantity,  
                ':idcart' => $this->getidcart(),
                ':idproduct' => $idproduct
            ]);
        } else {
 
            $sql->query("
                INSERT INTO cart_products (idcart, idproduct, quantity, price) 
                VALUES (:idcart, :idproduct, :quantity, :price)", [
                ':idcart' => $this->getidcart(),
                ':idproduct' => $idproduct,
                ':quantity' => $quantity,
                ':price' => $price * $quantity  
            ]);
        }
    }

    public function removeProduct(int $idproduct)
    {
        $sql = new Sql();

        $results = $sql->select("
            SELECT quantity, price 
            FROM cart_products 
            WHERE idcart = :idcart AND idproduct = :idproduct", [
            ':idcart' => $this->getidcart(),
            ':idproduct' => $idproduct
        ]);

        if (count($results) > 0) {
            $current = $results[0];
            $currentQuantity = $current['quantity'];
            $currentPrice = $current['price'];

            if ($currentQuantity > 1) {
                $newQuantity = $currentQuantity - 1;
                $newPrice = $currentPrice - ($currentPrice / $currentQuantity);

                $sql->query("
                    UPDATE cart_products 
                    SET quantity = :quantity, price = :price 
                    WHERE idcart = :idcart AND idproduct = :idproduct", [
                    ':quantity' => $newQuantity,
                    ':price' => $newPrice,
                    ':idcart' => $this->getidcart(),
                    ':idproduct' => $idproduct
                ]);
            } else {
                $this->removeProductCompletely($idproduct);
            }
        }
    }

    public function removeProductCompletely($idproduct)
    {
        $sql = new Sql();

        var_dump($idproduct);
        
        $sql->query("
            DELETE FROM cart_products 
            WHERE idcart = :idcart AND idproduct = :idproduct", [
            ':idcart' => $this->getidcart(),
            ':idproduct' => $idproduct
        ]);
    }

    public function getProducts()
    {
        $sql = new Sql();
    
        $results = $sql->select("
            SELECT 
                p.id,
                p.name,
                p.description,
                p.price,
                cp.idcart_product,
                cp.quantity,
                cp.price AS subtotal_price,
                (SELECT SUM(cp_inner.price) 
                 FROM cart_products cp_inner 
                 WHERE cp_inner.idcart = :idcart) AS total_cart_price
            FROM 
                cart_products cp
            JOIN 
                products p ON cp.idproduct = p.id
            WHERE 
                cp.idcart = :idcart
        ", [
            ':idcart' => $this->getidcart()
        ]);
    
        return $results;
    }


}
    

?>