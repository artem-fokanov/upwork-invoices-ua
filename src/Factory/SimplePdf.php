<?php

namespace UpworkInvoicesUa\Factory;

use Mpdf\Mpdf;
use UpworkInvoicesUa\Invoice\Invoice;

class SimplePdf extends Component implements Invoice
{
    protected Mpdf $pdf;

    public function __construct(protected string $fileName, protected string $content){
        $this->pdf = new Mpdf;
        $this->pdf->WriteHTML($this->content);
    }

    public function create(): Invoice
    {
        return $this; // This file combines factory component & invoice itself
    }

    public function generate(): ?string
    {
        return $this->pdf->OutputFile($this->fileName);
    }
}