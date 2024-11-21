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

        // Check if the cart exists in the session
        if (isset($_SESSION[Cart::SESSION]) && (int)$_SESSION[Cart::SESSION]['idcart'] > 0) {
            $cart->get((int)$_SESSION[Cart::SESSION]['idcart']);
        } else {
            $cart->getFromSessionID();

            // If no cart exists, create a new one
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
    public function addProduct(Products $product)
	{

		$sql = new Sql();

		$sql->query("INSERT INTO cart_products (idcart, idproduct) VALUES(:idcart, :idproduct)", [
			':idcart' => $this->getidcart(),
			':idproduct' => $product->getid()
		]);

	}

    public function removeProduct($idproduct)
    {
        $sql = new Sql();

        $sql->query("DELETE FROM cart_products WHERE idcart = :idcart AND idproduct = :idproduct", [
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
                cp.idcart_product
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