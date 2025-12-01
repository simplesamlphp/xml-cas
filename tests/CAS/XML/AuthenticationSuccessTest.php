<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\Utils\XPath;
use SimpleSAML\CAS\XML\AbstractCasElement;
use SimpleSAML\CAS\XML\AbstractResponse;
use SimpleSAML\CAS\XML\Attributes;
use SimpleSAML\CAS\XML\AuthenticationDate;
use SimpleSAML\CAS\XML\AuthenticationSuccess;
use SimpleSAML\CAS\XML\IsFromNewLogin;
use SimpleSAML\CAS\XML\LongTermAuthenticationRequestTokenUsed;
use SimpleSAML\CAS\XML\Proxies;
use SimpleSAML\CAS\XML\Proxy;
use SimpleSAML\CAS\XML\ProxyGrantingTicket;
use SimpleSAML\CAS\XML\User;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Type\BooleanValue;
use SimpleSAML\XMLSchema\Type\DateTimeValue;
use SimpleSAML\XMLSchema\Type\StringValue;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\AuthenticationSuccessTest
 *
 * @package simplesamlphp/cas
 */
#[CoversClass(XPath::class)]
#[CoversClass(AuthenticationSuccess::class)]
#[CoversClass(AbstractResponse::class)]
#[CoversClass(AbstractCasElement::class)]
final class AuthenticationSuccessTest extends TestCase
{
    use SerializableElementTestTrait;


