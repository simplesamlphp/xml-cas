<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use DateTimeImmutable;
use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\CAS\Constants as C;
use SimpleSAML\XML\Exception\InvalidDOMElementException;

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
     * @param \DateTimeImmutable $timestamp
     */
    final public function __construct(
        protected DateTimeImmutable $timestamp
    ) {
    }


    /**
     * Retrieve the issue timestamp of this message.
     *
     * @return \DateTimeImmutable The issue timestamp of this message, as an UNIX timestamp
     */
    public function getTimestamp(): DateTimeImmutable
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
        $root = $this->instantiateParentElement($parent);
        $root->textContent = $this->getTimestamp()->format(C::DATETIME_FORMAT);

        return $root;
    }


    /**
     * Convert XML into a cas:authenticationDate
     *
     * @param \DOMElement $xml The XML element we should load
     * @return static
     *
     * @throws \SimpleSAML\XML\Exception\InvalidDOMElementException
     *   If the qualified name of the supplied element is wrong
     */
    public static function fromXML(DOMElement $xml): static
    {
        Assert::same($xml->localName, static::getLocalName(), InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, static::getNamespaceURI(), InvalidDOMElementException::class);

        return new static(new DateTimeImmutable($xml->textContent));
    }
}
