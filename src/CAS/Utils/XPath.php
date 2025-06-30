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
class XPath extends \SimpleSAML\XML\Utils\XPath
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

        return $xp;
    }
}
