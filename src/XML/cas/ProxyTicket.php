<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use SimpleSAML\XML\TypedTextContentTrait;
use SimpleSAML\XMLSchema\Type\Builtin\StringValue;

/**
 * Class for CAS proxyTicket
 *
 * @package simplesamlphp/cas
 */
final class ProxyTicket extends AbstractCasElement
{
    use TypedTextContentTrait;

    /** @var string */
    public const TEXTCONTENT_TYPE = StringValue::class;

    /** @var string */
    final public const LOCALNAME = 'proxyTicket';
}
