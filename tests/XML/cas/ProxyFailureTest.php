<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\Constants as C;
use SimpleSAML\CAS\XML\cas\ProxyFailure;
use SimpleSAML\Test\XML\SerializableElementTestTrait;
use SimpleSAML\XML\DOMDocumentFactory;

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
    protected function setUp(): void
    {
        $this->testedClass = ProxyFailure::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(dirname(__FILE__))) . '/resources/xml/cas_proxyFailure.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $proxyFailure = new ProxyFailure('some text', C::ERR_INVALID_REQUEST);

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($proxyFailure),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $proxyFailure = ProxyFailure::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals('some text', $proxyFailure->getContent());
        $this->assertEquals(C::ERR_INVALID_REQUEST, $proxyFailure->getCode());
    }
}
