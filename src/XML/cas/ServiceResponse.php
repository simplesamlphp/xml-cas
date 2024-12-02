<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XML\Exception\MissingElementException;
use SimpleSAML\XML\Exception\TooManyElementsException;

use function array_merge;
use function array_pop;

/**
 * Class for CAS serviceResponse
 *
 * @package simplesamlphp/cas
 */
final class ServiceResponse extends AbstractCasElement
{
    /** @var string */
    final public const LOCALNAME = 'serviceResponse';


    /**
     * Initialize a cas:serviceResponse element
     *
     * @param \SimpleSAML\CAS\XML\cas\AbstractResponse $response
     */
    final public function __construct(
        protected AbstractResponse $response,
    ) {
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\AbstractResponse
     */
    public function getResponse(): AbstractResponse
    {
        return $this->response;
    }


    /**
     * Convert XML into a cas:serviceResponse-element
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
        Assert::same($xml->localName, static::getLocalName(), InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, static::getNamespaceURI(), InvalidDOMElementException::class);

        $authenticationSuccess = AuthenticationSuccess::getChildrenOfClass($xml);
        $authenticationFailure = AuthenticationFailure::getChildrenOfClass($xml);
        $proxySuccess = ProxySuccess::getChildrenOfClass($xml);
        $proxyFailure = ProxyFailure::getChildrenOfClass($xml);

        $response = array_merge($authenticationSuccess, $authenticationFailure, $proxySuccess, $proxyFailure);
        Assert::notEmpty(
            $response,
            'The <cas:serviceResponse> must contain exactly one of <cas:authenticationSuccess>,'
            . ' <cas:authenticationFailure>, <cas:proxySuccess> or <cas:proxyFailure>.',
            MissingElementException::class,
        );
        Assert::count(
            $response,
            1,
            'The <cas:serviceResponse> must contain exactly one of <cas:authenticationSuccess>,'
            . ' <cas:authenticationFailure>, <cas:proxySuccess> or <cas:proxyFailure>.',
            TooManyElementsException::class,
        );

        return new static(array_pop($response));
    }


    /**
     * Convert this ServiceResponse to XML.
     *
     * @param \DOMElement|null $parent The element we should append this ServiceResponse to.
     * @return \DOMElement
     */
    public function toXML(?DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);

        $this->getResponse()->toXML($e);

        return $e;
    }
}
