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

class BasicSiteView implements UiComponent
{
    public function __construct(
        private readonly UiComponent $body
    )
    {

    }

    public function createHtmlView(): string
    {
        global $headerComponents;
        global $footerComponents;
        $instance = new SiteView(new SiteHeader($headerComponents), $this->body, new SiteFooter($footerComponents));
        return $instance->createHtmlView();
    }

}
