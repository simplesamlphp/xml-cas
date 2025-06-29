<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\cas\AbstractCasElement;
use SimpleSAML\CAS\XML\cas\LongTermAuthenticationRequestTokenUsed;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Type\Builtin\BooleanValue;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\LongTermAuthenticationRequestTokenUsedTest
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
            dirname(__FILE__, 4) . '/resources/xml/cas_longTermAuthenticationRequestTokenUsed.xml',
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
