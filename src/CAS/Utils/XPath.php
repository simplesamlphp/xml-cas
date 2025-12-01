<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Utils;

use DOMNode;
use DOMXPath;
use SimpleSAML\CAS\Constants as C;

/**
 * Compilation of utilities for XPath.
 *
 * @package simplesamlphp/xml-cas
 */
class XPath extends \SimpleSAML\XPath\XPath
{
    /**
     * Get a DOMXPath object that can be used to search for CAS elements.
     *
     * @param \DOMNode $node The document to associate to the DOMXPath object.
     * @param bool $autoregister Whether to auto-register all namespaces used in the document
     *
     * @return \DOMXPath A DOMXPath object ready to use in the given document, with several
     *   cas-related namespaces already registered.
     */
    public static function getXPath(DOMNode $node, bool $autoregister = false): DOMXPath
    {
        $xp = parent::getXPath($node, $autoregister);

        /*
         * - Registering 'cas' to the same URI again is fine.
         * - If someone previously bound 'cas' to a different URI on the same DOMXPath,
         *   your call will change its meaning for subsequent queries
         * */
        $xp->registerNamespace('cas', C::NS_CAS);

        return $xp;
    }
}
