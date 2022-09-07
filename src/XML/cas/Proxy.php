<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XML\XMLStringElementTrait;

/**
 * Class for CAS proxy
 *
 * @package simplesamlphp/cas
 */
class Proxy extends AbstractCasElement
{
    use XMLStringElementTrait;

    /** @var string */
    public const LOCALNAME = 'proxy';


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
     * Convert XML into a cas:proxy
     *
     * @param \DOMElement $xml The XML element we should load
     * @return static
     *
     * @throws \SimpleSAML\XML\Exception\InvalidDOMElementException
     *   If the qualified name of the supplied element is wrong
     */
    public static function fromXML(DOMElement $xml): self
    {
        Assert::same($xml->localName, 'proxy', InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, Proxy::NS, InvalidDOMElementException::class);

        return new static($xml->textContent);
    }
}
