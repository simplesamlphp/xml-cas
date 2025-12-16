<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use DOMElement;
use SimpleSAML\CAS\Assert\Assert;
use SimpleSAML\CAS\Constants as C;
use SimpleSAML\XMLSchema\Exception\MissingElementException;

/**
 * Class for CAS proxies
 *
 * @package simplesamlphp/xml-cas
 */
abstract class AbstractProxies extends AbstractCasElement
{
    final public const string LOCALNAME = 'proxies';


    /**
     * Initialize a Proxies element.
     *
     * @param \SimpleSAML\CAS\XML\Proxy[] $proxy
     */
    public function __construct(
        protected array $proxy = [],
    ) {
        Assert::maxCount($proxy, C::UNBOUNDED_LIMIT);
        Assert::allIsInstanceOf($proxy, Proxy::class);
        Assert::minCount($proxy, 1, 'Missing at least one Proxy in Proxies.', MissingElementException::class);
    }


    /**
     * @return \SimpleSAML\CAS\XML\Proxy[]
     */
    public function getProxy(): array
    {
        return $this->proxy;
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
