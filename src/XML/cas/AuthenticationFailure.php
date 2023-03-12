<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XML\Exception\MissingAttributeException;
use SimpleSAML\XML\StringElementTrait;

/**
 * Class for CAS authenticationFailure
 *
 * @package simplesamlphp/cas
 */
final class AuthenticationFailure extends AbstractResponse
{
    use StringElementTrait;

    /** @var string */
    public const LOCALNAME = 'authenticationFailure';


    /**
     * Create a new instance of AuthenticationFailure
     *
     * @param string $content
     * @param string $code
     */
    final public function __construct(
        string $content,
        protected string $code,
    ) {
        Assert::notEmpty($code, 'The code in AuthenticationFailure must not be a empty.');
        $this->setContent($content);
    }


    /**
     * Collect the value of the code-property
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }


    /**
     * Validate the content of the element.
     *
     * @param string $content  The value to go in the XML textContent
     * @throws \Exception on failure
     * @return void
     */
    protected function validateContent(string $content): void
    {
        Assert::notWhitespaceOnly($content);
    }


    /**
     * Initialize an AuthenticationFailure element.
     *
     * @param \DOMElement $xml The XML element we should load.
     * @return static
     *
     * @throws \SimpleSAML\XML\Exception\InvalidDOMElementException
     *   if the qualified name of the supplied element is wrong
     * @throws \SimpleSAML\XML\Exception\MissingAttributeException
     *   if the supplied element is missing any of the mandatory attributes
     */
    public static function fromXML(DOMElement $xml): static
    {
        Assert::same($xml->localName, 'authenticationFailure', InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, ProxyFailure::NS, InvalidDOMElementException::class);
        Assert::true(
            $xml->hasAttribute('code'),
            'Missing code from ' . static::getLocalName(),
            MissingAttributeException::class,
        );

        /** @psalm-var string $code */
        $code = self::getAttribute($xml, 'code');
        return new static(trim($xml->textContent), $code);
    }


    /**
     * Convert this AuthenticationFailure to XML.
     *
     * @param \DOMElement|null $parent The element we should append to.
     * @return \DOMElement This AuthenticatioFailure-element.
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);
        $e->textContent = $this->getContent();
        $e->setAttribute('code', $this->getCode());

        return $e;
    }
}
