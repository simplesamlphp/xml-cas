<?php

declare(strict_types=1);

namespace SimpleSAML\CAS;

/**
 * Various CAS constants.
 *
 * @package simplesamlphp/cas
 */
class Constants extends \SimpleSAML\XML\Constants
{
    /**
     * The namespace for the CAS protocol.
     */
    final public const NS_CAS = 'http://www.yale.edu/tp/cas';

    /**
     * The format to express a timestamp in CAS
     */
    final public const DATETIME_FORMAT = 'Y-m-d\\TH:i:sp';
}
