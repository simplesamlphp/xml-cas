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
    public const NS_CAS = 'http://www.yale.edu/tp/cas';

    /**
     * The error codes defined by the CAS protocol specification
     */
    public const ERR_INVALID_REQUEST = 'INVALID_REQUEST';
    public const ERR_INVALID_TICKET_SPEC = 'INVALID_TICKET_SPEC';
    public const ERR_UNAUTHORIZED_SERVICE_PROXY = 'UNAUTHORIZED_SERVICE_PROXY';
    public const ERR_INVALID_PROXY_CALLBACK = 'INVALID_PROXY_CALLBACK';
    public const ERR_INVALID_TICKET = 'INVALID_TICKET';
    public const ERR_INVALID_SERVICE = 'INVALID_SERVICE';
    public const ERR_INTERNAL_ERROR = 'INTERNAL_ERROR';
}
