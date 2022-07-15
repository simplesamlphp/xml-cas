<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\cas\ProxyGrantingTicket;
use SimpleSAML\Test\XML\SerializableXMLTestTrait;
use SimpleSAML\XML\DOMDocumentFactory;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\ProxyGrantingTicketTest
 *
 * @covers \SimpleSAML\CAS\XML\cas\ProxyGrantingTicket
 * @covers \SimpleSAML\CAS\XML\cas\AbstractCasElement
 *
 * @package simplesamlphp/cas
 */
final class ProxyGrantingTicketTest extends TestCase
{
    use SerializableXMLTestTrait;

    /**
     */
    protected function setUp(): void
    {
        $this->testedClass = ProxyGrantingTicket::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(dirname(__FILE__))) . '/resources/xml/cas_proxyGrantingTicket.xml'
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $proxyGrantingTicket = new ProxyGrantingTicket('PGTIOU-84678-8a9d...');

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($proxyGrantingTicket)
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $proxyGrantingTicket = ProxyGrantingTicket::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals('PGTIOU-84678-8a9d...', $proxyGrantingTicket->getContent());
    }
}