    /** @var \SimpleSAML\XMLSchema\Type\DateTimeValue */
    private static DateTimeValue $authenticationDate;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = AuthenticationSuccess::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_authenticationSuccess.xml',
        );

        self::$authenticationDate = DateTimeValue::fromString('2015-11-12T09:30:10Z');
    }


    /**
     */
    public function testMarshalling(): void
    {
        /** @var \DOMElement $firstNameElt */
        $firstNameElt = DOMDocumentFactory::fromString(
            '<cas:firstname xmlns:cas="http://www.yale.edu/tp/cas">John</cas:firstname>',
        )->documentElement;

        $firstName = new Chunk($firstNameElt);

        /** @var \DOMElement $lastNameElt */
        $lastNameElt = DOMDocumentFactory::fromString(
            '<cas:lastname xmlns:cas="http://www.yale.edu/tp/cas">Doe</cas:lastname>',
        )->documentElement;
        $lastName = new Chunk($lastNameElt);

        /** @var \DOMElement $emailElt */
        $emailElt = DOMDocumentFactory::fromString(
            '<cas:email xmlns:cas="http://www.yale.edu/tp/cas">jdoe@example.org</cas:email>',
        )->documentElement;
        $email = new Chunk($emailElt);

        $authenticationDate = new AuthenticationDate(self::$authenticationDate);
        $longTerm = new LongTermAuthenticationRequestTokenUsed(BooleanValue::fromString('true'));
        $isFromNewLogin = new IsFromNewLogin(BooleanValue::fromString('true'));

        $user = new User(StringValue::fromString('username'));
        $attributes = new Attributes($authenticationDate, $longTerm, $isFromNewLogin, [$firstName, $lastName, $email]);
        $proxyGrantingTicket = new ProxyGrantingTicket(StringValue::fromString('PGTIOU-84678-8a9d...'));
        $proxies = new Proxies([
            new Proxy(StringValue::fromString('https://proxy2/pgtUrl')),
            new Proxy(StringValue::fromString('https://proxy1/pgtUrl')),
        ]);

        $authenticationSuccess = new AuthenticationSuccess($user, $attributes, $proxyGrantingTicket, $proxies);

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($authenticationSuccess),
        );
    }


    public function testMarshallingElementOrdering(): void
    {
        /** @var \DOMElement $firstNameElt */
        $firstNameElt = DOMDocumentFactory::fromString(
            '<cas:firstname xmlns:cas="http://www.yale.edu/tp/cas">John</cas:firstname>',
        )->documentElement;

        $firstName = new Chunk($firstNameElt);

        /** @var \DOMElement $lastNameElt */
        $lastNameElt = DOMDocumentFactory::fromString(
            '<cas:lastname xmlns:cas="http://www.yale.edu/tp/cas">Doe</cas:lastname>',
        )->documentElement;
        $lastName = new Chunk($lastNameElt);

        /** @var \DOMElement $emailElt */
        $emailElt = DOMDocumentFactory::fromString(
            '<cas:email xmlns:cas="http://www.yale.edu/tp/cas">jdoe@example.org</cas:email>',
        )->documentElement;
        $email = new Chunk($emailElt);

        $authenticationDate = new AuthenticationDate(self::$authenticationDate);
        $longTerm = new LongTermAuthenticationRequestTokenUsed(BooleanValue::fromString('true'));
        $isFromNewLogin = new IsFromNewLogin(BooleanValue::fromString('true'));

        $user = new User(StringValue::fromString('username'));
        $attributes = new Attributes($authenticationDate, $longTerm, $isFromNewLogin, [$firstName, $lastName, $email]);
        $proxyGrantingTicket = new ProxyGrantingTicket(StringValue::fromString('PGTIOU-84678-8a9d...'));
        $proxies = new Proxies([
            new Proxy(StringValue::fromString('https://proxy2/pgtUrl')),
            new Proxy(StringValue::fromString('https://proxy1/pgtUrl')),
        ]);

        $authenticationSuccess = new AuthenticationSuccess($user, $attributes, $proxyGrantingTicket, $proxies);
        $authenticationSuccessElement = $authenticationSuccess->toXML();

        // Test for a user-element
        $xpCache = XPath::getXPath($authenticationSuccessElement);
        $authenticationSuccessElements = XPath::xpQuery($authenticationSuccessElement, './cas:user', $xpCache);
        $this->assertCount(1, $authenticationSuccessElements);

        // Test ordering of cas:authenticationSuccess contents
        /** @psalm-var \DOMElement[] $authenticationSuccessElements */
        $authenticationSuccessElements = XPath::xpQuery(
            $authenticationSuccessElement,
            './cas:user/following-sibling::*',
            $xpCache,
        );

        $this->assertCount(3, $authenticationSuccessElements);
        $this->assertEquals('cas:attributes', $authenticationSuccessElements[0]->tagName);
        $this->assertEquals('cas:proxyGrantingTicket', $authenticationSuccessElements[1]->tagName);
        $this->assertEquals('cas:proxies', $authenticationSuccessElements[2]->tagName);
    }


    /**
     * Ensure that AuthenticationSuccess::fromXML can parse a Slate-style CAS response
     * where:
     * - cas:authenticationSuccess has top-level vendor elements (slate:person/round/ref)
     * - cas:attributes is missing CAS core fields (authenticationDate, longTerm..., isFromNewLogin)
     *
     * Parsing must succeed in lenient mode (default strictness).
     */
    public function testParsesSlateAuthenticationSuccessLenient(): void
    {
        $doc = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas-slate-success-response.xml',
        );

        /** @var \DOMElement|null $authnEl */
        $authnEl = $doc->getElementsByTagNameNS('http://www.yale.edu/tp/cas', 'authenticationSuccess')->item(0);
        $this->assertInstanceOf(
            \DOMElement::class,
            $authnEl,
            'Slate XML must contain cas:authenticationSuccess',
        );

        // Default parsing is lenient (no strict flag), so missing CAS attributes inside cas:attributes
        // should not make parsing fail.
        $authn = AuthenticationSuccess::fromXML($authnEl);

        // User must be parsed
        $this->assertSame(
            'example-user@technolutions.com',
            (string) $authn->getUser()->getContent(),
            'Unexpected cas:user value from Slate response',
        );

        // cas:attributes element exists
        $attrs = $authn->getAttributes();

        // Assert core CAS attributes are NOT present (all must be null)
        $this->assertNull(
            $attrs->getAuthenticationDate(),
            'Slate AuthenticationSuccess must not contain cas:authenticationDate',
        );
        $this->assertNull(
            $attrs->getLongTermAuthenticationRequestTokenUsed(),
            'Slate AuthenticationSuccess must not contain cas:longTermAuthenticationRequestTokenUsed',
        );
        $this->assertNull(
            $attrs->getIsFromNewLogin(),
            'Slate AuthenticationSuccess must not contain cas:isFromNewLogin',
        );

        // Ensure extension attributes (firstname/lastname/email) were preserved as Chunks.
        $chunks = $attrs->getElements();
        $this->assertNotEmpty($chunks, 'Expected at least one attribute Chunk from Slate response');

        $names = [];
        foreach ($chunks as $chunk) {
            if ($chunk instanceof Chunk) {
                $names[] = $chunk->getLocalName();
            }
        }

        $this->assertContains('firstname', $names);
        $this->assertContains('lastname', $names);
        $this->assertContains('email', $names);

        // Top-level Slate elements under cas:authenticationSuccess should be preserved as metadata
        $metadata = $authn->getAuthenticationSuccessMetadata();
        $this->assertCount(3, $metadata, 'Expected three top-level Slate metadata elements');

        $seen = [];
        /** @var \DOMElement $el */
        foreach ($metadata as $el) {
            $this->assertSame('http://technolutions.com/slate', $el->namespaceURI);
            $seen[$el->localName] = trim($el->textContent ?? '');
        }

        $this->assertSame('345d2e1b-65de-419c-96ce-e1866d4c57cd', $seen['person'] ?? null);
        $this->assertSame('Regular Decision', $seen['round'] ?? null);
        $this->assertSame('774482874', $seen['ref'] ?? null);
    }
}
