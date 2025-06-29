<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\Type\CodeValue;
use SimpleSAML\CAS\XML\cas\AbstractCasElement;
use SimpleSAML\CAS\XML\cas\AbstractResponse;
use SimpleSAML\CAS\XML\cas\ErrorEnum;
use SimpleSAML\CAS\XML\cas\ProxyFailure;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Type\Builtin\StringValue;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\ProxyFailureTest
 *
 * @package simplesamlphp/cas
 */
#[CoversClass(ProxyFailure::class)]
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
            dirname(__FILE__, 4) . '/resources/xml/cas_proxyFailure.xml',
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
