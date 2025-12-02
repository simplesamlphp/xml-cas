<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use DOMElement;

/**
 * Class for CAS authenticationSuccess
 *
 * @package simplesamlphp/xml-cas
 */
abstract class AbstractAuthenticationSuccess extends AbstractResponse
{
    /** @var string */
    final public const LOCALNAME = 'authenticationSuccess';


    /**
     * Initialize a cas:authenticationSuccess element
     *
     * @param \SimpleSAML\CAS\XML\User $user
     * @param \SimpleSAML\CAS\XML\Attributes $attributes
     * @param \SimpleSAML\CAS\XML\ProxyGrantingTicket|null $proxyGrantingTicket
     * @param \SimpleSAML\CAS\XML\Proxies|null $proxies
     */
    public function __construct(
        protected User $user,
        protected Attributes $attributes,
        protected ?ProxyGrantingTicket $proxyGrantingTicket = null,
        protected ?Proxies $proxies = null,
    ) {
    }


    /**
     * @return \SimpleSAML\CAS\XML\User
     */
    public function getUser(): User
    {
        return $this->user;
    }


    /**
     * @return \SimpleSAML\CAS\XML\Attributes
     */
    public function getAttributes(): Attributes
    {
        return $this->attributes;
    }


    /**
     * @return \SimpleSAML\CAS\XML\ProxyGrantingTicket
     */
    public function getProxyGrantingTicket(): ?ProxyGrantingTicket
    {
        return $this->proxyGrantingTicket;
    }


    /**
     * @return \SimpleSAML\CAS\XML\Proxies
     */
    public function getProxies(): ?Proxies
    {
        return $this->proxies;
    }


    /**
     * Convert this AuthenticationSuccess to XML.
     *
     * @param \DOMElement|null $parent The element we should append this AuthenticationSuccess to.
     * @return \DOMElement
     */
    public function toXML(?DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);

        $this->getUser()->toXML($e);
        $this->getAttributes()->toXML($e);
        $this->getProxyGrantingTicket()?->toXML($e);
        $this->getProxies()?->toXML($e);

        return $e;
    }
}
