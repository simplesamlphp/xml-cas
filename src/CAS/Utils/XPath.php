<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Utils;

use DOMNode;
use DOMXPath;
use SimpleSAML\CAS\Constants as C;

/**
 * Compilation of utilities for XPath.
 *
 * @package simplesamlphp/cas
 */
class XPath extends \SimpleSAML\XPath\XPath
{
    /**
     * Get a DOMXPath object that can be used to search for CAS elements.
     *
     * @param \DOMNode $node The document to associate to the DOMXPath object.
     *
     * @return \DOMXPath A DOMXPath object ready to use in the given document, with several
     *   cas-related namespaces already registered.
     */
    public static function getXPath(DOMNode $node): DOMXPath
    {
        $xp = parent::getXPath($node);
        $xp->registerNamespace('cas', C::NS_CAS);

        // Register additional namespaces declared on the document root so that
        // XPath queries can use vendor-specific prefixes (e.g. slate:*).
        // See registerRootNamespaces() below for protocol rationale.
        self::registerRootNamespaces($xp, $node);

        return $xp;
    }


    /**
     * Inspect the root element’s xmlns declarations and register all prefixed namespaces with DOMXPath.
     *
     * Protocol rationale and behavior:
     * - CAS v3 schema uses xs:any with processContents="lax" under <cas:attributes>. This explicitly allows
     *   “foreign” elements (e.g., slate:person) from any XML namespace to appear inside the attributes block.
     * - In XML, namespace declarations are made via xmlns attributes on elements:
     *     xmlns:cas="http://www.yale.edu/tp/cas"
     *     xmlns:slate="https://example.org/slate"
     * - XPath requires prefixes to address namespaced elements. The default namespace (xmlns="...") cannot be
     *   used without a prefix in XPath expressions, so we only register prefixed declarations.
     * - To make consumer-provided XPaths like 'cas:attributes/slate:person' work, we auto-register all prefixes
     *   declared on the document element into the DOMXPath instance. This preserves interoperability for any
     *   vendor-specific attributes permitted by the schema.
     *
     * Safety checks:
     * - Skip empty URIs, skip the default 'xmlns' declaration, and don’t override the 'cas' prefix already set.
     * - Only process attributes in the XMLNS namespace (http://www.w3.org/2000/xmlns/).
     *
     * @param \DOMXPath $xp   The XPath context to receive namespace registrations.
     * @param \DOMNode  $node Any node within the target DOM document.
     */
    private static function registerRootNamespaces(DOMXPath $xp, DOMNode $node): void
    {
        // Resolve the owner document and root element for namespace discovery.
        $doc = $node instanceof \DOMDocument ? $node : $node->ownerDocument;
        $root = $doc?->documentElement;

        if ($root === null || !$root->hasAttributes()) {
            return;
        }

        // XMLNS namespace URI per the Namespaces in XML spec.
        $xmlnsNamespace = 'http://www.w3.org/2000/xmlns/';

        foreach ($root->attributes as $attr) {
            // Only capture xmlns declarations (xmlns:prefix="uri" or xmlns="uri")
            if ($attr->namespaceURI !== $xmlnsNamespace) {
                continue;
            }

            $prefix = $attr->localName; // e.g., 'slate' for xmlns:slate, or 'xmlns' for the default namespace
            $uri = (string) $attr->nodeValue;

            // XPath requires a prefix; default xmlns cannot be used in XPath steps.
            // Also guard against empty URIs and avoid re-binding the already registered 'cas' prefix.
            if ($prefix === null || $prefix === '' || $prefix === 'xmlns' || $uri === '' || $prefix === 'cas') {
                continue;
            }

            // Register the namespace so XPath queries can use its prefix (e.g., slate:*).
            $xp->registerNamespace($prefix, $uri);
        }
    }
}
