<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use SimpleSAML\XML\TypedTextContentTrait;
use SimpleSAML\XMLSchema\Type\BooleanValue;

/**
 * Class for CAS longTermAuthenticationRequestTokenUsed
 *
 * @package simplesamlphp/xml-cas
 */
final class LongTermAuthenticationRequestTokenUsed extends AbstractCasElement
{
    use TypedTextContentTrait;


    public const string TEXTCONTENT_TYPE = BooleanValue::class;

    final public const string LOCALNAME = 'longTermAuthenticationRequestTokenUsed';
}
