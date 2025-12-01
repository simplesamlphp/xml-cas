<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML;

use SimpleSAML\XML\ElementInterface;
use SimpleSAML\XML\SerializableElementInterface;

/**
 * Abstract class to be implemented by all the responses in this namespace
 *
 * @package simplesamlphp/xml-cas
 */
abstract class AbstractResponse extends AbstractCasElement implements
    ElementInterface,
    SerializableElementInterface
{
}
