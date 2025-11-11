<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\Constants as C;
use SimpleSAML\CAS\Utils\XPath;
use SimpleSAML\CAS\XML\AbstractCasElement;
use SimpleSAML\CAS\XML\Attributes;
use SimpleSAML\CAS\XML\AuthenticationDate;
use SimpleSAML\CAS\XML\AuthenticationSuccess;
use SimpleSAML\CAS\XML\IsFromNewLogin;
use SimpleSAML\CAS\XML\LongTermAuthenticationRequestTokenUsed;
use SimpleSAML\CAS\XML\ProxyGrantingTicket;
use SimpleSAML\CAS\XML\ServiceResponse;
use SimpleSAML\CAS\XML\User;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SchemaValidationTestTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Type\BooleanValue;
use SimpleSAML\XMLSchema\Type\DateTimeValue;
use SimpleSAML\XMLSchema\Type\StringValue;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\ServiceResponseTest
 *
 * @package simplesamlphp/cas
 */
#[CoversClass(ServiceResponse::class)]
#[CoversClass(AbstractCasElement::class)]
final class ServiceResponseTest extends TestCase
{
    use SchemaValidationTestTrait;
    use SerializableElementTestTrait;


    /** @var \SimpleSAML\XMLSchema\Type\DateTimeValue */
    private static DateTimeValue $authenticationDate;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = ServiceResponse::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_serviceResponse.xml',
        );

        self::$authenticationDate = DateTimeValue::fromString('2015-11-12T09:30:10Z');
    }


    /**
     * Verifies model-to-XML marshalling fidelity for a canonical CAS response.
     *
     * Why useful:
     * - Guards that serialization produces schema-conform, stable XML (element order, names, namespaces).
     * - Ensures optional/custom attributes added as XML chunks are preserved during marshalling.
     * - Detects regressions that would change on-the-wire responses.
     */
    public function testMarshalling(): void
    {
        $authenticationDate = new AuthenticationDate(self::$authenticationDate);
        $longTerm = new LongTermAuthenticationRequestTokenUsed(BooleanValue::fromString('true'));
        $isFromNewLogin = new IsFromNewLogin(BooleanValue::fromString('true'));

        /** @var \DOMElement $element */
        $element = DOMDocumentFactory::fromString(
            '<cas:myAttribute xmlns:cas="http://www.yale.edu/tp/cas">myValue</cas:myAttribute>',
        )->documentElement;
        $myAttribute = new Chunk($element);

        $authenticationSuccess = new Authenticationsuccess(
            new User(StringValue::fromString('username')),
            new Attributes($authenticationDate, $longTerm, $isFromNewLogin, [$myAttribute]),
            new ProxyGrantingTicket(StringValue::fromString('PGTIOU-84678-8a9d...')),
        );
        $serviceResponse = new ServiceResponse($authenticationSuccess);

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($serviceResponse),
        );
    }


    /**
     * Ensures foreign namespaced attributes allowed by xs:any survive a full round-trip.
     *
     * Why useful:
     * - Confirms that unknown/vendor prefixes (e.g., slate:*) are not dropped by the model.
     * - Verifies core CAS elements remain intact alongside custom attributes after parse → serialize.
     * - Protects interoperability with CAS servers emitting additional attributes.
     */
    public function testParsesAndKeepsCustomNamespaceAttributes(): void
    {
        $doc = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_custom_serviceResponse.xml',
        );

        $root = $doc->documentElement;
        self::assertInstanceOf(\DOMElement::class, $root, 'Root element must be a DOMElement');
        $serviceResponse = ServiceResponse::fromXML($root);

        // Round-trip back to XML and inspect with DOM APIs (namespace-aware)
        $roundTripped = $serviceResponse->toXML()->ownerDocument;
        $this->assertNotNull($roundTripped, 'Round-tripped document must not be null');

        // Verify user
        $users = $roundTripped->getElementsByTagNameNS('http://www.yale.edu/tp/cas', 'user');
        $this->assertSame(1, $users->count(), 'Exactly one cas:user expected');
        $this->assertSame('jdoe', $users->item(0)?->textContent);

        // Verify custom namespaced attributes under slate namespace
        $slateNs = 'https://example.org/slate';
        $person = $roundTripped->getElementsByTagNameNS($slateNs, 'person');
        $round = $roundTripped->getElementsByTagNameNS($slateNs, 'round');
        $ref = $roundTripped->getElementsByTagNameNS($slateNs, 'ref');

        $this->assertSame(1, $person->count(), 'Expected one slate:person element');
        $this->assertSame('12345', $person->item(0)?->textContent);

        $this->assertSame(1, $round->count(), 'Expected one slate:round element');
        $this->assertSame('Fall-2025', $round->item(0)?->textContent);

        $this->assertSame(1, $ref->count(), 'Expected one slate:ref element');
        $this->assertSame('ABC-123', $ref->item(0)?->textContent);
    }


    /**
     * Validates consumer-style XPath-based attribute extraction across namespaces.
     *
     * Why useful:
     * - Mirrors production logic that rewrites absolute CAS XPaths to be relative to cas:authenticationSuccess.
     * - Confirms that CAS and custom prefixes are registered in the XPath context and queries succeed.
     * - Ensures configuration-driven mappings can reliably fetch both standard and vendor-specific values.
     */
    public function testXPathAttributeExtractionWithCustomNamespaces(): void
    {
        // Load a response that contains extra namespaced attributes (slate:*)
        $doc = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_custom_serviceResponse.xml',
        );

        // Find the authenticationSuccess element in the ORIGINAL document,
        // so we keep all namespace declarations (including xmlns:slate).
        $authn = $doc->getElementsByTagNameNS(C::NS_CAS, 'authenticationSuccess')->item(0);
        $this->assertNotNull($authn, 'authenticationSuccess element not found in document');
        $element = $authn; // context node for XPath queries with full ns bindings

        $xp = XPath::getXPath($element);

        // This mimics the consumer's $casConfig['attributes'] mapping
        $casattributes = [
            'user' => 'cas:user',
            'firstname' => 'cas:attributes/cas:firstname',
            // Include one absolute form to ensure rewrite-to-relative logic works against authenticationSuccess
            'slate_round' => '/cas:serviceResponse/cas:authenticationSuccess/cas:attributes/slate:round',
            'slate_person' => 'cas:attributes/slate:person',
            'slate_ref' => 'cas:attributes/slate:ref',
        ];

        $attributes = [];
        foreach ($casattributes as $name => $query) {
            $marker = 'cas:authenticationSuccess/';
            if (str_starts_with($query, '/')) {
                $pos = strpos($query, $marker);
                if ($pos !== false) {
                    $query = substr($query, $pos + strlen($marker));
                }
            }

            $nodes = XPath::xpQuery($element, $query, $xp);
            foreach ($nodes as $n) {
                $attributes[$name][] = $n->textContent;
            }
        }

        // Assert both CAS and custom namespaced attributes are extracted
        $this->assertSame(['jdoe'], $attributes['user'] ?? []);
        $this->assertSame(['John'], $attributes['firstname'] ?? []);
        $this->assertSame(['12345'], $attributes['slate_person'] ?? []);
        $this->assertSame(['Fall-2025'], $attributes['slate_round'] ?? []);
        $this->assertSame(['ABC-123'], $attributes['slate_ref'] ?? []);
    }
}
