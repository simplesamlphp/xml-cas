<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\AbstractCasElement;
use SimpleSAML\CAS\XML\ProxyTicket;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Type\StringValue;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\ProxyTicketTest
 *
 * @package simplesamlphp/xml-cas
 */
#[CoversClass(ProxyTicket::class)]
#[CoversClass(AbstractCasElement::class)]
final class ProxyTicketTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = ProxyTicket::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_proxyTicket.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $proxyTicket = new ProxyTicket(
            StringValue::fromString('PT-1856392-b98xZrQN4p90ASrw96c8'),
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($proxyTicket),
        );
    }
}
