<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\AbstractCasElement;
use SimpleSAML\CAS\XML\Proxies;
use SimpleSAML\CAS\XML\Proxy;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Type\StringValue;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\ProxiesTest
 *
 * @package simplesamlphp/cas
 */
#[CoversClass(Proxies::class)]
#[CoversClass(AbstractCasElement::class)]
final class ProxiesTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Proxies::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_proxies.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $entry1 = new Proxy(StringValue::fromString('https://example.org/proxy/1'));
        $entry2 = new Proxy(StringValue::fromString('https://example.org/proxy/2'));
        $list = new Proxies([$entry1, $entry2]);

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($list),
        );
    }
}
