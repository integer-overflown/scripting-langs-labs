<?php

require_once 'site_components.php';

const POST_PARAM_PRODUCTS_PICK_UP = 'products';
const POST_PARAM_PRODUCTS_CHOSEN_AMOUNT = 'chosenAmount';

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
        // indexed form variables is a very nice trick, taken from
        // https://www.php.net/manual/en/reserved.variables.post.php
        return
            '<span class="product-list-item-name">' . $this->productItem->name . '</span>
            <input class="product-list-item-amount-input" type="number" min="0" value="0" name="'
            . POST_PARAM_PRODUCTS_PICK_UP . '[' . $this->productItem->id . '][' . POST_PARAM_PRODUCTS_CHOSEN_AMOUNT . ']">
            <span class="product-list-item-price">$' . $this->productItem->price . ' per each</span>
            ';
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

class ProductPickUpForm implements UiComponent
{
    public function __construct(
        private readonly ProductDisplayList $productDisplayList,
        private readonly UiComponent        $submitButton
    )
    {

    }

    public function createHtmlView(): string
    {
        return '<form method="POST" class="product-pick-up-form"><div class="product-pick-up-form-contents">' . $this->productDisplayList->createHtmlView() . $this->submitButton->createHtmlView() . '</div></form>';
    }

}
