<?php

namespace UpworkInvoicesUa\Invoice;

class HtmlInvoice implements Invoice
{
    public function __construct(protected string $html) {}

    public function generate(): string
    {
        return $this->html;
    }
}