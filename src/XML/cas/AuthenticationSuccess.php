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
final class AuthenticationSuccess extends AbstractResponse
{
    /** @var string */
    public const LOCALNAME = 'authenticationSuccess';


    /**
     * Initialize a cas:authenticationSuccess element
     *
     * @param \SimpleSAML\CAS\XML\cas\User $user
     * @param \SimpleSAML\CAS\XML\cas\Attributes $attributes
     * @param \SimpleSAML\CAS\XML\cas\ProxyGrantingTicket|null $proxyGrantingTicket
     * @param \SimpleSAML\CAS\XML\cas\Proxies|null $proxies
     */
    final public function __construct(
        protected User $user,
        protected Attributes $attributes,
        protected ?ProxyGrantingTicket $proxyGrantingTicket = null,
        protected ?Proxies $proxies = null,
    ) {
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\User
     */
    public function getUser(): User
    {
        return $this->user;
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\Attributes
     */
    public function getAttributes(): Attributes
    {
        return $this->attributes;
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\ProxyGrantingTicket
     */
    public function getProxyGrantingTicket(): ?ProxyGrantingTicket
    {
        return $this->proxyGrantingTicket;
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\Proxies
     */
    public function getProxies(): ?Proxies
    {
        return $this->proxies;
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
