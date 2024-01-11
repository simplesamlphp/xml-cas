<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\Error as ERR;
use SimpleSAML\CAS\XML\cas\ProxyFailure;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\ProxyFailureTest
 *
 * @covers \SimpleSAML\CAS\XML\cas\ProxyFailure
 * @covers \SimpleSAML\CAS\XML\cas\AbstractResponse
 * @covers \SimpleSAML\CAS\XML\cas\AbstractCasElement
 *
 * @package simplesamlphp/cas
 */
final class ProxyFailureTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = ProxyFailure::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 4) . '/resources/xml/cas_proxyFailure.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $proxyFailure = new ProxyFailure('some text', ERR::INVALID_REQUEST);

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($proxyFailure),
        );
    }
}
