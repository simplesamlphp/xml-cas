<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use DOMElement;

/**
 * Class for CAS serviceResponse
 *
 * @package simplesamlphp/xml-cas
 */
abstract class AbstractServiceResponse extends AbstractCasElement
{
    /** @var string */
    final public const LOCALNAME = 'serviceResponse';


    /**
     * Initialize a cas:serviceResponse element
     *
     * @param \SimpleSAML\CAS\XML\AbstractResponse $response
     */
    public function __construct(
        protected AbstractResponse $response,
    ) {
    }


    /**
     * @return \SimpleSAML\CAS\XML\AbstractResponse
     */
    public function getResponse(): AbstractResponse
    {
        return $this->response;
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
