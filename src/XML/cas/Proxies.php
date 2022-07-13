<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XML\Exception\MissingElementException;

/**
 * Class for CAS proxies
 *
 * @package simplesamlphp/cas
 */
class Proxies extends AbstractCasElement
{
    /** @var string */
    public const LOCALNAME = 'proxies';

    /** @var \SimpleSAML\CAS\XML\cas\Proxy[] $proxy */
    protected array $proxy = [];


    /**
     * Initialize a Proxies element.
     *
     * @param \SimpleSAML\CAS\XML\cas\Proxy[] $proxy
     */
    public function __construct(array $proxy = [])
    {
        $this->setProxy($proxy);
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\Proxy[]
     */
    public function getProxy(): array
    {
        return $this->proxy;
    }


    /**
     * @param \SimpleSAML\CAS\XML\cas\Proxy[] $proxy
     */
    private function setProxy(array $proxy): void
    {
        Assert::allIsInstanceOf($proxy, Proxy::class);
        Assert::minCount($proxy, 1, 'Missing at least one Proxy in Proxies.', MissingElementException::class);

        $this->proxy = $proxy;
    }


    /**
     * Convert XML into a Proxies-element
     *
     * @param \DOMElement $xml The XML element we should load
     * @return \SimpleSAML\CAS\XML\cas\Proxies
     *
     * @throws \SimpleSAML\XML\Exception\InvalidDOMElementException if the qualified name of the supplied element is wrong
     * @throws \SimpleSAML\XML\Exception\MissingElementException if one of the mandatory child-elements is missing
     * @throws \SimpleSAML\XML\Exception\TooManyElementsException if too many child-elements of a type are specified
     */
    public static function fromXML(DOMElement $xml): object
    {
        Assert::same($xml->localName, 'proxies', InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, Proxies::NS, InvalidDOMElementException::class);

        $proxy = Proxy::getChildrenOfClass($xml);
        Assert::minCount($proxy, 1, 'Missing at least one Proxy in Proxies.', MissingElementException::class);

        return new self($proxy);
    }


    /**
     * Convert this Proxies to XML.
     *
     * @param \DOMElement|null $parent The element we should append this Proxies to.
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);

        foreach ($this->proxy as $proxy) {
            $proxy->toXML($e);
        }

        return $e;
    }
}
