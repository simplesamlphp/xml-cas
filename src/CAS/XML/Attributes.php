<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use DOMElement;
use SimpleSAML\CAS\Assert\Assert;
use SimpleSAML\CAS\Constants as C;
use SimpleSAML\XML\ExtendableElementTrait;
use SimpleSAML\XMLSchema\Exception\InvalidDOMElementException;
use SimpleSAML\XMLSchema\Exception\MissingElementException;
use SimpleSAML\XMLSchema\XML\Constants\NS;

/**
 * Class for CAS attributes
 *
 * @package simplesamlphp/cas
 */
final class Attributes extends AbstractCasElement
{
    use ExtendableElementTrait;


    /** @var string */
    final public const LOCALNAME = 'attributes';

    /** The namespace-attribute for the xs:any element */
    final public const XS_ANY_ELT_NAMESPACE = NS::ANY;

    /** The exclusions for the xs:any element */
    final public const XS_ANY_ELT_EXCLUSIONS = [
        [C::NS_CAS, 'authenticationDate'],
        [C::NS_CAS, 'longTermAuthenticationRequestTokenUsed'],
        [C::NS_CAS, 'isFromNewLogin'],
    ];


    /**
     * Initialize a cas:attributes element
     *
     * @param \SimpleSAML\CAS\XML\AuthenticationDate|null $authenticationDate
     * @param \SimpleSAML\CAS\XML\LongTermAuthenticationRequestTokenUsed|null $longTermAuthenticationRequestTokenUsed
     * @param \SimpleSAML\CAS\XML\IsFromNewLogin|null $isFromNewLogin
     * @param list<\SimpleSAML\XML\SerializableElementInterface> $elts
     */
    final public function __construct(
        protected ?AuthenticationDate $authenticationDate,
        protected ?LongTermAuthenticationRequestTokenUsed $longTermAuthenticationRequestTokenUsed,
        protected ?IsFromNewLogin $isFromNewLogin,
        array $elts = [],
    ) {
        $this->setElements($elts);
    }


    /**
     * @return \SimpleSAML\CAS\XML\AuthenticationDate|null
     */
    public function getAuthenticationDate(): ?AuthenticationDate
    {
        return $this->authenticationDate;
    }


    /**
     * @return \SimpleSAML\CAS\XML\LongTermAuthenticationRequestTokenUsed|null
     */
    public function getLongTermAuthenticationRequestTokenUsed(): ?LongTermAuthenticationRequestTokenUsed
    {
        return $this->longTermAuthenticationRequestTokenUsed;
    }


    /**
     * @return \SimpleSAML\CAS\XML\IsFromNewLogin|null
     */
    public function getIsFromNewLogin(): ?IsFromNewLogin
    {
        return $this->isFromNewLogin;
    }


    /**
     * Convert XML into a cas:attributes-element
     *
     * This parser supports a "lenient" mode for interoperability with CAS servers
     * that omit core CAS attributes but still embed additional attributes, such as
     * Technolutions Slate. In Slate's CAS responses, <cas:attributes> may only
     * contain custom fields (e.g. cas:firstname, cas:lastname, cas:email) and
     * omit the CAS 3.0 attributes authenticationDate, longTermAuthenticationRequestTokenUsed
     * and isFromNewLogin. To allow consuming those responses, strict parsing is
     * disabled by default and can be enabled explicitly via $strictParsing.
     *
     * @param \DOMElement $xml The XML element we should load
     * @param bool        $strictParsing Whether to enforce CAS 3.0 required attributes
     * @return static
     *
     * @throws \SimpleSAML\XMLSchema\Exception\InvalidDOMElementException
     *   if the qualified name of the supplied element is wrong
     * @throws \SimpleSAML\XMLSchema\Exception\MissingAttributeException
     *   if the supplied element is missing one of the mandatory attributes
     */
    public static function fromXML(DOMElement $xml, bool $strictParsing = false): static
    {
        Assert::same($xml->localName, static::getLocalName(), InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, static::getNamespaceURI(), InvalidDOMElementException::class);

        $authenticationDate = AuthenticationDate::getChildrenOfClass($xml);
        self::assertZeroOrOneOrExactlyOneWhenStrict(
            $authenticationDate,
            $strictParsing,
            'Exactly one <cas:authenticationDate> must be specified.',
            'At most one <cas:authenticationDate> may be specified.',
        );

        $longTermAuthenticationRequestTokenUsed = LongTermAuthenticationRequestTokenUsed::getChildrenOfClass($xml);
        self::assertZeroOrOneOrExactlyOneWhenStrict(
            $longTermAuthenticationRequestTokenUsed,
            $strictParsing,
            'Exactly one <cas:longTermAuthenticationRequestTokenUsed> must be specified.',
            'At most one <cas:longTermAuthenticationRequestTokenUsed> may be specified.',
        );

        $isFromNewLogin = IsFromNewLogin::getChildrenOfClass($xml);
        self::assertZeroOrOneOrExactlyOneWhenStrict(
            $isFromNewLogin,
            $strictParsing,
            'Exactly one <cas:isFromNewLogin> must be specified.',
            'At most one <cas:isFromNewLogin> may be specified.',
        );

        return new static(
            $authenticationDate[0] ?? null,
            $longTermAuthenticationRequestTokenUsed[0] ?? null,
            $isFromNewLogin[0] ?? null,
            self::getChildElementsFromXML($xml),
        );
    }


    /**
     * Convert this Attributes to XML.
     *
     * @param \DOMElement|null $parent The element we should append this Attributes to.
     * @return \DOMElement
     */
    public function toXML(?DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);

        $this->getAuthenticationDate()?->toXML($e);
        $this->getLongTermAuthenticationRequestTokenUsed()?->toXML($e);
        $this->getIsFromNewLogin()?->toXML($e);

        /** @psalm-var \SimpleSAML\XML\SerializableElementInterface $elt */
        foreach ($this->elements as $elt) {
            if (!$elt->isEmptyElement()) {
                $elt->toXML($e);
            }
        }

        return $e;
    }


    /**
     * Assert that an array contains exactly one element when strict parsing is enabled,
     * or zero or one element when strict parsing is disabled.
     *
     * @param array<mixed> $elements The array to check
     * @param bool $strict Whether strict parsing is enabled
     * @param string $exactlyOneMessage The message to show when exactly one element is required
     * @param string $atMostOneMessage The message to show when at most one element is allowed
     * @return void
     *
     * @throws \SimpleSAML\XMLSchema\Exception\MissingElementException
     *   if the array contains more than one element, or if strict parsing is enabled and the array is empty
     */
    private static function assertZeroOrOneOrExactlyOneWhenStrict(
        array $elements,
        bool $strict,
        string $exactlyOneMessage,
        string $atMostOneMessage,
    ): void {
        if ($strict) {
            Assert::count($elements, 1, $exactlyOneMessage, MissingElementException::class);
            return;
        }

        if (count($elements) > 1) {
            throw new MissingElementException($atMostOneMessage);
        }
    }
}
