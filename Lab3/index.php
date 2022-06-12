<?php

require_once 'site_components.php';

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

class DummyBody implements UiComponent
{
    function createHtmlView(): string
    {
        return '<div>
            <h1>Nothing here yet, but stay tuned!</h1>
        </div>';
    }

}

$siteView = new SiteView(new SiteHeader($headerComponents), new DummyBody(), new SiteFooter($footerComponents));

echo $siteView->createHtmlView();
