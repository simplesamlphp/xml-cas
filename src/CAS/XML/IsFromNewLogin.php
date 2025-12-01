<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use SimpleSAML\XML\TypedTextContentTrait;
use SimpleSAML\XMLSchema\Type\BooleanValue;

/**
 * Class for CAS isFromNewLogin
 *
 * @package simplesamlphp/xml-cas
 */
final class IsFromNewLogin extends AbstractCasElement
{
    use TypedTextContentTrait;


    /** @var string */
    public const TEXTCONTENT_TYPE = BooleanValue::class;

    /** @var string */
    final public const LOCALNAME = 'isFromNewLogin';
}
