<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use Dom;

/**
 * Class for CAS serviceResponse
 *
 * @package simplesamlphp/xml-cas
 */
abstract class AbstractServiceResponse extends AbstractCasElement
{
    final public const string LOCALNAME = 'serviceResponse';


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
     * @param \Dom\Element|null $parent The element we should append this ServiceResponse to.
     * @return \Dom\Element
     */
    public function toXML(?Dom\Element $parent = null): Dom\Element
    {
        $e = $this->instantiateParentElement($parent);

        $this->getResponse()->toXML($e);

        return $e;
    }
}
