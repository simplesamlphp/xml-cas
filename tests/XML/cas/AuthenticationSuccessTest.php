<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\Utils\XPath;
use SimpleSAML\CAS\XML\cas\AbstractCasElement;
use SimpleSAML\CAS\XML\cas\AbstractResponse;
use SimpleSAML\CAS\XML\cas\Attributes;
use SimpleSAML\CAS\XML\cas\AuthenticationDate;
use SimpleSAML\CAS\XML\cas\AuthenticationSuccess;
use SimpleSAML\CAS\XML\cas\IsFromNewLogin;
use SimpleSAML\CAS\XML\cas\LongTermAuthenticationRequestTokenUsed;
use SimpleSAML\CAS\XML\cas\Proxies;
use SimpleSAML\CAS\XML\cas\Proxy;
use SimpleSAML\CAS\XML\cas\ProxyGrantingTicket;
use SimpleSAML\CAS\XML\cas\User;
use SimpleSAML\XML\{Chunk, DOMDocumentFactory};
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Type\Builtin\{BooleanValue, DateTimeValue, StringValue};

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\AuthenticationSuccessTest
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

    /** @var \SimpleSAML\XMLSchema\Type\Builtin\DateTimeValue */
    private static DateTimeValue $authenticationDate;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = AuthenticationSuccess::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 4) . '/resources/xml/cas_authenticationSuccess.xml',
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
}
