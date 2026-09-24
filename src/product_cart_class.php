<?php
// Note: session_start() is handled by init_cart.php
// This file only contains class definitions

class Product {
    private $product_id;
    private $name;
    private $colour;
    private $style;
    private $country;
    private $price;
    private $qty;
    private $image;
    private $label;

    function __construct($product_id, $name, $colour, $style, $country, $price, $qty = 1, $image = "", $label = "") {
        $this->product_id = $product_id;
        $this->name = $name;
        $this->colour = $colour;
        $this->style = $style;
        $this->country = $country;
        $this->price = $price;
        $this->qty = $qty;
        $this->image = $image;
        $this->label = $label;
    }

    // --- Getters ---
    function get_id() { return $this->product_id; }
    function get_name() { return $this->name; }
    function get_colour() { return $this->colour; }
    function get_style() { return $this->style; }
    function get_country() { return $this->country; }
    function get_price() { return $this->price; }
    function get_qty() { return $this->qty; }
    function get_image() { return $this->image; }
    function get_label() { return $this->label; }

    // --- Setters ---
    function set_qty($qty) { 
        if ($qty > 0) $this->qty = $qty; 
    }

    function increase_qty($amount = 1) {
        $this->qty += $amount;
    }

    function decrease_qty($amount = 1) {
        if ($this->qty - $amount > 0) {
            $this->qty -= $amount;
        }
    }

    // --- Business Logic ---
    function get_total_cost() {
        return $this->qty * $this->price;
    }
}

class Cart {
    private $products; // key = product_id, value = Product object

    function __construct() {
        $this->products = array();
    }

    // Add product
    function add_product(Product $product) {
        $id = $product->get_id();
        if (isset($this->products[$id])) {
            $this->products[$id]->increase_qty($product->get_qty());
        } else {
            $this->products[$id] = $product;
        }
    }

    // Remove product completely
    function remove_product($product_id) {
        if (isset($this->products[$product_id])) {
            unset($this->products[$product_id]);
        }
    }

    // Update product quantity
    function update_product_qty($product_id, $qty) {
        if (isset($this->products[$product_id])) {
            if ($qty > 0) {
                $this->products[$product_id]->set_qty($qty);
            } else {
                $this->remove_product($product_id);
            }
        }
    }

    // Clear all products from the cart
    function clear() {
        $this->products = array();
    }

    // Get all products
    function get_products() {
        return $this->products;
    }

    // Get total items in cart
    function get_total_items() {
        $total = 0;
        foreach ($this->products as $product) {
            $total += $product->get_qty();
        }
        return $total;
    }

    // Get total price
    function get_total_price() {
        $total = 0;
        foreach ($this->products as $product) {
            $total += $product->get_total_cost();
        }
        return $total;
    }
}
?>