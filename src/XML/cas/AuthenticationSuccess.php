<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\CAS\Constants as C;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XML\Exception\MissingElementException;

/**
 * Class for CAS authenticationSuccess
 *
 * @package simplesamlphp/cas
 */
class AuthenticationSuccess extends AbstractResponse
{
    /** @var string */
    public const LOCALNAME = 'authenticationSuccess';

    /** @var \SimpleSAML\CAS\XML\cas\User */
    protected User $user;

    /** @var \SimpleSAML\CAS\XML\cas\Attributes */
    protected Attributes $attributes;

    /** @var \SimpleSAML\CAS\XML\cas\ProxyGrantingTicket|null */
    protected ?ProxyGrantingTicket $proxyGrantingTicket = null;

    /** @var \SimpleSAML\CAS\XML\cas\Proxies|null */
    protected ?Proxies $proxies = null;


    /**
     * Initialize a cas:authenticationSuccess element
     *
     * @param \SimpleSAML\CAS\XML\cas\User $user
     * @param \SimpleSAML\CAS\XML\cas\Attributes $attributes
     * @param \SimpleSAML\CAS\XML\cas\ProxyGrantingTicket|null $proxyGrantingTicket
     * @param \SimpleSAML\CAS\XML\cas\Proxies|null $proxies
     */
    final public function __construct(
        User $user,
        Attributes $attributes,
        ?ProxyGrantingTicket $proxyGrantingTicket = null,
        ?Proxies $proxies = null,
    ) {
        $this->setUser($user);
        $this->setAttributes($attributes);
        $this->setProxyGrantingTicket($proxyGrantingTicket);
        $this->setProxies($proxies);
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\User
     */
    public function getUser(): User
    {
        return $this->user;
    }


    /**
     * @param \SimpleSAML\CAS\XML\cas\User $user
     */
    private function setUser(User $user): void
    {
        $this->user = $user;
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\Attributes
     */
    public function getAttributes(): Attributes
    {
        return $this->attributes;
    }


    /**
     * @param \SimpleSAML\CAS\XML\cas\Attributes $attributes
     */
    private function setAttributes(Attributes $attributes): void
    {
        $this->attributes = $attributes;
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\ProxyGrantingTicket
     */
    public function getProxyGrantingTicket(): ?ProxyGrantingTicket
    {
        return $this->proxyGrantingTicket;
    }


    /**
     * @param \SimpleSAML\CAS\XML\cas\ProxyGrantingTicket $proxyGrantingTicket
     */
    private function setProxyGrantingTicket(?ProxyGrantingTicket $proxyGrantingTicket): void
    {
        $this->proxyGrantingTicket = $proxyGrantingTicket;
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\Proxies
     */
    public function getProxies(): ?Proxies
    {
        return $this->proxies;
    }


    /**
     * @param \SimpleSAML\CAS\XML\cas\Proxies $proxies
     */
    private function setProxies(?Proxies $proxies): void
    {
        $this->proxies = $proxies;
    }


    /**
     * Convert XML into a cas:authenticationSuccess-element
     *
     * @param \DOMElement $xml The XML element we should load
     * @return static
     *
     * @throws \SimpleSAML\XML\Exception\InvalidDOMElementException
     *   if the qualified name of the supplied element is wrong
     * @throws \SimpleSAML\XML\Exception\MissingAttributeException
     *   if the supplied element is missing one of the mandatory attributes
     */
    public static function fromXML(DOMElement $xml): static
    {
        Assert::same($xml->localName, 'authenticationSuccess', InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, AuthenticationSuccess::NS, InvalidDOMElementException::class);

        $user = User::getChildrenOfClass($xml);
        Assert::count(
            $user,
            1,
            'Exactly one <cas:user> must be specified.',
            MissingElementException::class,
        );

        $attributes = Attributes::getChildrenOfClass($xml);
        Assert::count(
            $attributes,
            1,
            'Exactly one <cas:attributes> must be specified.',
            MissingElementException::class,
        );

        $proxyGrantingTicket = ProxyGrantingTicket::getChildrenOfClass($xml);
        $proxies = Proxies::getChildrenOfClass($xml);

        return new static(
            array_pop($user),
            array_pop($attributes),
            array_pop($proxyGrantingTicket),
            array_pop($proxies),
        );
    }


    /**
     * Convert this AuthenticationSuccess to XML.
     *
     * @param \DOMElement|null $parent The element we should append this AuthenticationSuccess to.
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);

        $this->getUser()->toXML($e);
        $this->getAttributes()->toXML($e);
        $this->getProxyGrantingTicket()?->toXML($e);
        $this->getProxies()?->toXML($e);

        return $e;
    }
}
