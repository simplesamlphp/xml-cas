<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\cas\ProxySuccess;
use SimpleSAML\CAS\XML\cas\ProxyTicket;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\ProxySuccessTest
 *
 * @covers \SimpleSAML\CAS\XML\cas\ProxySuccess
 * @covers \SimpleSAML\CAS\XML\cas\AbstractResponse
 * @covers \SimpleSAML\CAS\XML\cas\AbstractCasElement
 *
 * @package simplesamlphp/cas
 */
final class ProxySuccessTest extends TestCase
{
    use SerializableElementTestTrait;

    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = ProxySuccess::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 4) . '/resources/xml/cas_proxySuccess.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $proxySuccess = new ProxySuccess(new ProxyTicket('PT-1856392-b98xZrQN4p90ASrw96c8'));

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($proxySuccess),
        );
    }
}
