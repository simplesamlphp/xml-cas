<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XML\Exception\MissingElementException;

use function array_pop;

/**
 * Class for CAS proxySuccess
 *
 * @package simplesamlphp/cas
 */
final class ProxySuccess extends AbstractResponse
{
    /** @var string */
    final public const LOCALNAME = 'proxySuccess';


    /**
     * Initialize a cas:proxySuccess element
     *
     * @param \SimpleSAML\CAS\XML\cas\ProxyTicket $proxyTicket
     */
    final public function __construct(
        protected ProxyTicket $proxyTicket,
    ) {
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\ProxyTicket
     */
    public function getProxyTicket(): ProxyTicket
    {
        return $this->proxyTicket;
    }


    /**
     * Initialize an ProxySuccess element.
     *
     * @param \DOMElement $xml The XML element we should load.
     * @return static
     *
     * @throws \SimpleSAML\XML\Exception\InvalidDOMElementException
     *   if the qualified name of the supplied element is wrong
     * @throws \SimpleSAML\XML\Exception\MissingAttributeException
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

        return new static(array_pop($proxyTicket));
    }


    /**
     * Convert this ProxySuccess to XML.
     *
     * @param \DOMElement|null $parent The element we should append to.
     * @return \DOMElement This ProxySuccess-element.
     */
    public function toXML(?DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);

        $this->getProxyTicket()->toXML($e);

        return $e;
    }
}
