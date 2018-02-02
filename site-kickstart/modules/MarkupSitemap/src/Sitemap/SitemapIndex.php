<?php

/**
 * Sitemap for PHP. Eloquent sitemap creation with sub-element support.
 * https://github.com/ThePixelDeveloper/Sitemap/
 * Local fork maintained by Mike Rockett for MarkupSitemap.
 *
 * @copyright 2013, Mathew Davies <thepixeldeveloper@googlemail.com>
 * @license   MIT
 */

namespace Rockett\Sitemap;

use Rockett\Sitemap\Contracts\OutputContract;
use XMLWriter;

/**
 * Class SitemapIndex
 * RESERVED FOR FUTURE USE
 *
 * @package Rockett\Sitemap
 */
class SitemapIndex implements OutputContract
{
    /**
     * Array of Sitemap entries.
     *
     * @var OutputContract[]
     */
    protected $sitemaps = [];

    /**
     * Add a new Sitemap object to the collection.
     *
     * @param  OutputContract $sitemap
     * @return $this
     */
    public function addSitemap(OutputContract $sitemap)
    {
        $this->sitemaps[] = $sitemap;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function generateXML(XMLWriter $XMLWriter)
    {
        $XMLWriter->startElement('sitemapindex');
        $XMLWriter->writeAttribute('xmlns:xsi', 'https://www.w3.org/2001/XMLSchema-instance');

        $XMLWriter->writeAttribute(
            'xsi:schemaLocation',
            'http://www.sitemaps.org/schemas/sitemap/0.9 ' .
            'https://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd'
        );

        $XMLWriter->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($this->getSitemaps() as $sitemap) {
            $sitemap->generateXML($XMLWriter);
        }

        $XMLWriter->endElement();
    }

    /**
     * Get an array of Sitemap objects.
     *
     * @return OutputContract[]
     */
    public function getSitemaps()
    {
        return $this->sitemaps;
    }
}
