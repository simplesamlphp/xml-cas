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
    final public const string NS_CAS = 'http://www.yale.edu/tp/cas';

    /**
     * The format to express a timestamp in CAS
     */
    final public const string DATETIME_FORMAT = 'Y-m-d\\TH:i:sp';

    /**
     * The INTERNAL_ERROR CAS error
     */
    final public const string ERR_INTERNAL_ERROR = 'INTERNAL_ERROR';

    /**
     * The INVALID_REQUEST CAS error
     */
    final public const string ERR_INVALID_REQUEST = 'INVALID_REQUEST';

    /**
     * The INVALID_SERVICE CAS error
     */
    final public const string ERR_INVALID_SERVICE = 'INVALID_SERVICE';

    /**
     * The INVALID_TICKET CAS error
     */
    final public const string ERR_INVALID_TICKET = 'INVALID_TICKET';
}
