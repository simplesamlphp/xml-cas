<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\cas;

use SimpleSAML\XML\XMLElementInterface;

/**
 * Abstract class to be implemented by all the responses in this namespace
 *
 * @package simplesamlphp/cas
 */
abstract class AbstractResponse extends AbstractCasElement implements
    XMLElementInterface
{
}
