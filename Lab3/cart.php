<?php
require_once 'page_template.php';

$page = new BasicSiteView(new SiteBody(StaticUiComponent::fromString("<h1>Hold on, cart is being developed</h1>")));

echo $page->createHtmlView();
