<?php

require_once 'product.php';
require_once 'product_receipt.php';

if (empty(session_id())) {
    session_start();
}

if (isset($_POST[POST_PARAM_PRODUCTS_PICK_UP])) {
    $receipt = ProductReceipt::parseRequest($_POST[POST_PARAM_PRODUCTS_PICK_UP]);
    if ($receipt === null) {
        // For now, don't care much about what exactly has gone wrong, just sent 'Bad request' back
        http_response_code(400);
    } else {
        ProductReceipt::setSessionReceipt($receipt);
        header('Location: page_cart.php');
    }
    exit;
}

require_once 'page_products.php';
