<?php
require_once 'product.php';
require_once 'page_template.php';
require_once 'product_receipt.php';

class PurchaseReceiptTable implements UiComponent
{
    private static array $headers = ['id', 'name', 'price', 'count', 'sum'];

    public function __construct(
        private readonly ProductReceipt $productReceipt
    )
    {

    }

    public function createHtmlView(): string
    {
        $html = '<table class="purchase-receipt-table"><tbody><tr class="purchase-receipt-table-row">';

        foreach (PurchaseReceiptTable::$headers as $header) {
            $html .= "<th class=\"purchase-receipt-table-row-header\">$header</th>";
        }

        $html .= '</tr>';

        foreach ($this->productReceipt->itemsBought as $productReceiptEntry) {
            $html .= '<tr class="purchase-receipt-table-row">';

            $product = $productReceiptEntry->productItem;
            $amount = $productReceiptEntry->amount;
            $sum = $amount * $product->price;

            foreach ([$product->id, $product->name, $product->price, $amount, $sum] as $rowData) {
                $html .= "<td class=\"purchase-receipt-table-row-data\">$rowData</td>";
            }

            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }

}

if (empty(session_id())) {
    session_start();
}

$placeholder = StaticUiComponent::fromString(
    '<div class="purchase-empty-cart-placeholder"><p>Nothing in the cart yet</p><a href="page_products.php">Go to products</a></div>'
);

$sessionReceipt = ProductReceipt::getSessionReceipt();
$haveContent = ($sessionReceipt !== null && count($sessionReceipt->itemsBought) > 0);
$bodyComponent = $haveContent ? new PurchaseReceiptTable($sessionReceipt) : $placeholder;

$page = new BasicSiteView(new SiteBody($bodyComponent));

echo $page->createHtmlView();
