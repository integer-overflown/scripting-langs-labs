<?php

require_once 'product.php';

/**
 * Just an abstraction over data source, this could have been a database, but is a plain array for simplicity
 */
class DataStore
{
    public function getAvailableProducts(): array
    {
        // We're serving the same as in Lab2
        return [
            new ProductItem(id: 0, name: "Coffee", price: 10),
            new ProductItem(id: 1, name: "Cocoa", price: 7),
            new ProductItem(id: 2, name: "Tea", price: 5),
            new ProductItem(id: 3, name: "Cola", price: 14),
            new ProductItem(id: 4, name: "Pepsi", price: 15),
            new ProductItem(id: 5, name: "Sprite", price: 12),
            new ProductItem(id: 6, name: "Juice", price: 14),
            new ProductItem(id: 7, name: "Energy drink", price: 20),
            new ProductItem(id: 8, name: "Beer", price: 25),
            new ProductItem(id: 9, name: "Whiskey", price: 60)
        ];
    }
}
