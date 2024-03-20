<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\cas\AbstractCasElement;
use SimpleSAML\CAS\XML\cas\ProxyGrantingTicket;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\ProxyGrantingTicketTest
 *
 * @package simplesamlphp/cas
 */
#[CoversClass(ProxyGrantingTicket::class)]
#[CoversClass(AbstractCasElement::class)]
final class ProxyGrantingTicketTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = ProxyGrantingTicket::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 4) . '/resources/xml/cas_proxyGrantingTicket.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $proxyGrantingTicket = new ProxyGrantingTicket('PGTIOU-84678-8a9d...');

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($proxyGrantingTicket),
        );
    }
}
