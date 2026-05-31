<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use Dom;
use SimpleSAML\CAS\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\InvalidDOMElementException;
use SimpleSAML\XMLSchema\Type\DateTimeValue;

use function strval;

/**
 * Class for CAS authenticationDate
 *
 * @package simplesamlphp/xml-cas
 */
final class AuthenticationDate extends AbstractCasElement
{
    final public const string LOCALNAME = 'authenticationDate';


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
     * @return \Dom\Element The root element of the DOM tree
     */
    public function toXML(?Dom\Element $parent = null): Dom\Element
    {
        $e = $this->instantiateParentElement($parent);
        $e->textContent = strval($this->getTimestamp());

        return $e;
    }


    /**
     * Convert XML into a cas:authenticationDate
     *
     * @param \Dom\Element $xml The XML element we should load
     * @return static
     *
     * @throws \SimpleSAML\XMLSchema\Exception\InvalidDOMElementException
     *   If the qualified name of the supplied element is wrong
     */
    public static function fromXML(Dom\Element $xml): static
    {
        Assert::same($xml->localName, static::getLocalName(), InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, static::getNamespaceURI(), InvalidDOMElementException::class);

        return new static(DateTimeValue::fromString((string) $xml->textContent));
    }
}
