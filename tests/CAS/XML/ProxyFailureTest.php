<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\Type\CodeValue;
use SimpleSAML\CAS\XML\AbstractCasElement;
use SimpleSAML\CAS\XML\AbstractProxyFailure;
use SimpleSAML\CAS\XML\AbstractResponse;
use SimpleSAML\CAS\XML\Enumeration\ErrorEnum;
use SimpleSAML\CAS\XML\ProxyFailure;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Type\StringValue;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\ProxyFailureTest
 *
 * @package simplesamlphp/xml-cas
 */
#[CoversClass(ProxyFailure::class)]
#[CoversClass(AbstractProxyFailure::class)]
#[CoversClass(AbstractResponse::class)]
#[CoversClass(AbstractCasElement::class)]
final class ProxyFailureTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = ProxyFailure::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_proxyFailure.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $proxyFailure = new ProxyFailure(
            StringValue::fromString('some text'),
            CodeValue::fromEnum(ErrorEnum::INVALID_REQUEST),
        );

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($proxyFailure),
        );
    }
}
