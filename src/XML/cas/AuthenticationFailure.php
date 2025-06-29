<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use DOMElement;
use SimpleSAML\CAS\Assert\Assert;
use SimpleSAML\CAS\Type\CodeValue;
use SimpleSAML\XML\TypedTextContentTrait;
use SimpleSAML\XMLSchema\Exception\InvalidDOMElementException;
use SimpleSAML\XMLSchema\Type\Builtin\StringValue;

use function strval;

/**
 * Class for CAS authenticationFailure
 *
 * @package simplesamlphp/cas
 */
final class AuthenticationFailure extends AbstractResponse
{
    use TypedTextContentTrait;

    /** @var string */
    public const TEXTCONTENT_TYPE = StringValue::class;

    /** @var string */
    final public const LOCALNAME = 'authenticationFailure';


    /**
     * Create a new instance of AuthenticationFailure
     *
     * @param \SimpleSAML\XMLSchema\Type\Builtin\StringValue $content
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
     * Initialize an AuthenticationFailure element.
     *
     * @param \DOMElement $xml The XML element we should load.
     * @return static
     *
     * @throws \SimpleSAML\XMLSchema\Exception\InvalidDOMElementException
     *   if the qualified name of the supplied element is wrong
     * @throws \SimpleSAML\XMLSchema\Exception\MissingAttributeException
     *   if the supplied element is missing any of the mandatory attributes
     */
    public static function fromXML(DOMElement $xml): static
    {
        Assert::same($xml->localName, static::getLocalName(), InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, static::getNamespaceURI(), InvalidDOMElementException::class);

        return new static(
            StringValue::fromString($xml->textContent),
            self::getAttribute($xml, 'code', CodeValue::class),
        );
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

        $e->setAttribute('code', strval($this->getCode()));
        $e->textContent = strval($this->getContent());

        return $e;
    }
}
