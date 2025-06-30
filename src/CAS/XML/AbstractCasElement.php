<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use SimpleSAML\CAS\Constants as C;
use SimpleSAML\XML\AbstractElement;

/**
 * Abstract class to be implemented by all the classes in this namespace
 *
 * @package simplesamlphp/cas
 */
abstract class AbstractCasElement extends AbstractElement
{
    /** @var string */
    public const NS = C::NS_CAS;

    /** @var string */
    public const NS_PREFIX = 'cas';

    /** @var string */
    public const SCHEMA = 'resources/schemas/cas-server-protocol-3.0.xsd';
}
