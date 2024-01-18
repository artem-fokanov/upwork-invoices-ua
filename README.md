# PHP генератор PDF інвойсів для Upwork 

## Використання
Простий приклад виклик:
```php
<?php

require_once 'vendor/autoload.php';

use UpworkInvoicesUa\Factory\SimplePdf;

$pdfBuilder = new SimplePdf(
    'invoice.pdf',
    <<<'HTML'
        <html lang="en">
        <head>
            <title>My invoice</title>
        </head>
        <body>
            <h1>Invoice</h1>
        </body>
        </html>'
    HTML);

$pdfBuilder->generateInvoice(); // Creates 'invoice.pdf' file with specified HTML
```

Можете ознайомитись з повним [прикладом](./templates/pdf-example.php) генерації PDF файлу `invoice.pdf` з типовим [шаблоном](./templates/html/invoice.html) для резидентів України.

## Підлаштування
Ви вільні створювати будь-який формат шаблону інвойсу та його генерації. Наслідуйте `UpworkInvoicesUa\Factory\Component` для рушія генератора інвойсів та `UpworkInvoicesUa\Invoice\Invoice` для їх імплементації. 