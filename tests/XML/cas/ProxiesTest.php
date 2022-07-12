<?php

declare(strict_types=1);

namespace SimpleSAML\Test\CAS\XML\cas;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\cas\Proxies;
use SimpleSAML\Test\XML\SerializableXMLTestTrait;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\Exception\MissingElementException;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\ProxiesTest
 *
 * @covers \SimpleSAML\CAS\XML\cas\Proxies
 * @covers \SimpleSAML\CAS\XML\cas\AbstractCasElement
 *
 * @package simplesamlphp/cas
 */
final class ProxiesTest extends TestCase
{
    use SerializableXMLTestTrait;


    /**
     */
    protected function setUp(): void
    {
        $this->testedClass = Proxies::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(dirname(__FILE__))) . '/resources/xml/cas_proxies.xml'
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $entry1 = new Proxy('https://example.org/proxy/1');
        $entry2 = new Proxy('https://example.org/proxy/2');
        $list = new Proxies([$entry1, $entry2]);

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($list)
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $list = Proxy::fromXML($this->xmlRepresentation->documentElement);

        $entries = $list->getProxies();
        $this->assertCount(2, $entries);

        $this->assertEquals('https://example.org/proxy/1', $entries[0]);
        $this->assertEquals('https://example.org/proxy/2', $entries[1]);
    }
}
