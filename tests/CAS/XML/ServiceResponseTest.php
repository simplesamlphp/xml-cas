<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
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
}
