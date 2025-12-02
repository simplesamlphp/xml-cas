<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use DOMElement;
use SimpleSAML\CAS\Type\CodeValue;
use SimpleSAML\XML\TypedTextContentTrait;
use SimpleSAML\XMLSchema\Type\StringValue;

/**
 * Class for CAS proxyFailure
 *
 * @package simplesamlphp/xml-cas
 */
abstract class AbstractProxyFailure extends AbstractResponse
{
    use TypedTextContentTrait;


    /** @var string */
    public const TEXTCONTENT_TYPE = StringValue::class;

    /** @var string */
    final public const LOCALNAME = 'proxyFailure';


    /**
     * Create a new instance of ProxyFailure
     *
     * @param \SimpleSAML\XMLSchema\Type\StringValue $content
     * @param \SimpleSAML\CAS\Type\CodeValue $code
     */
    final public function __construct(
        StringValue $content,
        protected CodeValue $code,
    ) {
        $this->setContent($content);
    }


    /**
     * Collect the value of the code-property
     *
     * @return \SimpleSAML\CAS\Type\CodeValue
     */
    public function getCode(): CodeValue
    {
        return $this->code;
    }


    /**
     * Convert this ProxyFailure to XML.
     *
     * @param \DOMElement|null $parent The element we should append to.
     * @return \DOMElement This ProxyFailure-element.
     */
    public function toXML(?DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);

        $e->textContent = $this->getContent()->getValue();
        $e->setAttribute('code', $this->getCode()->getValue());

        return $e;
    }
}
