<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\XML\Enumeration;

/**
 * The error codes defined by the CAS protocol specification
 */
enum ErrorEnum: string
{
    case INVALID_REQUEST = 'INVALID_REQUEST';
    case INVALID_TICKET_SPEC = 'INVALID_TICKET_SPEC';
    case UNAUTHORIZED_SERVICE_PROXY = 'UNAUTHORIZED_SERVICE_PROXY';
    case INVALID_PROXY_CALLBACK = 'INVALID_PROXY_CALLBACK';
    case INVALID_TICKET = 'INVALID_TICKET';
    case INVALID_SERVICE = 'INVALID_SERVICE';
    case INTERNAL_ERROR = 'INTERNAL_ERROR';
}
