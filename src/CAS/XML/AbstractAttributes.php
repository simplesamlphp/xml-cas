<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use DOMElement;
use SimpleSAML\CAS\Constants as C;
use SimpleSAML\XML\ExtendableElementTrait;
use SimpleSAML\XMLSchema\XML\Constants\NS;

/**
 * Class for CAS attributes
 *
 * @package simplesamlphp/xml-cas
 */
abstract class AbstractAttributes extends AbstractCasElement
{
    use ExtendableElementTrait;


    final public const string LOCALNAME = 'attributes';

    /** The namespace-attribute for the xs:any element */
    final public const string XS_ANY_ELT_NAMESPACE = NS::ANY;

    /** The exclusions for the xs:any element */
    final public const array XS_ANY_ELT_EXCLUSIONS = [
        [C::NS_CAS, 'authenticationDate'],
        [C::NS_CAS, 'longTermAuthenticationRequestTokenUsed'],
        [C::NS_CAS, 'isFromNewLogin'],
    ];


    /**
     * Initialize a cas:attributes element
     *
     * @param \SimpleSAML\CAS\XML\AuthenticationDate $authenticationDate
     * @param \SimpleSAML\CAS\XML\LongTermAuthenticationRequestTokenUsed $longTermAuthenticationRequestTokenUsed
     * @param \SimpleSAML\CAS\XML\IsFromNewLogin $isFromNewLogin
     * @param list<\SimpleSAML\XML\SerializableElementInterface> $elts
     */
    public function __construct(
        protected AuthenticationDate $authenticationDate,
        protected LongTermAuthenticationRequestTokenUsed $longTermAuthenticationRequestTokenUsed,
        protected IsFromNewLogin $isFromNewLogin,
        array $elts = [],
    ) {
        $this->setElements($elts);
    }


    /**
     * @return \SimpleSAML\CAS\XML\AuthenticationDate
     */
    public function getAuthenticationDate(): AuthenticationDate
    {
        return $this->authenticationDate;
    }


    /**
     * @return \SimpleSAML\CAS\XML\LongTermAuthenticationRequestTokenUsed
     */
    public function getLongTermAuthenticationRequestTokenUsed(): LongTermAuthenticationRequestTokenUsed
    {
        return $this->longTermAuthenticationRequestTokenUsed;
    }


    /**
     * @return \SimpleSAML\CAS\XML\IsFromNewLogin
     */
    public function getIsFromNewLogin(): IsFromNewLogin
    {
        return $this->isFromNewLogin;
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

        $this->getAuthenticationDate()->toXML($e);
        $this->getLongTermAuthenticationRequestTokenUsed()->toXML($e);
        $this->getIsFromNewLogin()->toXML($e);

        foreach ($this->elements as $elt) {
            if (!$elt->isEmptyElement()) {
                $elt->toXML($e);
            }
        }

        return $e;
    }
}
