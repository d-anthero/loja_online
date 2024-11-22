<?php

use PHPUnit\Framework\TestCase;
use Danthero\Cart;
use Danthero\Products;

class CartTest extends TestCase
{
    private $cart;

    protected function setUp(): void
    {
        session_start(); // Required to test session-based functionality
        $this->cart = new Cart();
        $this->cart->setData(['sessionid' => session_id()]);
        $this->cart->save(); // Ensure we have a cart in the database
    }

    public function testSave()
    {
        $this->assertNotNull($this->cart->getidcart(), "Cart ID should be set after saving.");
    }

    public function testAddProduct()
    {
        $product = new Products();
        $product->setData(['id' => 1, 'name' => 'Test Product', 'description' => 'Description', 'price' => 10.00]);

        // Add the product
        $this->cart->addProduct($product);

        // Verify the product is in the cart
        $products = $this->cart->getProducts();
        $this->assertCount(1, $products, "Cart should contain one product.");
        $this->assertEquals(10.00, $products[0]['subtotal_price'], "Product subtotal should be 10.00.");
    }

    public function testRemoveProduct()
    {
        $product = new Products();
        $product->setData(['id' => 1, 'name' => 'Test Product', 'description' => 'Description', 'price' => 10.00]);

        // Add the product
        $this->cart->addProduct($product, 2);

        // Remove one quantity
        $this->cart->removeProduct(1);

        // Verify the remaining quantity
        $products = $this->cart->getProducts();
        $this->assertEquals(1, $products[0]['quantity'], "Quantity should be reduced to 1.");

        // Remove the last quantity
        $this->cart->removeProduct(1);

        // Verify the product is removed
        $products = $this->cart->getProducts();
        $this->assertCount(0, $products, "Cart should be empty after removing the last product.");
    }

    public function testGetProducts()
    {
        $product = new Products();
        $product->setData(['id' => 1, 'name' => 'Test Product', 'description' => 'Description', 'price' => 10.00]);

        // Add multiple products
        $this->cart->addProduct($product, 3);

        $products = $this->cart->getProducts();

        $this->assertCount(1, $products, "Cart should contain one product.");
        $this->assertEquals(3, $products[0]['quantity'], "Quantity should be 3.");
        $this->assertEquals(30.00, $products[0]['subtotal_price'], "Subtotal should be 30.00.");
    }

    public function testGetFromSession()
    {
        $retrievedCart = Cart::getFromSession();
        $this->assertEquals($this->cart->getidcart(), $retrievedCart->getidcart(), "Retrieved cart ID should match the current cart ID.");
    }
}