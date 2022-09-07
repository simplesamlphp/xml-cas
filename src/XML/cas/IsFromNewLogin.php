<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\CAS\Exception\ProtocolViolationException;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XML\StringElementTrait;

/**
 * Class for CAS isFromNewLogin
 *
 * @package simplesamlphp/cas
 */
class IsFromNewLogin extends AbstractCasElement
{
    use StringElementTrait;

    /** @var string */
    public const LOCALNAME = 'isFromNewLogin';


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
        Assert::oneOf(
            $content,
            ['true', 'false', '0', '1'],
            'The value of ' . static::getNamespacePrefix() . ':' . self::getLocalName() . ' must be boolean.',
            ProtocolViolationException::class,
        );
    }


    /**
     * Convert XML into a cas:isFromNewLogin
     *
     * @param \DOMElement $xml The XML element we should load
     * @return static
     *
     * @throws \SimpleSAML\XML\Exception\InvalidDOMElementException
     *   If the qualified name of the supplied element is wrong
     */
    public static function fromXML(DOMElement $xml): static
    {
        Assert::same($xml->localName, 'isFromNewLogin', InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, IsFromNewLogin::NS, InvalidDOMElementException::class);

        return new static($xml->textContent);
    }
}
