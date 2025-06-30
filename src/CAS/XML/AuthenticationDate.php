<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use DOMElement;
use SimpleSAML\CAS\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\InvalidDOMElementException;
use SimpleSAML\XMLSchema\Type\DateTimeValue;

use function strval;

/**
 * Class for CAS authenticationDate
 *
 * @package simplesamlphp/cas
 */
final class AuthenticationDate extends AbstractCasElement
{
    /** @var string */
    final public const LOCALNAME = 'authenticationDate';


    /**
     * @param \SimpleSAML\XMLSchema\Type\DateTimeValue $timestamp
     */
    final public function __construct(
        protected DateTimeValue $timestamp,
    ) {
    }


    /**
     * Retrieve the issue timestamp of this message.
     *
     * @return \SimpleSAML\XMLSchema\Type\DateTimeValue The issue timestamp of this message
     */
    public function getTimestamp(): DateTimeValue
    {
        return $this->timestamp;
    }


    /**
     * Convert this element into an XML document.
     *
     * @return \DOMElement The root element of the DOM tree
     */
    public function toXML(?DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);
        $e->textContent = strval($this->getTimestamp());

        return $e;
    }


    /**
     * Convert XML into a cas:authenticationDate
     *
     * @param \DOMElement $xml The XML element we should load
     * @return static
     *
     * @throws \SimpleSAML\XMLSchema\Exception\InvalidDOMElementException
     *   If the qualified name of the supplied element is wrong
     */
    public static function fromXML(DOMElement $xml): static
    {
        Assert::same($xml->localName, static::getLocalName(), InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, static::getNamespaceURI(), InvalidDOMElementException::class);

        return new static(DateTimeValue::fromString($xml->textContent));
    }
}
