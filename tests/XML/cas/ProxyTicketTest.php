<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\cas\ProxyTicket;
use SimpleSAML\Test\XML\SerializableXMLTestTrait;
use SimpleSAML\XML\DOMDocumentFactory;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\ProxyTicketTest
 *
 * @covers \SimpleSAML\CAS\XML\cas\ProxyTicket
 * @covers \SimpleSAML\CAS\XML\cas\AbstractCasElement
 *
 * @package simplesamlphp/cas
 */
final class ProxyTicketTest extends TestCase
{
    use SerializableXMLTestTrait;

    /**
     */
    protected function setUp(): void
    {
        $this->testedClass = ProxyTicket::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(dirname(__FILE__))) . '/resources/xml/cas_proxyTicket.xml'
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $proxyTicket = new ProxyTicket('PT-1856392-b98xZrQN4p90ASrw96c8');

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($proxyTicket)
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $proxyTicket = ProxyTicket::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals('PT-1856392-b98xZrQN4p90ASrw96c8', $proxyTicket->getContent());
    }
}
