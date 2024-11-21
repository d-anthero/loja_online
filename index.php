<?php 
session_start();
require_once("vendor/autoload.php");

use \Danthero\Page;
use \Danthero\Products;
use \Danthero\Cart;

$app = new \Slim\Slim();

$app->config('debug', true);

//index
$app->get('/', function(){
    $page = new Page();

    $products = Products::listAll();

    $page->setTpl("product-list", array(
        "products"=>$products
    ));
});

//criar produto
$app->get('/product/create', function(){
    $page = new Page();

    $page->setTpl("product-create");
});

//deletar produto
$app->get("/product/:id/delete", function($id){
    $product = new Products();
    $product->get((int)$id);
    $product->delete();

    header("Location: /index.php");
    exit;
});

//editar produto
$app->get('/product/update/:id', function($id){
    $page = new Page();

    $product = new Products();
    $product->get((int)$id);

    $page->setTpl("product-update", array(
        "product"=>$product->getValues()
    ));
});

//salvar o produto criado
$app->post("/product/create", function(){
    $product = new Products();

    $product->setData($_POST);
    $product->save();

    header("Location: /index.php");
    exit;

});

//salvar o produto editado
$app->post("/product/update/:id", function($id){
    $product = new Products();
    $product->get((int)$id);
    $product->setData($_POST);
    $product->update();

    header("Location: /index.php");
    exit;
});

$app->get("/product/cart", function(){
    $cart = Cart::getFromSession();
    $page = new Page();

    $page->setTpl("cart", [ 'cart' => $cart->getValues(),
'products' => $cart->getProducts() ]);
});

$app->get("/cart/:id/add", function($id){
    $product = new Products();
    $product->get((int)$id);
    $cart = Cart::getFromSession();
    $cart->addProduct($product);

    header("Location: /index.php");
    exit;
});

$app->get("/cart/:id/minus", function($id){
    $product = new Products();
    $product->get((int)$id);
    $cart = Cart::getFromSession();
    $cart->removeProduct($product);

    header("Location: /product/cart");
    exit;
});

$app->get("/cart/:id/remove", function($id){
    $product = new Products();
    $product->get((int)$id);
    $cart = Cart::getFromSession();
    $cart->removeProduct($product, true);

    header("Location: /product/cart");
    exit;
});

$app->run();

 ?>