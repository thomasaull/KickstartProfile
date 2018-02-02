<?php

/**
 * Sitemap for PHP. Eloquent sitemap creation with sub-element support.
 * https://github.com/ThePixelDeveloper/Sitemap/
 * Local fork maintained by Mike Rockett for MarkupSitemap.
 *
 * @copyright 2013, Mathew Davies <thepixeldeveloper@googlemail.com>
 * @license   MIT
 */

namespace Rockett\Sitemap\Contracts;

use XMLWriter;

/**
 * Interface AppendAttributeInterface
 *
 * @package Rockett\Sitemap
 */
interface AppendAttributeContract
{
    /**
     * Appends an attribute to the collection XML attributes.
     *
     * @param  XMLWriter $XMLWriter
     * @return void
     */
    public function appendAttributeToCollectionXML(XMLWriter $XMLWriter);
}
