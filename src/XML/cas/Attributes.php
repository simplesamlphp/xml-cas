<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\CAS\Constants as C;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XML\Exception\MissingElementException;
use SimpleSAML\XML\ExtendableElementTrait;

/**
 * Class for CAS attributes
 *
 * @package simplesamlphp/cas
 */
final class Attributes extends AbstractCasElement
{
    use ExtendableElementTrait;

    /** @var string */
    public const LOCALNAME = 'attributes';

    /** The namespace-attribute for the xs:any element */
    public const XS_ANY_ELT_NAMESPACE = C::XS_ANY_NS_ANY;


    /**
     * Initialize a cas:attributes element
     *
     * @param \SimpleSAML\CAS\XML\cas\AuthenticationDate $authenticationDate
     * @param \SimpleSAML\CAS\XML\cas\LongTermAuthenticationRequestTokenUsed $longTermAuthenticationRequestTokenUsed
     * @param \SimpleSAML\CAS\XML\cas\IsFromNewLogin $isFromNewLogin
     * @param \SimpleSAML\XML\Chunk[] $elts
     */
    final public function __construct(
        protected AuthenticationDate $authenticationDate,
        protected LongTermAuthenticationRequestTokenUsed $longTermAuthenticationRequestTokenUsed,
        protected IsFromNewLogin $isFromNewLogin,
        array $elts = [],
    ) {
        $this->setElements($elts);
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\AuthenticationDate
     */
    public function getAuthenticationDate(): AuthenticationDate
    {
        return $this->authenticationDate;
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\LongTermAuthenticationRequestTokenUsed
     */
    public function getLongTermAuthenticationRequestTokenUsed(): LongTermAuthenticationRequestTokenUsed
    {
        return $this->longTermAuthenticationRequestTokenUsed;
    }


    /**
     * @return \SimpleSAML\CAS\XML\cas\IsFromNewLogin
     */
    public function getIsFromNewLogin(): IsFromNewLogin
    {
        return $this->isFromNewLogin;
    }


    /**
     * Convert XML into a cas:attributes-element
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
        Assert::same($xml->localName, 'attributes', InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, Attributes::NS, InvalidDOMElementException::class);

        $authenticationDate = AuthenticationDate::getChildrenOfClass($xml);
        Assert::count(
            $authenticationDate,
            1,
            'Exactly one <cas:authenticationDate> must be specified.',
            MissingElementException::class,
        );

        $longTermAuthenticationRequestTokenUsed = LongTermAuthenticationRequestTokenUsed::getChildrenOfClass($xml);
        Assert::count(
            $longTermAuthenticationRequestTokenUsed,
            1,
            'Exactly one <cas:longTermAuthenticationRequestTokenUsed> must be specified.',
            MissingElementException::class,
        );

        $isFromNewLogin = IsFromNewLogin::getChildrenOfClass($xml);
        Assert::count(
            $isFromNewLogin,
            1,
            'Exactly least one <cas:isFromNewLogin> must be specified.',
            MissingElementException::class,
        );

        $elts = [];
        foreach ($xml->childNodes as $elt) {
            if (!($elt instanceof DOMElement)) {
                continue;
            } elseif ($elt->namespaceURI === C::NS_CAS) {
                switch ($elt->localName) {
                    case 'authenticationDate':
                    case 'longTermAuthenticationRequestTokenUsed':
                    case 'isFromNewLogin':
                        continue 2;
                }
            }

            $elts[] = new Chunk($elt);
        }

        return new static(
            array_pop($authenticationDate),
            array_pop($longTermAuthenticationRequestTokenUsed),
            array_pop($isFromNewLogin),
            $elts,
        );
    }


    /**
     * Convert this Attributes to XML.
     *
     * @param \DOMElement|null $parent The element we should append this Attributes to.
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);

        $this->getAuthenticationDate()->toXML($e);
        $this->getLongTermAuthenticationRequestTokenUsed()->toXML($e);
        $this->getIsFromNewLogin()->toXML($e);

        /** @psalm-var \SimpleSAML\XML\SerializableElementInterface $elt */
        foreach ($this->elements as $elt) {
            if (!$elt->isEmptyElement()) {
                $elt->toXML($e);
            }
        }

        return $e;
    }
}
