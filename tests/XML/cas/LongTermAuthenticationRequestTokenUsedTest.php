<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\cas\LongTermAuthenticationRequestTokenUsed;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\LongTermAuthenticationRequestTokenUsedTest
 *
 * @covers \SimpleSAML\CAS\XML\cas\LongTermAuthenticationRequestTokenUsed
 * @covers \SimpleSAML\CAS\XML\cas\AbstractCasElement
 *
 * @package simplesamlphp/cas
 */
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
        $longTerm = new LongTermAuthenticationRequestTokenUsed('true');

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($longTerm),
        );
    }
}
