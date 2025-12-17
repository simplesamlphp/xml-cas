<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use SimpleSAML\CAS\Constants as C;
use SimpleSAML\XML\AbstractElement;

/**
 * Abstract class to be implemented by all the classes in this namespace
 *
 * @package simplesamlphp/xml-cas
 */
abstract class AbstractCasElement extends AbstractElement
{
    public const string NS = C::NS_CAS;

    public const string NS_PREFIX = 'cas';

    public const string SCHEMA = 'resources/schemas/cas-server-protocol-3.0.xsd';
}
