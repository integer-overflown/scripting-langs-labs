<?php

require_once 'site_components.php';
require_once 'data_store.php';
require_once 'product.php';
require_once 'model/login_info.php';

function getImagePath(string $imageBaseName, string $ext = "svg"): string
{
    return "images/$imageBaseName.$ext";
}

$headerComponents = [
    new SiteHeaderComponent(
        name: "Home",
        iconPath: getImagePath("ic_home"),
        route: 'page_products.php'
    ),
    new SiteHeaderComponent(
        name: "Products",
        iconPath: getImagePath("ic_products"),
        route: 'page_products.php'
    ),
    new SiteHeaderComponent(
        name: "Cart",
        iconPath: getImagePath("ic_cart"),
        route: 'page_cart.php'
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
        private readonly UiComponent $body,
        private readonly ?LoginInfo  $loginInfo
    )
    {

    }

    public function createHtmlView(): string
    {
        global $headerComponents;
        global $footerComponents;
        $instance = new SiteView(new SiteHeader($headerComponents), $this->getBodyView(), new SiteFooter($footerComponents));
        return $instance->createHtmlView();
    }

    private function getBodyView(): UiComponent
    {
        return $this->loginInfo === null
            ? StaticUiComponent::fromString('<body><p class="login-required-alert-message">Please login first</p></body>')
            : $this->body;
    }

}
