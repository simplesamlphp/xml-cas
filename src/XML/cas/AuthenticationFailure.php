<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\CAS\Error;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XML\Exception\MissingAttributeException;
use SimpleSAML\XML\StringElementTrait;
use ValueError;

use function is_string;
use function trim;

/**
 * Class for CAS authenticationFailure
 *
 * @package simplesamlphp/cas
 */
final class AuthenticationFailure extends AbstractResponse
{
    use StringElementTrait;

    /** @var string */
    final public const LOCALNAME = 'authenticationFailure';


    /**
     * Create a new instance of AuthenticationFailure
     *
     * @param string $content
     * @param \SimpleSAML\CAS\Error|string $code
     */
    final public function __construct(
        string $content,
        protected Error|string $code,
    ) {
        $this->setContent($content);
    }


    /**
     * Collect the value of the code-property
     *
     * @return \SimpleSAML\CAS\Error|string
     */
    public function getCode(): Error|string
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
        Assert::same($xml->localName, static::getLocalName(), InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, static::getNamespaceURI(), InvalidDOMElementException::class);
        Assert::true(
            $xml->hasAttribute('code'),
            'Missing code from ' . static::getLocalName(),
            MissingAttributeException::class,
        );

        try {
            $code = Error::from(self::getAttribute($xml, 'code'));
        } catch (ValueError) {
            $code = self::getAttribute($xml, 'code');
        }

        return new static(trim($xml->textContent), $code);
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
        $e->textContent = $this->getContent();

        $code = $this->getCode();
        $e->setAttribute('code', is_string($code) ? $code : $code->value);

        return $e;
    }
}
