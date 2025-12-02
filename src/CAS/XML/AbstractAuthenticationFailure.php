<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use DOMElement;
use SimpleSAML\CAS\Type\CodeValue;
use SimpleSAML\XML\TypedTextContentTrait;
use SimpleSAML\XMLSchema\Type\StringValue;

/**
 * Class for CAS authenticationFailure
 *
 * @package simplesamlphp/xml-cas
 */
abstract class AbstractAuthenticationFailure extends AbstractResponse
{
    use TypedTextContentTrait;


    /** @var string */
    public const TEXTCONTENT_TYPE = StringValue::class;

    /** @var string */
    final public const LOCALNAME = 'authenticationFailure';


    /**
     * Create a new instance of AuthenticationFailure
     *
     * @param \SimpleSAML\XMLSchema\Type\StringValue $content
     * @param \SimpleSAML\CAS\Type\CodeValue $code
     */
    public function __construct(
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
     * Convert this AuthenticationFailure to XML.
     *
     * @param \DOMElement|null $parent The element we should append to.
     * @return \DOMElement This AuthenticatioFailure-element.
     */
    public function toXML(?DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);

        $e->setAttribute('code', $this->getCode()->getValue());
        $e->textContent = $this->getContent()->getValue();

        return $e;
    }
}
