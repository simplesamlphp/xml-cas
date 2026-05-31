<?php

declare(strict_types=1);

namespace SimpleSAML\CAS\Utils;

use Dom;
use SimpleSAML\CAS\Constants as C;

/**
 * Compilation of utilities for XPath.
 *
 * @package simplesamlphp/xml-cas
 */
class XPath extends \SimpleSAML\XPath\XPath
{
    /**
     * Get a Dom\XPath object that can be used to search for CAS elements.
     *
     * @param \Dom\Node $node The document to associate to the Dom\XPath object.
     * @param bool $autoregister Whether to auto-register all namespaces used in the document
     *
     * @return \Dom\XPath A Dom\XPath object ready to use in the given document, with several
     *   cas-related namespaces already registered.
     */
    public static function getXPath(Dom\Node $node, bool $autoregister = false): Dom\XPath
    {
        $xp = parent::getXPath($node, $autoregister);

        /*
         * - Registering 'cas' to the same URI again is fine.
         * - If someone previously bound 'cas' to a different URI on the same Dom\XPath,
         *   your call will change its meaning for subsequent queries
         * */
        $xp->registerNamespace('cas', C::NS_CAS);

        return $xp;
    }
}
