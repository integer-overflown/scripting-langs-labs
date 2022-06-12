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
    const SESSION_KEY_PRODUCT_RECEIPT = 'PRODUCT_RECEIPT';

    private function __construct(
        public readonly array $itemsBought,
    )
    {
    }

    public static function parseRequest(array $userRequest): ?ProductReceipt
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

            if (!is_numeric($chosenAmount)) {
                return null;
            }

            $chosenAmount = intval($chosenAmount);

            // if none of the product were bought, we don't want to store a record in the receipt
            if ($chosenAmount === 0) {
                error_log("$id empty");
                continue;
            }

            // Freaky PHP syntax to push an item to array
            $itemsBought[] = new ProductReceiptEntry($product, $chosenAmount);
        }

        return new ProductReceipt($itemsBought);
    }

    public static function getSessionReceipt(): ?ProductReceipt
    {
        return array_key_exists(static::SESSION_KEY_PRODUCT_RECEIPT, $_SESSION) ? $_SESSION[static::SESSION_KEY_PRODUCT_RECEIPT] : null;
    }

    public static function setSessionReceipt(ProductReceipt $receipt): void
    {
        $_SESSION[static::SESSION_KEY_PRODUCT_RECEIPT] = $receipt;
    }
}
