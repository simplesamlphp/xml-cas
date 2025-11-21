<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Test\Utils;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleSAML\CAS\Constants as C;
use SimpleSAML\CAS\Utils\XPath;
use SimpleSAML\XML\DOMDocumentFactory;

/**
 * Class \SimpleSAML\CAS\Utils\XPath
 *
 * @package simplesamlphp/cas
 */
#[CoversClass(XPath::class)]
final class XPathTest extends TestCase
{
    /**
     * Verifies that XPath::getXPath auto-registers all namespaces declared on the
     * serviceResponse root, so queries can find both standard CAS elements (cas:*)
     * and custom, vendor-specific attributes allowed by xs:any (e.g., slate:*).
     * This guards against losing custom attributes due to missing namespace bindings.
     */
    public function testXpQueryFindsCasAndCustomAttributes(): void
    {
        $doc = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_custom_serviceResponse.xml',
        );

        // Use the original DOM to keep all xmlns declarations (cas + slate).
        $authn = $doc->getElementsByTagNameNS(C::NS_CAS, 'authenticationSuccess')->item(0);
        $this->assertNotNull($authn, 'authenticationSuccess element not found in document');

        $xp = XPath::getXPath($authn);

        // CAS attribute
        $userNodes = XPath::xpQuery($authn, 'cas:user', $xp);
        $this->assertCount(1, $userNodes);
        $this->assertSame('jdoe', $userNodes[0]->textContent);

        // Custom namespaced attributes (xs:any)
        $person = XPath::xpQuery($authn, 'cas:attributes/slate:person', $xp);
        $round  = XPath::xpQuery($authn, 'cas:attributes/slate:round', $xp);
        $ref    = XPath::xpQuery($authn, 'cas:attributes/slate:ref', $xp);

        $this->assertCount(1, $person);
        $this->assertSame('12345', $person[0]->textContent);

        $this->assertCount(1, $round);
        $this->assertSame('Fall-2025', $round[0]->textContent);

        $this->assertCount(1, $ref);
        $this->assertSame('ABC-123', $ref[0]->textContent);
    }


    /**
     * Ensures that absolute XPath mappings commonly used in configuration
     * (starting at /cas:serviceResponse/.../cas:authenticationSuccess/...) are
     * equivalent to their rewritten relative form. This validates the consumer
     * logic that rewrites absolute mappings to be relative to the context node.
     */
    public function testAbsoluteToRelativeRewriteYieldsSameResults(): void
    {
        $doc = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_custom_serviceResponse.xml',
        );
        $authn = $doc->getElementsByTagNameNS(C::NS_CAS, 'authenticationSuccess')->item(0);
        $this->assertNotNull($authn);

        $xp = XPath::getXPath($authn);

        // Absolute mapping as often used in configs
        $absolute = '/cas:serviceResponse/cas:authenticationSuccess/cas:attributes/slate:round';

        // Rewrite logic as in consumer code
        $marker = 'cas:authenticationSuccess/';
        $pos = strpos($absolute, $marker);
        $query = ($pos !== false) ? substr($absolute, $pos + strlen($marker)) : $absolute;

        $relative = 'cas:attributes/slate:round';

        $absNodes = XPath::xpQuery($authn, $query, $xp);     // rewritten absolute
        $relNodes = XPath::xpQuery($authn, $relative, $xp);  // direct relative

        $this->assertCount(count($relNodes), $absNodes);
        if (count($relNodes) > 0 && count($absNodes) > 0) {
            $this->assertSame(
                $relNodes[0]->textContent,
                $absNodes[0]->textContent,
            );
        }
    }


    /**
     * Validates handling of multiple custom namespaces under xs:any and
     * multi-valued attributes. Confirms that queries can extract repeated elements
     * (e.g., slate:role) and elements from another prefix (e.g., foo:level),
     * demonstrating robust multi-namespace, xs:any support.
     */
    public function testMultipleCustomNamespacesUnderXsAny(): void
    {
        $doc = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_multi_namespace_serviceResponse.xml',
        );
        $authn = $doc->getElementsByTagNameNS(C::NS_CAS, 'authenticationSuccess')->item(0);
        $this->assertNotNull($authn);

        $xp = XPath::getXPath($authn);

        // Multi-valued slate:role
        $roles = XPath::xpQuery($authn, 'cas:attributes/slate:role', $xp);
        $this->assertCount(2, $roles);
        $this->assertSame('student', $roles[0]->textContent);
        $this->assertSame('assistant', $roles[1]->textContent);

        // Another custom namespace
        $level = XPath::xpQuery($authn, 'cas:attributes/foo:level', $xp);
        $this->assertCount(1, $level);
        $this->assertSame('advanced', $level[0]->textContent);
    }


    public function testCasServiceResponseAttributes(): void
    {
        $doc = DOMDocumentFactory::fromFile(
            dirname(__FILE__, 3) . '/resources/xml/cas_c_serviceResponse.xml',
        );
        $authn = $doc->getElementsByTagNameNS(C::NS_CAS, 'authenticationSuccess')->item(0);
        $this->assertNotNull($authn, 'authenticationSuccess element not found in document');

        $xp = XPath::getXPath($authn);

        $attributes = [
            'sn' => 'Doe',
            'firstname' => 'John',
            'mail' => 'jdoe@example.edu',
            'eduPersonPrincipalName' => 'jdoe@example.edu',
        ];

        foreach ($attributes as $name => $expected) {
            $nodes = XPath::xpQuery($authn, "cas:attributes/cas:$name", $xp);
            $this->assertCount(1, $nodes, "Attribute $name not found");
            $this->assertSame($expected, $nodes[0]->textContent, "Incorrect value for $name");
        }
    }
}
