<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\CAS\XML\cas;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\XML\cas\Attributes;
use SimpleSAML\CAS\XML\cas\AuthenticationDate;
use SimpleSAML\CAS\XML\cas\IsFromNewLogin;
use SimpleSAML\CAS\XML\cas\LongTermAuthenticationRequestTokenUsed;
use SimpleSAML\Test\XML\SerializableXMLTestTrait;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\DOMDocumentFactory;

use function dirname;
use function strval;

/**
 * Class \SimpleSAML\CAS\XML\cas\AttributesTest
 *
 * @covers \SimpleSAML\CAS\XML\cas\Attributes
 * @covers \SimpleSAML\CAS\XML\cas\AbstractCasElement
 *
 * @package simplesamlphp/cas
 */
final class AttributesTest extends TestCase
{
    use SerializableXMLTestTrait;

    /**
     */
    protected function setUp(): void
    {
        $this->testedClass = Attributes::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(dirname(__FILE__))) . '/resources/xml/cas_attributes.xml'
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
            '<cas:myAttribute xmlns:cas="http://www.yale.edu/tp/cas">myValue</cas:myAttribute>'
        );
        $myAttribute = new Chunk($document->documentElement);
        $attributes = new Attributes($authenticationDate, $longTerm, $isFromNewLogin, [$myAttribute]);

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($attributes)
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $attributes = Attributes::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals('2015-11-12T09:30:10Z', $attributes->getAuthenticationDate()->getContent());
        $this->assertEquals('true', $attributes->getIsFromNewLogin()->getContent());
        $this->assertEquals('true', $attributes->getLongTermAuthenticationRequestTokenUsed()->getContent());

        $myAttributeElement = $attributes->getElements()[0]->getXML();

        $this->assertEquals('myAttribute', $myAttributeElement->localName);
        $this->assertEquals('myValue', $myAttributeElement->textContent);
    }
}
