<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\Error as ERR;
use SimpleSAML\CAS\XML\cas\AuthenticationFailure;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\AuthenticationFailureTest
 *
 * @covers \SimpleSAML\CAS\XML\cas\AuthenticationFailure
 * @covers \SimpleSAML\CAS\XML\cas\AbstractResponse
 * @covers \SimpleSAML\CAS\XML\cas\AbstractCasElement
 *
 * @package simplesamlphp/cas
 */
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


    /**
     */
    public function testUnmarshalling(): void
    {
        $authenticationFailure = AuthenticationFailure::fromXML(self::$xmlRepresentation->documentElement);

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($authenticationFailure),
        );
    }
}
