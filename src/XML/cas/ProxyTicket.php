<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XML\StringElementTrait;

/**
 * Class for CAS proxyTicket
 *
 * @package simplesamlphp/cas
 */
class ProxyTicket extends AbstractCasElement
{
    use StringElementTrait;

    /** @var string */
    public const LOCALNAME = 'proxyTicket';


    /**
     * @param string $content
     */
    final public function __construct(string $content)
    {
        $this->setContent($content);
    }


    /**
     * Validate the content of the element.
     *
     * @param string $content  The value to go in the XML textContent
     * @throws \Exception on failure
     * @return void
     */
    protected function validateContent(string $content): void
    {
        Assert::notWhitespaceOnly($content);
    }


    /**
     * Convert XML into a cas:proxyTicket
     *
     * @param \DOMElement $xml The XML element we should load
     * @return static
     *
     * @throws \SimpleSAML\XML\Exception\InvalidDOMElementException
     *   If the qualified name of the supplied element is wrong
     */
    public static function fromXML(DOMElement $xml): static
    {
        Assert::same($xml->localName, 'proxyTicket', InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, ProxyTicket::NS, InvalidDOMElementException::class);

        return new static($xml->textContent);
    }
}
