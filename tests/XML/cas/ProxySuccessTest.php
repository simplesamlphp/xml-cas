<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\Constants as C;
use SimpleSAML\CAS\XML\cas\ProxySuccess;
use SimpleSAML\CAS\XML\cas\ProxyTicket;
use SimpleSAML\Test\XML\SerializableXMLTestTrait;
use SimpleSAML\XML\DOMDocumentFactory;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\ProxySuccessTest
 *
 * @covers \SimpleSAML\CAS\XML\cas\ProxySuccess
 * @covers \SimpleSAML\CAS\XML\cas\AbstractCasElement
 *
 * @package simplesamlphp/cas
 */
final class ProxySuccessTest extends TestCase
{
    use SerializableXMLTestTrait;

    /**
     */
    protected function setUp(): void
    {
        $this->testedClass = ProxySuccess::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(dirname(__FILE__))) . '/resources/xml/cas_proxySuccess.xml'
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $proxySuccess = new ProxySuccess(new ProxyTicket('PT-1856392-b98xZrQN4p90ASrw96c8'));

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($proxySuccess)
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $proxySuccess = ProxySuccess::fromXML($this->xmlRepresentation->documentElement);

        $ticket = $proxySuccess->getProxyTicket();
        $this->assertEquals('PT-1856392-b98xZrQN4p90ASrw96c8', $ticket->getContent());
    }
}
