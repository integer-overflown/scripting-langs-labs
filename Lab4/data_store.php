<?php

require_once 'product.php';

// We're serving the same as in Lab2
$productsTable = [
    0 => new ProductItem(id: 0, name: "Coffee", price: 10),
    1 => new ProductItem(id: 1, name: "Cocoa", price: 7),
    2 => new ProductItem(id: 2, name: "Tea", price: 5),
    3 => new ProductItem(id: 3, name: "Cola", price: 14),
    4 => new ProductItem(id: 4, name: "Pepsi", price: 15),
    5 => new ProductItem(id: 5, name: "Sprite", price: 12),
    6 => new ProductItem(id: 6, name: "Juice", price: 14),
    7 => new ProductItem(id: 7, name: "Energy drink", price: 20),
    8 => new ProductItem(id: 8, name: "Beer", price: 25),
    9 => new ProductItem(id: 9, name: "Whiskey", price: 60)
];

/**
 * Just an abstraction over data source, this could have been a database, but is a plain array for simplicity
 */
class DataStore
{
    public function getAvailableProducts(): array
    {
        global $productsTable;
        return $productsTable;
    }

    public function getProductById(int $id): ?ProductItem
    {
        global $productsTable;
        if ($id < 0 || $id > count($productsTable)) {
            return null;
        }
        return $productsTable[$id];
    }

    public function getRegisteredCredentials(): array
    {
        return [['test', 'qwerty123'], ['user', 'password']];
    }
}
