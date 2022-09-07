<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\Utils\XPath;
use SimpleSAML\CAS\XML\cas\Attributes;
use SimpleSAML\CAS\XML\cas\AuthenticationDate;
use SimpleSAML\CAS\XML\cas\AuthenticationSuccess;
use SimpleSAML\CAS\XML\cas\IsFromNewLogin;
use SimpleSAML\CAS\XML\cas\LongTermAuthenticationRequestTokenUsed;
use SimpleSAML\CAS\XML\cas\ProxyGrantingTicket;
use SimpleSAML\CAS\XML\cas\Proxies;
use SimpleSAML\CAS\XML\cas\Proxy;
use SimpleSAML\CAS\XML\cas\User;
use SimpleSAML\Test\XML\SerializableElementTestTrait;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\DOMDocumentFactory;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\AuthenticationSuccessTest
 *
 * @covers \SimpleSAML\CAS\Utils\XPath
 * @covers \SimpleSAML\CAS\XML\cas\AuthenticationSuccess
 * @covers \SimpleSAML\CAS\XML\cas\AbstractResponse
 * @covers \SimpleSAML\CAS\XML\cas\AbstractCasElement
 *
 * @package simplesamlphp/cas
 */
final class AuthenticationSuccessTest extends TestCase
{
    use SerializableElementTestTrait;

    /**
     */
    protected function setUp(): void
    {
        $this->testedClass = AuthenticationSuccess::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(dirname(__FILE__))) . '/resources/xml/cas_authenticationSuccess.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $firstNameDocument = DOMDocumentFactory::fromString(
            '<cas:firstname xmlns:cas="http://www.yale.edu/tp/cas">John</cas:firstname>',
        );
        $firstName = new Chunk($firstNameDocument->documentElement);

        $lastNameDocument = DOMDocumentFactory::fromString(
            '<cas:lastname xmlns:cas="http://www.yale.edu/tp/cas">Doe</cas:lastname>',
        );
        $lastName = new Chunk($lastNameDocument->documentElement);

        $emailDocument = DOMDocumentFactory::fromString(
            '<cas:email xmlns:cas="http://www.yale.edu/tp/cas">jdoe@example.org</cas:email>',
        );
        $email = new Chunk($emailDocument->documentElement);

        $authenticationDate = new AuthenticationDate('2015-11-12T09:30:10Z');
        $longTerm = new LongTermAuthenticationRequestTokenUsed('true');
        $isFromNewLogin = new IsFromNewLogin('true');

        $user = new User('username');
        $attributes = new Attributes($authenticationDate, $longTerm, $isFromNewLogin, [$firstName, $lastName, $email]);
        $proxyGrantingTicket = new ProxyGrantingTicket('PGTIOU-84678-8a9d...');
        $proxies = new Proxies([
            new Proxy('https://proxy2/pgtUrl'),
            new Proxy('https://proxy1/pgtUrl'),
        ]);

        $authenticationSuccess = new AuthenticationSuccess($user, $attributes, $proxyGrantingTicket, $proxies);

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($authenticationSuccess),
        );
    }


    public function testMarshallingElementOrdering(): void
    {
        $firstNameDocument = DOMDocumentFactory::fromString(
            '<cas:firstname xmlns:cas="http://www.yale.edu/tp/cas">John</cas:firstname>',
        );
        $firstName = new Chunk($firstNameDocument->documentElement);

        $lastNameDocument = DOMDocumentFactory::fromString(
            '<cas:lastname xmlns:cas="http://www.yale.edu/tp/cas">Doe</cas:lastname>',
        );
        $lastName = new Chunk($lastNameDocument->documentElement);

        $emailDocument = DOMDocumentFactory::fromString(
            '<cas:email xmlns:cas="http://www.yale.edu/tp/cas">jdoe@example.org</cas:email>',
        );
        $email = new Chunk($emailDocument->documentElement);

        $authenticationDate = new AuthenticationDate('2015-11-12T09:30:10Z');
        $longTerm = new LongTermAuthenticationRequestTokenUsed('true');
        $isFromNewLogin = new IsFromNewLogin('true');

        $user = new User('username');
        $attributes = new Attributes($authenticationDate, $longTerm, $isFromNewLogin, [$firstName, $lastName, $email]);
        $proxyGrantingTicket = new ProxyGrantingTicket('PGTIOU-84678-8a9d...');
        $proxies = new Proxies([
            new Proxy('https://proxy2/pgtUrl'),
            new Proxy('https://proxy1/pgtUrl'),
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
     */
    public function testUnmarshalling(): void
    {
        $authenticationSuccess = AuthenticationSuccess::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals('username', $authenticationSuccess->getUser()->getContent());

        $attributes = $authenticationSuccess->getAttributes();
        $this->assertEquals('true', $attributes->getIsFromNewLogin()->getContent());
        $this->assertEquals('true', $attributes->getLongTermAuthenticationRequestTokenUsed()->getContent());
        $this->assertCount(3, $attributes->getElements());

        $firstNameElement = $attributes->getElements()[0]->getXML();
        $lastNameElement = $attributes->getElements()[1]->getXML();
        $emailElement = $attributes->getElements()[2]->getXML();

        $this->assertEquals('firstname', $firstNameElement->localName);
        $this->assertEquals('John', $firstNameElement->textContent);
        $this->assertEquals('lastname', $lastNameElement->localName);
        $this->assertEquals('Doe', $lastNameElement->textContent);
        $this->assertEquals('email', $emailElement->localName);
        $this->assertEquals('jdoe@example.org', $emailElement->textContent);

        $this->assertEquals('PGTIOU-84678-8a9d...', $authenticationSuccess->getProxyGrantingTicket()?->getContent());

        $proxies = $authenticationSuccess->getProxies();
        /** @psalm-var \SimpleSAML\CAS\XML\cas\Proxy[] $proxy */
        $proxy = $proxies?->getProxy();

        $this->assertCount(2, $proxy);
        $this->assertEquals('https://proxy2/pgtUrl', $proxy[0]->getContent());
        $this->assertEquals('https://proxy1/pgtUrl', $proxy[1]->getContent());
    }
}
