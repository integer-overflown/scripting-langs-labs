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

    private function generateCells(array $cellData, string $cellCssClass = "purchase-receipt-table-row-data"): string
    {
        $html = '';
        foreach ($cellData as $cellDatum) {
            $html .= "<td class=\"$cellCssClass\">$cellDatum</td>";
        }
        return $html;
    }

    public function createHtmlView(): string
    {
        $html = '<table class="purchase-receipt-table"><tbody><tr class="purchase-receipt-table-row">';

        foreach (PurchaseReceiptTable::$headers as $header) {
            $html .= "<th class=\"purchase-receipt-table-row-header\">$header</th>";
        }

        $html .= '</tr>';

        $total = 0.0;

        foreach ($this->productReceipt->itemsBought as $productReceiptEntry) {
            $html .= '<tr class="purchase-receipt-table-row">';

            $product = $productReceiptEntry->productItem;
            $amount = $productReceiptEntry->amount;
            $sum = $amount * $product->price;
            $deleteButton =
                "<button type=\"button\" onclick=\"removeProductFromCart($product->id)\" class=\"purchase-receipt-table-delete-button\">
                <img class=\"purchase-receipt-table-delete-button-icon\" src=\"images/ic_trash.svg\" alt>
                </button>";

            $total += $sum;

            $html .= $this->generateCells([$product->id, $product->name, $product->price, $amount, $sum]);
            $html .= $this->generateCells([$deleteButton], "purchase-receipt-table-row-data-centered");
            $html .= '</tr>';
        }

        $html .= $this->generateCells(['Total', '', '', '', $total, '']);

        $html .= '</tbody></table>';

        return $html;
    }

}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$sessionReceipt = ProductReceipt::getSessionReceipt();

function createBodyComponent(?ProductReceipt $sessionReceipt): UiComponent
{
    $placeholder = StaticUiComponent::fromString(
        '<div class="purchase-empty-cart-placeholder"><p>Nothing in the cart yet</p><a href="page_products.php">Go to products</a></div>'
    );
    $haveContent = ($sessionReceipt !== null && count($sessionReceipt->itemsBought) > 0);
    return $haveContent ? new PurchaseReceiptTable($sessionReceipt) : $placeholder;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $page = new BasicSiteView(new SiteBody(createBodyComponent($sessionReceipt)), LoginInfo::fromSession());
        echo $page->createHtmlView();
        break;
    case 'DELETE':
        if (array_key_exists('productId', $_REQUEST)) {
            $productId = $_REQUEST['productId'];

            if (!is_numeric($productId)) {
                http_response_code(400);
                exit;
            }

            $sessionReceipt->removeProduct($productId);

            echo createBodyComponent($sessionReceipt)->createHtmlView();
        } else {
            http_response_code(400);
        }
        break;
    default:
        http_response_code(405); // 405 Method Not Supported
        exit;
}

