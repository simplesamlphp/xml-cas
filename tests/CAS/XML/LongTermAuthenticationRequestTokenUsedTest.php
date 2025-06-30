<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\AbstractCasElement;
use SimpleSAML\CAS\XML\LongTermAuthenticationRequestTokenUsed;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Type\BooleanValue;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\LongTermAuthenticationRequestTokenUsedTest
 *
 * @package simplesamlphp/cas
 */
#[CoversClass(LongTermAuthenticationRequestTokenUsed::class)]
#[CoversClass(AbstractCasElement::class)]
final class LongTermAuthenticationRequestTokenUsedTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = LongTermAuthenticationRequestTokenUsed::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_longTermAuthenticationRequestTokenUsed.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $longTerm = new LongTermAuthenticationRequestTokenUsed(BooleanValue::fromString('true'));

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($longTerm),
        );
    }
}
