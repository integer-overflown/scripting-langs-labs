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
    new BasicSiteHeaderComponent(
        name: "Home",
        iconPath: getImagePath("ic_home"),
        route: 'page_products.php'
    ),
    new BasicSiteHeaderComponent(
        name: "Products",
        iconPath: getImagePath("ic_products"),
        route: 'page_products.php'
    ),
    new BasicSiteHeaderComponent(
        name: "Cart",
        iconPath: getImagePath("ic_cart"),
        route: 'page_cart.php'
    ),
    new VolatileSiteHeaderComponent(
        getNameFunction: function () {
            return "Profile";
        },
        iconPath: getImagePath("ic_profile"),
        route: 'page_profile.php',
        shouldShowFunction: function () {
            return LoginInfo::fromSession() !== null;
        }
    ),
    new VolatileSiteHeaderComponent(
        getNameFunction: function () {
            return LoginInfo::fromSession() === null ? "Login" : "Logout";
        },
        iconPath: getImagePath("login_icon"),
        route: 'page_login.php',
        shouldShowFunction: function () {
            return true;
        }
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
        $instance = new SiteView(new SiteHeader($headerComponents), $this->body, new SiteFooter($footerComponents), $this->loginInfo);
        return $instance->createHtmlView();
    }
}
