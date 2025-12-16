<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use DOMElement;

/**
 * Class for CAS proxySuccess
 *
 * @package simplesamlphp/xml-cas
 */
abstract class AbstractProxySuccess extends AbstractResponse
{
    final public const string LOCALNAME = 'proxySuccess';


    /**
     * Initialize a cas:proxySuccess element
     *
     * @param \SimpleSAML\CAS\XML\ProxyTicket $proxyTicket
     */
    final public function __construct(
        protected ProxyTicket $proxyTicket,
    ) {
    }


    /**
     * @return \SimpleSAML\CAS\XML\ProxyTicket
     */
    public function getProxyTicket(): ProxyTicket
    {
        return $this->proxyTicket;
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
