<?php

declare(strict_types=1);

return [
    'http://www.yale.edu/tp/cas' => [
        'Attributes' => '\SimpleSAML\CAS\XML\cas\Attributes',
        'AuthenticationDate' => '\SimpleSAML\CAS\XML\cas\AuthenticationDate',
        'AuthenticationFailure' => '\SimpleSAML\CAS\XML\cas\AuthenticationFailure',
        'AuthenticationSuccess' => '\SimpleSAML\CAS\XML\cas\AuthenticationSuccess',
        'IsFromNewLogin' => '\SimpleSAML\CAS\XML\cas\IsFromNewLogin',
        'LongTermAuthenticationRequestTokenUsed' => '\SimpleSAML\CAS\XML\cas\LongTermAuthenticationRequestTokenUsed',
        'Proxies' => '\SimpleSAML\CAS\XML\cas\Proxies',
        'Proxy' => '\SimpleSAML\CAS\XML\cas\Proxy',
        'ProxyFailure' => '\SimpleSAML\CAS\XML\cas\ProxyFailure',
        'ProxyGrantingTicket' => '\SimpleSAML\CAS\XML\cas\ProxyGrantingTicket',
        'ProxySuccess' => '\SimpleSAML\CAS\XML\cas\ProxySuccess',
        'ProxyTicket' => '\SimpleSAML\CAS\XML\cas\ProxyTicket',
        'ServiceResponse' => '\SimpleSAML\CAS\XML\cas\ServiceResponse',
        'User' => '\SimpleSAML\CAS\XML\cas\User',
    ],
];
