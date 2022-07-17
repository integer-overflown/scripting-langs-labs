<?php

interface UiComponent
{
    public function createHtmlView(): string;
}

interface SiteHeaderComponent extends UiComponent
{
    public function isShown(): bool;
}

class VolatileSiteHeaderComponent implements SiteHeaderComponent
{
    public function __construct(
        private readonly mixed  $getNameFunction,
        private readonly string $iconPath,
        private readonly string $route,
        private readonly mixed  $shouldShowFunction
    )
    {
        if (!file_exists($this->iconPath)) {
            trigger_error("$this->iconPath doesn't exist", E_USER_WARNING);
        }
    }

    public function createHtmlView(): string
    {
        return "<div class=\"site-header-section\">
                <img src=\"$this->iconPath\" class=\"site-header-section-icon\" alt>
                <a class=\"site-header-section-name\" href=\"$this->route\">" . ($this->getNameFunction)() . "</a>
                </div>";
    }

    public function isShown(): bool
    {
        return ($this->shouldShowFunction)();
    }

}

class BasicSiteHeaderComponent implements SiteHeaderComponent
{
    private readonly SiteHeaderComponent $actual;

    public function __construct(
        string $name,
        string $iconPath,
        string $route,
        bool   $isShown = true
    )
    {
        if (!file_exists($iconPath)) {
            trigger_error("$iconPath doesn't exist", E_USER_WARNING);
        }

        $this->actual = new VolatileSiteHeaderComponent(function () use ($name) {
            return $name;
        }, $iconPath, $route, function () use ($isShown) {
            return $isShown;
        });
    }

    public function createHtmlView(): string
    {
        return $this->actual->createHtmlView();
    }

    public function isShown(): bool
    {
        return $this->actual->isShown();
    }


}

class SiteHeader implements UiComponent
{
    private const SEPARATOR_ELEMENT = '<span>|</span>';

    public function __construct(
        public readonly array $headerComponents
    )
    {
    }

    public function createHtmlView(): string
    {
        $shownComponents = [];

        foreach ($this->headerComponents as $headerComponent) {
            if ($headerComponent->isShown()) {
                $shownComponents[] = $headerComponent->createHtmlView();
            }
        }

        return '<header class="site-header">
        <div class="site-header-background-group">
        <div class="site-header-section-container">
        '
            .
            implode("\n" . static::SEPARATOR_ELEMENT . "\n", $shownComponents)
            .
            '</div></div></header>';
    }

}

class SiteFooterComponent implements UiComponent
{
    public function __construct(
        public string $name
    )
    {
    }

    public function createHtmlView(): string
    {
        return "<div class=\"site-footer-section\">
                <span class=\"site-footer-section-name\">$this->name</span>
                </div>";
    }

}

class SiteFooter implements UiComponent
{
    private static string $separatorElement = "<span>|</span>";

    public function __construct(
        public readonly array $footerComponents
    )
    {
    }

    public function createHtmlView(): string
    {
        return
            '<footer class="site-footer">
        <div class="site-footer-background-group">
        <div class="site-footer-section-container">'
            .
            implode("\n" . SiteFooter::$separatorElement . "\n", array_map(function (UiComponent $uiComponent) {
                return $uiComponent->createHtmlView();
            }, $this->footerComponents))
            .
            '</div>
         </div>
         </footer>';
    }

}

class SiteView implements UiComponent
{
    public function __construct(
        private readonly UiComponent $headerComponent,
        private readonly UiComponent $siteBody,
        private readonly UiComponent $footerComponent,
        private readonly ?LoginInfo  $loginInfo
    )
    {
    }

    public function createHtmlView(): string
    {
        $head = file_get_contents('head.html');
        assert($head !== false, "Unable to read HTML head");
        return '<html lang="en">' . $head .
            '<body>' . $this->headerComponent->createHtmlView() . $this->getBodyView()->createHtmlView() . $this->footerComponent->createHtmlView() .
            '</body>' . '</html>';
    }

    private function getBodyView(): UiComponent
    {
        return $this->loginInfo === null
            ? StaticUiComponent::fromString('<body><p class="login-required-alert-message">Please login first</p></body>')
            : $this->siteBody;
    }
}

class SiteBody implements UiComponent
{
    public function __construct(
        private readonly UiComponent $siteBody
    )
    {
    }

    public function createHtmlView(): string
    {
        return '<body><div class="page-body-view">'
            .
            $this->siteBody->createHtmlView()
            .
            '</div></body>';
    }
}

class StaticUiComponent implements UiComponent
{
    private function __construct(
        private readonly string $contents
    )
    {
    }

    public static function fromString(string $contents): UiComponent
    {
        return new StaticUiComponent($contents);
    }

    public static function fromFile(string $path): UiComponent
    {
        if (!file_exists($path)) {
            trigger_error("$path doesn't exist", E_ERROR);
        }
        return new StaticUiComponent(file_get_contents($path));
    }

    public function createHtmlView(): string
    {
        return $this->contents;
    }
}
