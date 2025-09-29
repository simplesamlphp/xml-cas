<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use SimpleSAML\XML\TypedTextContentTrait;
use SimpleSAML\XMLSchema\Type\StringValue;

/**
 * Class for CAS user
 *
 * @package simplesamlphp/cas
 */
final class User extends AbstractCasElement
{
    use TypedTextContentTrait;


    /** @var string */
    public const TEXTCONTENT_TYPE = StringValue::class;

    /** @var string */
    final public const LOCALNAME = 'user';
}
