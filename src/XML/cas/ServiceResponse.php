<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XML\Exception\MissingElementException;
use SimpleSAML\XML\Exception\TooManyElementsException;

/**
 * Class for CAS serviceResponse
 *
 * @package simplesamlphp/cas
 */
class ServiceResponse extends AbstractCasElement
{
    /** @var string */
    public const LOCALNAME = 'serviceResponse';

    /** @var \SimpleSAML\CAS\XML\cas\AbstractResponse */
    protected AbstractResponse $response;


    /**
     * Initialize a cas:serviceResponse element
     *
     * @param \SimpleSAML\CAS\XML\cas\AbstractResponse $response
     */
    final public function __construct(AbstractResponse $response)
    {
        $this->setResponse($response);
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\AbstractResponse
     */
    public function getResponse(): AbstractResponse
    {
        return $this->response;
    }


    /**
     * @param \SimpleSAML\CAS\XML\cas\AbstractResponse $response
     */
    private function setResponse(AbstractResponse $response): void
    {
        $this->response = $response;
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
    public static function fromXML(DOMElement $xml): self
    {
        Assert::same($xml->localName, 'serviceResponse', InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, ServiceResponse::NS, InvalidDOMElementException::class);

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
    public function toXML(DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);

        $this->response->toXML($e);

        return $e;
    }
}
