<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use SimpleSAML\XML\TypedTextContentTrait;
use SimpleSAML\XMLSchema\Type\Builtin\BooleanValue;

/**
 * Class for CAS isFromNewLogin
 *
 * @package simplesamlphp/cas
 */
final class IsFromNewLogin extends AbstractCasElement
{
    use TypedTextContentTrait;

    /** @var string */
    public const TEXTCONTENT_TYPE = BooleanValue::class;

    /** @var string */
    final public const LOCALNAME = 'isFromNewLogin';
}
