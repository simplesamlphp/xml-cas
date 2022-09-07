<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\cas\IsFromNewLogin;
use SimpleSAML\Test\XML\SerializableXMLTestTrait;
use SimpleSAML\XML\DOMDocumentFactory;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\IsFromNewLoginTest
 *
 * @covers \SimpleSAML\CAS\XML\cas\IsFromNewLogin
 * @covers \SimpleSAML\CAS\XML\cas\AbstractCasElement
 *
 * @package simplesamlphp/cas
 */
final class IsFromNewLoginTest extends TestCase
{
    use SerializableXMLTestTrait;

    /**
     */
    protected function setUp(): void
    {
        $this->testedClass = IsFromNewLogin::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(dirname(__FILE__))) . '/resources/xml/cas_isFromNewLogin.xml',
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $isFromNewLogin = new IsFromNewLogin('true');

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($isFromNewLogin),
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $isFromNewLogin = IsFromNewLogin::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals('true', $isFromNewLogin->getContent());
    }
}
