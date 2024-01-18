<?php

namespace UpworkInvoicesUa\Factory;

use UpworkInvoicesUa\Invoice\HtmlInvoice;
use UpworkInvoicesUa\Invoice\Invoice;

class HtmlTemplateEngine extends Component
{
    protected \DOMDocument $document;

    public function __construct(string $file, array $data)
    {
        if (!file_exists($file)) {
            throw new \RuntimeException('File doesn\'t exist!');
        }

        libxml_use_internal_errors(true);

        $this->document = new \DOMDocument;
        $this->document->loadHTMLFile($file);

        if (array_key_exists('ids', $data) && is_array($data['ids'])) {
            foreach ($data['ids'] as $id => $value) {
                $this->assignTextToNodeById($id, $value);
            }
        }
        if (array_key_exists('html', $data) && is_array($data['ids'])) {
            foreach ($data['html'] as $id => $value) {
                $this->assignHtmlToNodeById($id, $value);
            }
        }
        if (array_key_exists('tags', $data) && is_array($data['tags'])) {
            foreach ($data['tags'] as $tag => $value) {
                $this->assignTextToTags($tag, $value);
            }
        }
    }

    public function create(): Invoice
    {
        $html = $this->document->saveHTML();
        return new HtmlInvoice($html ?? '');
    }

    public function assignTextToTags(string $tag, string $text): void
    {
        $nodes = $this->document->getElementsByTagName($tag);
        foreach ($nodes as $node) {
            $node->textContent = $text;
        }
    }

    public function assignTextToNodeById(string $id, string $text): void
    {
        $element = $this->document->getElementById($id);
        if ($element) {
            $element->textContent = $text;
        }
    }
    public function assignHtmlToNodeById(string $id, string $html): void
    {
        $element = $this->document->getElementById($id);

        if ($element) {
            $fragment = $element->ownerDocument->createDocumentFragment();
            $fragment->appendXML($html);
            while ($element->hasChildNodes())
                $element->removeChild($element->firstChild);
            $element->appendChild($fragment);
        }
    }
}