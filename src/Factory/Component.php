<?php

namespace UpworkInvoicesUa\Factory;
use UpworkInvoicesUa\Invoice\Invoice;

abstract class Component
{
    abstract public function create(): Invoice;

    public function generateInvoice()
    {
        $component = $this->create();

        return $component->generate();
    }
}