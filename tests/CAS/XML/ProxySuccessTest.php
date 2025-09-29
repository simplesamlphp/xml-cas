<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\AbstractCasElement;
use SimpleSAML\CAS\XML\AbstractResponse;
use SimpleSAML\CAS\XML\ProxySuccess;
use SimpleSAML\CAS\XML\ProxyTicket;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Type\StringValue;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\ProxySuccessTest
 *
 * @package simplesamlphp/cas
 */
#[CoversClass(ProxySuccess::class)]
#[CoversClass(AbstractResponse::class)]
#[CoversClass(AbstractCasElement::class)]
final class ProxySuccessTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = ProxySuccess::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_proxySuccess.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $proxySuccess = new ProxySuccess(
            new ProxyTicket(StringValue::fromString('PT-1856392-b98xZrQN4p90ASrw96c8')),
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($proxySuccess),
        );
    }
}
