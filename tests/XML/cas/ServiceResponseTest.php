<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\cas\Attributes;
use SimpleSAML\CAS\XML\cas\AuthenticationDate;
use SimpleSAML\CAS\XML\cas\AuthenticationSuccess;
use SimpleSAML\CAS\XML\cas\IsFromNewLogin;
use SimpleSAML\CAS\XML\cas\LongTermAuthenticationRequestTokenUsed;
use SimpleSAML\CAS\XML\cas\ProxyGrantingTicket;
use SimpleSAML\CAS\XML\cas\ServiceResponse;
use SimpleSAML\CAS\XML\cas\User;
use SimpleSAML\Test\XML\SerializableElementTestTrait;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\DOMDocumentFactory;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\ServiceResponseTest
 *
 * @covers \SimpleSAML\CAS\XML\cas\ServiceResponse
 * @covers \SimpleSAML\CAS\XML\cas\AbstractCasElement
 *
 * @package simplesamlphp/cas
 */
final class ServiceResponseTest extends TestCase
{
    use SerializableElementTestTrait;

    /**
     */
    protected function setUp(): void
    {
        $this->testedClass = ServiceResponse::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(dirname(__FILE__))) . '/resources/xml/cas_serviceResponse.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $authenticationDate = new AuthenticationDate('2015-11-12T09:30:10Z');
        $longTerm = new LongTermAuthenticationRequestTokenUsed('true');
        $isFromNewLogin = new IsFromNewLogin('true');
        $document = DOMDocumentFactory::fromString(
            '<cas:myAttribute xmlns:cas="http://www.yale.edu/tp/cas">myValue</cas:myAttribute>',
        );
        $myAttribute = new Chunk($document->documentElement);

        $authenticationSuccess = new Authenticationsuccess(
            new User('username'),
            new Attributes($authenticationDate, $longTerm, $isFromNewLogin, [$myAttribute]),
            new ProxyGrantingTicket('PGTIOU-84678-8a9d...'),
        );
        $serviceResponse = new ServiceResponse($authenticationSuccess);

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($serviceResponse),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $serviceResponse = ServiceResponse::fromXML($this->xmlRepresentation->documentElement);
        $authenticationSuccess = $serviceResponse->getResponse();
        $this->assertInstanceOf(AuthenticationSuccess::class, $authenticationSuccess);

        $this->assertEquals('username', $authenticationSuccess->getUser()->getContent());

        $attributes = $authenticationSuccess->getAttributes();
        $this->assertEquals('2015-11-12T09:30:10Z', $attributes->getAuthenticationDate()->getContent());
        $this->assertEquals('true', $attributes->getIsFromNewLogin()->getContent());
        $this->assertEquals('true', $attributes->getLongTermAuthenticationRequestTokenUsed()->getContent());

        $myAttributeElement = $attributes->getElements()[0]->getXML();

        $this->assertEquals('myAttribute', $myAttributeElement->localName);
        $this->assertEquals('myValue', $myAttributeElement->textContent);

        $this->assertEquals('PGTIOU-84678-8a9d...', $authenticationSuccess->getProxyGrantingTicket()?->getContent());
    }
}
