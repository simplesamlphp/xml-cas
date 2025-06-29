<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use SimpleSAML\XML\TypedTextContentTrait;
use SimpleSAML\XMLSchema\Type\Builtin\StringValue;

/**
 * Class for CAS proxyGrantingTicket
 *
 * @package simplesamlphp/cas
 */
final class ProxyGrantingTicket extends AbstractCasElement
{
    use TypedTextContentTrait;

    /** @var string */
    public const TEXTCONTENT_TYPE = StringValue::class;

    /** @var string */
    final public const LOCALNAME = 'proxyGrantingTicket';
}
