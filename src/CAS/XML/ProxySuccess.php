<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use DOMElement;
use SimpleSAML\CAS\Assert\Assert;
use SimpleSAML\XMLSchema\Exception\InvalidDOMElementException;
use SimpleSAML\XMLSchema\Exception\MissingElementException;

/**
 * Class for CAS proxySuccess
 *
 * @package simplesamlphp/xml-cas
 */
final class ProxySuccess extends AbstractProxySuccess
{
    /**
     * Initialize an ProxySuccess element.
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

        $proxyTicket = ProxyTicket::getChildrenOfClass($xml);
        Assert::count(
            $proxyTicket,
            1,
            'Exactly one <cas:proxyTicket> must be specified.',
            MissingElementException::class,
        );

        return new static($proxyTicket[0]);
    }
}
