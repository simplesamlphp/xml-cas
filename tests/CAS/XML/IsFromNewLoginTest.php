<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\AbstractCasElement;
use SimpleSAML\CAS\XML\IsFromNewLogin;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Type\BooleanValue;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\IsFromNewLoginTest
 *
 * @package simplesamlphp/cas
 */
#[CoversClass(IsFromNewLogin::class)]
#[CoversClass(AbstractCasElement::class)]
final class IsFromNewLoginTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = IsFromNewLogin::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_isFromNewLogin.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $isFromNewLogin = new IsFromNewLogin(BooleanValue::fromString('true'));

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($isFromNewLogin),
        );
    }
}
