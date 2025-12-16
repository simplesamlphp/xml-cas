<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use SimpleSAML\XML\TypedTextContentTrait;
use SimpleSAML\XMLSchema\Type\StringValue;

/**
 * Class for CAS user
 *
 * @package simplesamlphp/xml-cas
 */
final class User extends AbstractCasElement
{
    use TypedTextContentTrait;


    public const string TEXTCONTENT_TYPE = StringValue::class;

    final public const string LOCALNAME = 'user';
}
