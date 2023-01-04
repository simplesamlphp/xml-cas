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
use SimpleSAML\Test\XML\SchemaValidationTestTrait;
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
    use SchemaValidationTestTrait;
    use SerializableElementTestTrait;

    /**
     */
    protected function setUp(): void
    {
        $this->schema = dirname(__FILE__, 4) . '/schemas/cas-server-protocol-3.0.xsd';

        $this->testedClass = ServiceResponse::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_serviceResponse.xml',
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

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($serviceResponse),
        );
    }
}
