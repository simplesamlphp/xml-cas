<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\CAS\Constants as C;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XML\Exception\MissingElementException;

/**
 * Class for CAS proxies
 *
 * @package simplesamlphp/cas
 */
final class Proxies extends AbstractCasElement
{
    /** @var string */
    final public const LOCALNAME = 'proxies';


    /**
     * Initialize a Proxies element.
     *
     * @param \SimpleSAML\CAS\XML\cas\Proxy[] $proxy
     */
    final public function __construct(
        protected array $proxy = [],
    ) {
        Assert::maxCount($proxy, C::UNBOUNDED_LIMIT);
        Assert::allIsInstanceOf($proxy, Proxy::class);
        Assert::minCount($proxy, 1, 'Missing at least one Proxy in Proxies.', MissingElementException::class);
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\Proxy[]
     */
    public function getProxy(): array
    {
        return $this->proxy;
    }


    /**
     * Convert XML into a Proxies-element
     *
     * @param \DOMElement $xml The XML element we should load
     * @return static
     *
     * @throws \SimpleSAML\XML\Exception\InvalidDOMElementException
     *  if the qualified name of the supplied element is wrong
     * @throws \SimpleSAML\XML\Exception\MissingElementException if one of the mandatory child-elements is missing
     * @throws \SimpleSAML\XML\Exception\TooManyElementsException if too many child-elements of a type are specified
     */
    public static function fromXML(DOMElement $xml): static
    {
        Assert::same($xml->localName, static::getLocalName(), InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, static::getNamespaceURI(), InvalidDOMElementException::class);

        $proxy = Proxy::getChildrenOfClass($xml);
        Assert::minCount($proxy, 1, 'Missing at least one Proxy in Proxies.', MissingElementException::class);

        return new static($proxy);
    }


    /**
     * Convert this Proxies to XML.
     *
     * @param \DOMElement|null $parent The element we should append this Proxies to.
     * @return \DOMElement
     */
    public function toXML(?DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);

        foreach ($this->getProxy() as $proxy) {
            $proxy->toXML($e);
        }

        return $e;
    }
}
