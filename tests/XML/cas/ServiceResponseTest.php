<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\cas\AbstractCasElement;
use SimpleSAML\CAS\XML\cas\Attributes;
use SimpleSAML\CAS\XML\cas\AuthenticationDate;
use SimpleSAML\CAS\XML\cas\AuthenticationSuccess;
use SimpleSAML\CAS\XML\cas\IsFromNewLogin;
use SimpleSAML\CAS\XML\cas\LongTermAuthenticationRequestTokenUsed;
use SimpleSAML\CAS\XML\cas\ProxyGrantingTicket;
use SimpleSAML\CAS\XML\cas\ServiceResponse;
use SimpleSAML\CAS\XML\cas\User;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SchemaValidationTestTrait;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\ServiceResponseTest
 *
 * @package simplesamlphp/cas
 */
#[CoversClass(ServiceResponse::class)]
#[CoversClass(AbstractCasElement::class)]
final class ServiceResponseTest extends TestCase
{
    use SchemaValidationTestTrait;
    use SerializableElementTestTrait;

    /** @var \DateTimeImmutable */
    private static DateTimeImmutable $authenticationDate;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = ServiceResponse::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 4) . '/resources/xml/cas_serviceResponse.xml',
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

        $authenticationSuccess = new Authenticationsuccess(
            new User('username'),
            new Attributes($authenticationDate, $longTerm, $isFromNewLogin, [$myAttribute]),
            new ProxyGrantingTicket('PGTIOU-84678-8a9d...'),
        );
        $serviceResponse = new ServiceResponse($authenticationSuccess);

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($serviceResponse),
        );
    }
}
