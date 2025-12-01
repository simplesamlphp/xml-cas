<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\Utils\XPath;
use SimpleSAML\CAS\XML\AbstractCasElement;
use SimpleSAML\CAS\XML\Attributes;
use SimpleSAML\CAS\XML\AuthenticationDate;
use SimpleSAML\CAS\XML\IsFromNewLogin;
use SimpleSAML\CAS\XML\LongTermAuthenticationRequestTokenUsed;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Exception\MissingElementException;
use SimpleSAML\XMLSchema\Type\BooleanValue;
use SimpleSAML\XMLSchema\Type\DateTimeValue;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\AttributesTest
 *
 * @package simplesamlphp/cas
 */
#[CoversClass(XPath::class)]
#[CoversClass(Attributes::class)]
#[CoversClass(AbstractCasElement::class)]
final class AttributesTest extends TestCase
{
    use SerializableElementTestTrait;


    /** @var \SimpleSAML\XMLSchema\Type\DateTimeValue */
    private static DateTimeValue $authenticationDate;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = Attributes::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_attributes.xml',
        );

        self::$authenticationDate = DateTimeValue::fromString('2015-11-12T09:30:10Z');
    }


    /**
     */
    public function testMarshalling(): void
    {
        $authenticationDate = new AuthenticationDate(self::$authenticationDate);
        $longTerm = new LongTermAuthenticationRequestTokenUsed(BooleanValue::fromString('true'));
        $isFromNewLogin = new IsFromNewLogin(BooleanValue::fromString('true'));
        $document = DOMDocumentFactory::fromString(
            '<cas:myAttribute xmlns:cas="http://www.yale.edu/tp/cas">myValue</cas:myAttribute>',
        );

        /** @var \DOMElement $elt */
        $elt = $document->documentElement;
        $myAttribute = new Chunk($elt);
        $attributes = new Attributes($authenticationDate, $longTerm, $isFromNewLogin, [$myAttribute]);

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($attributes),
        );
    }


    public function testMarshallingElementOrdering(): void
    {
        $authenticationDate = new AuthenticationDate(self::$authenticationDate);
        $longTerm = new LongTermAuthenticationRequestTokenUsed(BooleanValue::fromString('true'));
        $isFromNewLogin = new IsFromNewLogin(BooleanValue::fromString('true'));
        $document = DOMDocumentFactory::fromString(
            '<cas:myAttribute xmlns:cas="http://www.yale.edu/tp/cas">myValue</cas:myAttribute>',
        );

        /** @var \DOMElement $elt */
        $elt = $document->documentElement;
        $myAttribute = new Chunk($elt);
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


    /**
     * @return array<string, array{0:string,1:bool,2:bool}>
     */
    public static function slateResponseStrictnessProvider(): array
    {
        return [
            'lenient parsing (strict=false) succeeds for Slate response' => [
                'lenient',
                false,
                true,
            ],
            'strict parsing (strict=true) fails for Slate response' => [
                'strict',
                true,
                false,
            ],
        ];
    }


    /**
     * Ensure that parsing of the Slate CAS <cas:attributes> element via Attributes::fromXML
     * succeeds when strict parsing is disabled and fails when strict parsing is enabled.
     *
     * In the Slate example, <cas:attributes> only contains firstname/lastname/email and
     * is missing the CAS‑required:
     *   - cas:authenticationDate
     *   - cas:longTermAuthenticationRequestTokenUsed
     *   - cas:isFromNewLogin
     *
     * With strict=false => these are optional and parsing must succeed.
     * With strict=true  => they are required and parsing must fail.
     *
     * @param string $description   Human-readable variant description
     * @param bool   $strictParsing Whether to enable strict parsing
     * @param bool   $shouldSucceed Whether parsing is expected to succeed
     */
    #[DataProvider('slateResponseStrictnessProvider')]
    public function testSlateAttributesParsingStrictness(
        string $description,
        bool $strictParsing,
        bool $shouldSucceed,
    ): void {
        $doc = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas-slate-success-response.xml',
        );
        $root = $doc->documentElement;
        $this->assertInstanceOf(\DOMElement::class, $root, 'Loaded Slate XML does not have a document element');

        /** @var \DOMElement|null $attributesEl */
        $attributesEl = $doc->getElementsByTagNameNS('http://www.yale.edu/tp/cas', 'attributes')->item(0);
        $this->assertInstanceOf(\DOMElement::class, $attributesEl, 'Slate XML must contain a cas:attributes element');

        if (!$shouldSucceed) {
            $this->expectException(MissingElementException::class);
            $this->expectExceptionMessageMatches(
                '/authenticationDate|longTermAuthenticationRequestTokenUsed|isFromNewLogin/i',
            );
        }

        $attributes = Attributes::fromXML($attributesEl, $strictParsing);

        if ($shouldSucceed) {
            // In lenient mode, we accept missing CAS attributes; they should be null.
            $this->assertNull(
                $attributes->getAuthenticationDate(),
                sprintf('Expected null authenticationDate for "%s" case', $description),
            );
            $this->assertNull(
                $attributes->getLongTermAuthenticationRequestTokenUsed(),
                sprintf('Expected null longTermAuthenticationRequestTokenUsed for "%s" case', $description),
            );
            $this->assertNull(
                $attributes->getIsFromNewLogin(),
                sprintf('Expected null isFromNewLogin for "%s" case', $description),
            );
        }
    }
}
