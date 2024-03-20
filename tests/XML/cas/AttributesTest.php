<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\Utils\XPath;
use SimpleSAML\CAS\XML\cas\AbstractCasElement;
use SimpleSAML\CAS\XML\cas\Attributes;
use SimpleSAML\CAS\XML\cas\AuthenticationDate;
use SimpleSAML\CAS\XML\cas\IsFromNewLogin;
use SimpleSAML\CAS\XML\cas\LongTermAuthenticationRequestTokenUsed;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\AttributesTest
 *
 * @package simplesamlphp/cas
 */
#[CoversClass(XPath::class)]
#[CoversClass(Attributes::class)]
#[CoversClass(AbstractCasElement::class)]
final class AttributesTest extends TestCase
{
    use SerializableElementTestTrait;

    /** @var \DateTimeImmutable */
    private static DateTimeImmutable $authenticationDate;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Attributes::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 4) . '/resources/xml/cas_attributes.xml',
        );

        self::$authenticationDate = new DateTimeImmutable('2015-11-12T09:30:10Z');
    }


    /**
     */
    public function testMarshalling(): void
    {
        $authenticationDate = new AuthenticationDate(self::$authenticationDate);
        $longTerm = new LongTermAuthenticationRequestTokenUsed('true');
        $isFromNewLogin = new IsFromNewLogin('true');
        $document = DOMDocumentFactory::fromString(
            '<cas:myAttribute xmlns:cas="http://www.yale.edu/tp/cas">myValue</cas:myAttribute>',
        );
        $myAttribute = new Chunk($document->documentElement);
        $attributes = new Attributes($authenticationDate, $longTerm, $isFromNewLogin, [$myAttribute]);

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($attributes),
        );
    }


    public function testMarshallingElementOrdering(): void
    {
        $authenticationDate = new AuthenticationDate(self::$authenticationDate);
        $longTerm = new LongTermAuthenticationRequestTokenUsed('true');
        $isFromNewLogin = new IsFromNewLogin('true');
        $document = DOMDocumentFactory::fromString(
            '<cas:myAttribute xmlns:cas="http://www.yale.edu/tp/cas">myValue</cas:myAttribute>',
        );
        $myAttribute = new Chunk($document->documentElement);
        $attributes = new Attributes($authenticationDate, $longTerm, $isFromNewLogin, [$myAttribute]);

        $attributesElement = $attributes->toXML();

        // Test for an authenticationDate
        $xpCache = XPath::getXPath($attributesElement);
        $attributesElements = XPath::xpQuery($attributesElement, './cas:authenticationDate', $xpCache);
        $this->assertCount(1, $attributesElements);

        // Test ordering of cas:attributes contents
        /** @psalm-var \DOMElement[] $attributesElements */
        $attributesElements = XPath::xpQuery(
            $attributesElement,
            './cas:authenticationDate/following-sibling::*',
            $xpCache,
        );

        $this->assertGreaterThanOrEqual(2, count($attributesElements));
        $this->assertEquals('cas:longTermAuthenticationRequestTokenUsed', $attributesElements[0]->tagName);
        $this->assertEquals('cas:isFromNewLogin', $attributesElements[1]->tagName);
    }
}
