<?php

require_once 'site_components.php';
require_once 'data_store.php';
require_once 'product.php';

function getImagePath(string $imageBaseName, string $ext = "svg"): string
{
    return "images/$imageBaseName.$ext";
}

$headerComponents = [
    new SiteHeaderComponent(
        name: "Home",
        iconPath: getImagePath("ic_home")
    ),
    new SiteHeaderComponent(
        name: "Products",
        iconPath: getImagePath("ic_products")
    ),
    new SiteHeaderComponent(
        name: "Cart",
        iconPath: getImagePath("ic_cart")
    )
];

$footerComponents = [
    new SiteFooterComponent(
        name: "Home"
    ),
    new SiteFooterComponent(
        name: "Products"
    ),
    new SiteFooterComponent(
        name: "About Us"
    ),
    new SiteFooterComponent(
        name: "Cart"
    )
];

$dataStore = new DataStore();
$productView = new ProductDisplayList(array_map(function (ProductItem $product) {
    return new ProductDisplayComponent($product);
}, $dataStore->getAvailableProducts()));
$productForm = new ProductPickUpForm($productView, StaticUiComponent::fromString('<input type="submit" value="Send" class="product-pick-up-form-submit-button">'));

$siteView = new SiteView(new SiteHeader($headerComponents), new SiteBody($productForm), new SiteFooter($footerComponents));

echo $siteView->createHtmlView();
