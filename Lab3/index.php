<?php

require_once 'product.php';

if (isset($_POST[POST_PARAM_PRODUCTS_PICK_UP])) {
    header("Location: cart.php");
    exit(0);
}

require_once 'page_products.php';
