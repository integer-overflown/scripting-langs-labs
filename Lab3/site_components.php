<?php

interface UiComponent
{
    public function createHtmlView(): string;
}

class SiteHeaderComponent implements UiComponent
{
    public function __construct(
        private readonly string $name,
        private readonly string $iconPath,
        private readonly string $route
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
                <a class=\"site-header-section-name\" href=\"$this->route\">$this->name</a>
                </div>";
    }

}

class SiteHeader implements UiComponent
{
    public function __construct(
        public readonly array $headerComponents
    )
    {
    }

    public function createHtmlView(): string
    {
        return '<header class="site-header">
        <div class="site-header-background-group">
        <div class="site-header-section-separators">'
            .
            implode("\n", array_fill(0, count($this->headerComponents) - 1, "<span>|</span>"))
            .
            '
        </div>
        <div class="site-header-section-container">
        '
            .
            implode("\n", array_map(function (UiComponent $component) {
                return $component->createHtmlView();
            }, $this->headerComponents))
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
        private readonly UiComponent $footerComponent
    )
    {
    }

    public function createHtmlView(): string
    {
        $head = file_get_contents('head.html');
        assert($head !== false, "Unable to read HTML head");
        return '<html lang="en">' . $head .
            '<body>' . $this->headerComponent->createHtmlView() . $this->siteBody->createHtmlView() . $this->footerComponent->createHtmlView() .
            '</body>' . '</html>';
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

class StaticUiComponent implements UiComponent {
    private function __construct(
        private readonly string $contents
    ) {
    }

    public static function fromString(string $contents) : UiComponent {
        return new StaticUiComponent($contents);
    }

    public static function fromFile(string $path) : UiComponent {
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
