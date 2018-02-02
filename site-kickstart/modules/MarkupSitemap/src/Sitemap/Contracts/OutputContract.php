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
 * Interface OutputInterface
 *
 * @package Rockett\Sitemap
 */
interface OutputContract
{
    /**
     * Generate the XML for a given element / sub-element.
     *
     * @param  XMLWriter $XMLWriter
     * @return void
     */
    public function generateXML(XMLWriter $XMLWriter);
}
