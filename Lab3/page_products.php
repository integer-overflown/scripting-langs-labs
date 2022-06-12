<?php

require_once 'site_components.php';
require_once 'data_store.php';
require_once 'page_template.php';

$dataStore = new DataStore();
$productView = new ProductDisplayList(array_map(function (ProductItem $product) {
    return new ProductDisplayComponent($product);
}, $dataStore->getAvailableProducts()));
$productForm = new ProductPickUpForm($productView, StaticUiComponent::fromString('<input type="submit" value="Send" class="product-pick-up-form-submit-button">'));

$siteView = new BasicSiteView(new SiteBody($productForm));

echo $siteView->createHtmlView();
