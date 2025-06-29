<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\cas\AbstractCasElement;
use SimpleSAML\CAS\XML\cas\User;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XML\TestUtils\SerializableElementTestTrait;
use SimpleSAML\XMLSchema\Type\Builtin\StringValue;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\UserTest
 *
 * @package simplesamlphp/cas
 */
#[CoversClass(User::class)]
#[CoversClass(AbstractCasElement::class)]
final class UserTest extends TestCase
{
    use SerializableElementTestTrait;


    /**
     */
    public static function setUpBeforeClass(): void
    {
        self::$testedClass = User::class;

        self::$xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 4) . '/resources/xml/cas_user.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $user = new User(StringValue::fromString('username'));

        $this->assertEquals(
            self::$xmlRepresentation->saveXML(self::$xmlRepresentation->documentElement),
            strval($user),
        );
    }
}
