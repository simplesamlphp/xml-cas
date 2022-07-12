<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use SimpleSAML\CAS\Constants as C;
use SimpleSAML\XML\AbstractXMLElement;

/**
 * Abstract class to be implemented by all the classes in this namespace
 *
 * @package simplesamlphp/cas
 */
abstract class AbstractCasElement extends AbstractXMLElement
{
    /** @var string */
    public const NS = C::NS_CAS;

    /** @var string */
    public const NS_PREFIX = 'cas';
}
