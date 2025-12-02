<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use DOMElement;
use SimpleSAML\CAS\Assert\Assert;
use SimpleSAML\CAS\Type\CodeValue;
use SimpleSAML\XMLSchema\Exception\InvalidDOMElementException;
use SimpleSAML\XMLSchema\Type\StringValue;

/**
 * Class for CAS authenticationFailure
 *
 * @package simplesamlphp/xml-cas
 */
final class AuthenticationFailure extends AbstractAuthenticationFailure
{
    /**
     * Initialize an AuthenticationFailure element.
     *
     * @param \DOMElement $xml The XML element we should load.
     * @return static
     *
     * @throws \SimpleSAML\XMLSchema\Exception\InvalidDOMElementException
     *   if the qualified name of the supplied element is wrong
     * @throws \SimpleSAML\XMLSchema\Exception\MissingAttributeException
     *   if the supplied element is missing any of the mandatory attributes
     */
    public static function fromXML(DOMElement $xml): static
    {
        Assert::same($xml->localName, static::getLocalName(), InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, static::getNamespaceURI(), InvalidDOMElementException::class);

        return new static(
            StringValue::fromString($xml->textContent),
            self::getAttribute($xml, 'code', CodeValue::class),
        );
    }
}
