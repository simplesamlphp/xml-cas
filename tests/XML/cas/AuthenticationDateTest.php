<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\XML\cas;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\cas\AuthenticationDate;
use SimpleSAML\Test\XML\SerializableElementTestTrait;
use SimpleSAML\XML\DOMDocumentFactory;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\AuthenticationDateTest
 *
 * @covers \SimpleSAML\CAS\XML\cas\AuthenticationDate
 * @covers \SimpleSAML\CAS\XML\cas\AbstractCasElement
 *
 * @package simplesamlphp/cas
 */
final class AuthenticationDateTest extends TestCase
{
    use SerializableElementTestTrait;

    /**
     */
    protected function setUp(): void
    {
        $this->testedClass = AuthenticationDate::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(dirname(__FILE__))) . '/resources/xml/cas_authenticationDate.xml'
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $authenticationDate = new AuthenticationDate('2015-11-12T09:30:10Z');

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($authenticationDate)
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $authenticationDate = AuthenticationDate::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals('2015-11-12T09:30:10Z', $authenticationDate->getContent());
    }
}
