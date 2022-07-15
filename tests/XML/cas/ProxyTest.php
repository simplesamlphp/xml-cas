<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\cas\Proxy;
use SimpleSAML\Test\XML\SerializableXMLTestTrait;
use SimpleSAML\XML\DOMDocumentFactory;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\ProxyTest
 *
 * @covers \SimpleSAML\CAS\XML\cas\Proxy
 * @covers \SimpleSAML\CAS\XML\cas\AbstractCasElement
 *
 * @package simplesamlphp/cas
 */
final class ProxyTest extends TestCase
{
    use SerializableXMLTestTrait;

    /**
     */
    protected function setUp(): void
    {
        $this->testedClass = Proxy::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(dirname(__FILE__))) . '/resources/xml/cas_proxy.xml'
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $proxy = new Proxy('https://example.org/proxy');

        $proxyElement = $proxy->toXML();
        $this->assertEquals('https://example.org/proxy', $proxyElement->textContent);

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($proxy)
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $proxy = Proxy::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals('https://example.org/proxy', $proxy->getContent());
    }
}
