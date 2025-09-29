<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\AbstractCasElement;
use SimpleSAML\CAS\XML\Proxy;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Type\StringValue;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\ProxyTest
 *
 * @package simplesamlphp/cas
 */
#[CoversClass(Proxy::class)]
#[CoversClass(AbstractCasElement::class)]
final class ProxyTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Proxy::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_proxy.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $proxy = new Proxy(StringValue::fromString('https://example.org/proxy'));

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($proxy),
        );
    }
}
