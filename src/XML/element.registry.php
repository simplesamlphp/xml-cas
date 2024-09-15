<?php

declare(strict_types=1);

return [
    'http://www.yale.edu/tp/cas' => [
        'attributes' => '\SimpleSAML\CAS\XML\cas\Attributes',
        'authenticationDate' => '\SimpleSAML\CAS\XML\cas\AuthenticationDate',
        'authenticationFailure' => '\SimpleSAML\CAS\XML\cas\AuthenticationFailure',
        'authenticationSuccess' => '\SimpleSAML\CAS\XML\cas\AuthenticationSuccess',
        'isFromNewLogin' => '\SimpleSAML\CAS\XML\cas\IsFromNewLogin',
        'longTermAuthenticationRequestTokenUsed' => '\SimpleSAML\CAS\XML\cas\LongTermAuthenticationRequestTokenUsed',
        'proxies' => '\SimpleSAML\CAS\XML\cas\Proxies',
        'proxy' => '\SimpleSAML\CAS\XML\cas\Proxy',
        'proxyFailure' => '\SimpleSAML\CAS\XML\cas\ProxyFailure',
        'proxyGrantingTicket' => '\SimpleSAML\CAS\XML\cas\ProxyGrantingTicket',
        'proxySuccess' => '\SimpleSAML\CAS\XML\cas\ProxySuccess',
        'proxyTicket' => '\SimpleSAML\CAS\XML\cas\ProxyTicket',
        'serviceResponse' => '\SimpleSAML\CAS\XML\cas\ServiceResponse',
        'user' => '\SimpleSAML\CAS\XML\cas\User',
    ],
];
