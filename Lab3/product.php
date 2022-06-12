<?php

require_once 'site_components.php';

class ProductItem
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly float  $price
    )
    {
    }
}

class ProductDisplayComponent implements UiComponent
{
    public function __construct(
        private readonly ProductItem $productItem
    )
    {
    }

    public function createHtmlView(): string
    {
        return
            '<span class="product-list-item-name">' . $this->productItem->name . '</span>
            <input class="product-list-item-amount-input" type="number" min="0" value="0">
            <span class="product-list-item-price">$' . $this->productItem->price . ' per each</span>';
    }
}

class ProductDisplayList implements UiComponent
{
    public function __construct(
        private readonly array $productDisplayComponents
    )
    {
    }

    function createHtmlView(): string
    {
        return
            '<div class="product-list-container">'
            .
            implode("\n", array_map(function (UiComponent $uiComponent) {
                return $uiComponent->createHtmlView();
            }, $this->productDisplayComponents))
            .
            '</div>';
    }

}
