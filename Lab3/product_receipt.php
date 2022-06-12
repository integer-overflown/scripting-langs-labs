<?php
require_once 'product.php';
require_once 'data_store.php';

class ProductReceiptEntry
{
    public function __construct(
        public readonly ProductItem $productItem,
        public readonly int         $amount
    )
    {
    }
}

class ProductReceipt
{
    private function __construct(
        public readonly array $itemsBought,
    )
    {
    }

    static function parseRequest(array $userRequest): ?ProductReceipt
    {
        $dataStore = new DataStore();
        $itemsBought = [];

        foreach ($userRequest as $id => $properties) {
            $product = $dataStore->getProductById($id);

            if ($product === null) {
                return null;
            }

            if (!key_exists(POST_PARAM_PRODUCTS_CHOSEN_AMOUNT, $properties)
                || ($chosenAmount = $properties[POST_PARAM_PRODUCTS_CHOSEN_AMOUNT]) < 0) {
                return null;
            }

            // if none of the product were bought, we don't want to store a record in the receipt
            if ($chosenAmount === 0) {
                continue;
            }

            // Freaky PHP syntax to push an item to array
            $itemsBought[] = new ProductReceiptEntry($product, $chosenAmount);
        }

        return new ProductReceipt($itemsBought);
    }
}
