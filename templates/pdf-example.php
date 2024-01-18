<?php

require_once 'vendor/autoload.php';

use NumberToWords\NumberToWords;
use UpworkInvoicesUa\Factory\{
    Component,
    HtmlTemplateEngine,
    SimplePdf
};

function makeInvoice(Component $component) {
    return $component->generateInvoice();
}

function numberFormatter(): NumberFormatter {
    //$fmt = new NumberFormatter( 'en-US', NumberFormatter::DECIMAL);
    $fmt = new NumberFormatter( 'en-US', NumberFormatter::PATTERN_DECIMAL);
    $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
    $fmt->setSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL, ' ');

    return $fmt;
}

echo "Preparing data for invoice" . PHP_EOL;
// Date used as base of invoice ID
$date = new DateTime;
// Invoice number is date+revision
$invoice = $date->format('ymd/1');
// Price
$fmt = numberFormatter();
$usd = 1115.00;

$data = [
    // These fills matching DOMElement by ID with text
    'ids' => [
        'invoice' => $invoice,
        'en-pe-name' => 'John Doe',
        'uk-pe-name' => 'Джон До',

        'en-pe-address' => '123 Main Street, Anytown, 12345',
        'uk-pe-address' => '12345, м.Енітаун, вул. Мейн Стріт 123',

        'en-pe-id' => '1122333444',
        'uk-pe-id' => '1122333444',

        'en-subject' => 'Software development',
        'uk-subject' => 'Розробка програмного забезпечення',

        'en-bank-pe-name' => 'John Doe',
        'en-beneficiary-bank-pe-iban' => 'UA21 3223 1300 0002 6007 2335 6600 1',
        'en-beneficiary-bank' => 'B-BANK',
        'en-beneficiary-bank-address' => '987 Main Street, Anytown, USA 12345',
        'en-beneficiary-bank-swift-code' => '00SWIFT99',

        'en-description' => 'Software development (to be detailed)',
        'uk-description' => 'Розробка програмного забезпечення (детально)',

        'en-total-text' => NumberToWords::transformCurrency('en', $usd * 100, 'USD'),
        'uk-total-text' => NumberToWords::transformCurrency('ua', $usd * 100, 'USD')
    ],
    // These fills all matching DOMElement by tag name
    'tags' => [
        'date' => $date->format('d.m.Y'),
        'price-total' => $fmt->format($usd)
    ],
    // And these fills matching DOMElement by ID with HTML
    'html' => [
        'en-correspondent-bank' => <<<'HTML'
            <br/>
            Account in the correspondent bank: <br/>
            Correspondent bank: <br/>
            Bank Address: <br/>
            SWIFT code: <br/>
            <br/>
            HTML,
    ]
];

$templateFile = __DIR__ . '/templates/html/invoice.html';
echo "Populating HTML template $templateFile with data" . PHP_EOL;
$htmlInvoice = makeInvoice(new HtmlTemplateEngine($templateFile, $data));

$outputFile = 'invoice.pdf';
makeInvoice(new SimplePdf($outputFile, $htmlInvoice));
echo "Output file: " . realpath($outputFile) . PHP_EOL;