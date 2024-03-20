<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\Error as ERR;
use SimpleSAML\CAS\XML\cas\AbstractCasElement;
use SimpleSAML\CAS\XML\cas\AbstractResponse;
use SimpleSAML\CAS\XML\cas\AuthenticationFailure;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\AuthenticationFailureTest
 *
 * @package simplesamlphp/cas
 */
#[CoversClass(AuthenticationFailure::class)]
#[CoversClass(AbstractResponse::class)]
#[CoversClass(AbstractCasElement::class)]
final class AuthenticationFailureTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = AuthenticationFailure::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 4) . '/resources/xml/cas_authenticationFailure.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $authenticationFailure = new AuthenticationFailure(
            'Ticket ST-1856339-aA5Yuvrxzpv8Tau1cYQ7 not recognized',
            ERR::INVALID_TICKET,
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($authenticationFailure),
        );
    }
}
