<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML;

use DOMElement;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
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
use function str_starts_with;
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
        $this->assertCount(1, $users, 'Exactly one cas:user expected');
        $this->assertSame('jdoe', $users->item(0)?->textContent);

        // Verify custom namespaced attributes under slate namespace
        $slateNs = 'https://example.org/slate';
        $person = $roundTripped->getElementsByTagNameNS($slateNs, 'person');
        $round = $roundTripped->getElementsByTagNameNS($slateNs, 'round');
        $ref = $roundTripped->getElementsByTagNameNS($slateNs, 'ref');

        $this->assertCount(1, $person, 'Expected one slate:person element');
        $this->assertSame('12345', $person->item(0)?->textContent);

        $this->assertCount(1, $round, 'Expected one slate:round element');
        $this->assertSame('Fall-2025', $round->item(0)?->textContent);

        $this->assertCount(1, $ref, 'Expected one slate:ref element');
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


    /**
     * @return array<string, array{0: string, 1: string, 2: array<string>}>
     */
    public static function providerSuccessResponseCQueries(): array
    {
        return [
            'user' => [
                '/cas:serviceResponse/cas:authenticationSuccess/cas:user',
                'cas:user',
                ['jdoe'],
            ],
            'top-level slate:person' => [
                '/cas:serviceResponse/cas:authenticationSuccess/slate:person',
                'slate:person',
                ['12345_top'],
            ],
            'sn' => [
                '/cas:serviceResponse/cas:authenticationSuccess/cas:attributes/cas:sn',
                'cas:attributes/cas:sn',
                ['Doe'],
            ],
            'firstname' => [
                '/cas:serviceResponse/cas:authenticationSuccess/cas:attributes/cas:firstname',
                'cas:attributes/cas:firstname',
                ['John'],
            ],
            'mail' => [
                '/cas:serviceResponse/cas:authenticationSuccess/cas:attributes/cas:mail',
                'cas:attributes/cas:mail',
                ['jdoe@example.edu'],
            ],
            'eppn' => [
                '/cas:serviceResponse/cas:authenticationSuccess/cas:attributes/cas:eduPersonPrincipalName',
                'cas:attributes/cas:eduPersonPrincipalName',
                ['jdoe@example.edu'],
            ],
            'slate person' => [
                '/cas:serviceResponse/cas:authenticationSuccess/cas:attributes/slate:person',
                'cas:attributes/slate:person',
                ['12345'],
            ],
            'slate round' => [
                '/cas:serviceResponse/cas:authenticationSuccess/cas:attributes/slate:round',
                'cas:attributes/slate:round',
                ['Fall-2025'],
            ],
            'slate ref' => [
                '/cas:serviceResponse/cas:authenticationSuccess/cas:attributes/slate:ref',
                'cas:attributes/slate:ref',
                ['ABC-123'],
            ],
        ];
    }


    /**
     * Tests that parsed ServiceResponse data matches values from both absolute and relative XPath queries.
     *
     * This test verifies:
     * - Equivalence between absolute and relative XPath queries against the XML
     * - Correct extraction of user identifier from cas:user element
     * - Matching of parsed attributes against XPath query results
     * - Preservation of auxiliary top-level elements (e.g. slate:person)
     *
     * @param string $absolute The absolute XPath query starting from root
     * @param string $relative The relative XPath query from authenticationSuccess context
     * @param array<string> $expected The expected values to match from both queries
     */
    #[DataProvider('providerSuccessResponseCQueries')]
    public function testServiceResponseParsedDataMatchesXPathQueriesFromXml(
        string $absolute,
        string $relative,
        array $expected,
    ): void {
        // Load XML
        $doc = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/success_response_c.xml',
        );

        $root = $doc->documentElement;
        self::assertInstanceOf(DOMElement::class, $root, 'Root element must be a DOMElement');

        // Parse to ServiceResponse model
        $serviceResponse = ServiceResponse::fromXML($root);
        $message = $serviceResponse->getResponse();
        self::assertInstanceOf(
            AuthenticationSuccess::class,
            $message,
            'Expected AuthenticationSuccess',
        );

        // Message element DOM for XPath comparisons
        $element = $message->toXML();
        $xpMsg = XPath::getXPath($element, true);

        // 1) Compare user value
        $userNodes = XPath::xpQuery($element, 'cas:user', $xpMsg);
        $this->assertCount(1, $userNodes, 'Expected one cas:user');
        $this->assertSame($userNodes[0]->textContent, strval($message->getUser()->getContent()));

        // 2) Compare attributes built from XPath vs. parsed model (Chunks)
        $xpValues = array_map(static fn($n) => $n->textContent, XPath::xpQuery($element, $relative, $xpMsg));
        /** @var array<string> $expected */
        $this->assertSame($expected, $xpValues, 'Unexpected XPath results for provided query');

        // Build parsed attributes map from model, following the same keying rules used by consumer code:
        // - prefix cas or empty => key is localName
        // - other prefixes => "prefix:localName"
        /** @var list<\SimpleSAML\XML\Chunk> $chunks */
        $chunks = $message->getAttributes()->getElements();
        $parsedAttributes = [];
        foreach ($chunks as $chunk) {
            $local = $chunk->getLocalName();
            $prefix = $chunk->getPrefix();
            $xml = $chunk->getXML();

            $key = ($prefix === '' || $prefix === 'cas')
                ? $local
                : ($prefix . ':' . $local);

            $parsedAttributes[$key] ??= [];
            $parsedAttributes[$key][] = trim($xml->textContent ?? '');
        }

        // If this case is an attribute path (not the top-level slate:person or user),
        // ensure parsed attributes include the same values.
        if (str_starts_with($relative, 'cas:attributes/')) {
            $tail = substr($relative, strlen('cas:attributes/')); // e.g. cas:sn or slate:ref
            $parts = explode(':', $tail, 2);
            if (count($parts) === 2) {
                [$pfx, $lname] = $parts;
                $attrKey = ($pfx === 'cas' || $pfx === '')
                    ? $lname
                    : ($pfx . ':' . $lname);
            } else {
                $attrKey = $tail;
            }

            $this->assertArrayHasKey($attrKey, $parsedAttributes, "Parsed attributes missing key '$attrKey'");
            $this->assertSame(
                $expected,
                $parsedAttributes[$attrKey],
                "Parsed attributes for key '$attrKey' mismatch",
            );
        }

        // 3) Compare auxiliary top-level children under authenticationSuccess
        // For success_response_c.xml we expect a top-level slate:person next to cas:user and cas:attributes
        $aux = $message->getAuthenticationSuccessMetadata();
        $metadataSlatePerson = null;
        foreach ($aux as $child) {
            if ($child->namespaceURI === 'https://example.org/slate' && $child->localName === 'person') {
                $metadataSlatePerson = $child->textContent;
                break;
            }
        }
        $this->assertSame(
            '12345_top',
            $metadataSlatePerson,
            'Auxiliary top-level slate:person mismatch',
        );

        // Finally assert that absolute and relative XPath queries on the original XML agree
        $xpRoot = XPath::getXPath($root, true);
        $absNodes = XPath::xpQuery($root, $absolute, $xpRoot);
        $relNodes = XPath::xpQuery($element, $relative, $xpMsg);
        $this->assertCount(
            count($relNodes),
            $absNodes,
            'Mismatch in node count between absolute and relative',
        );
        $this->assertSame(
            array_map(static fn($n) => $n->textContent, iterator_to_array($absNodes)),
            array_map(static fn($n) => $n->textContent, iterator_to_array($relNodes)),
            'Mismatch in node values between absolute and relative',
        );
    }
}
