<?php

/**
 * Sitemap for PHP. Eloquent sitemap creation with sub-element support.
 * https://github.com/ThePixelDeveloper/Sitemap/
 * Local fork maintained by Mike Rockett for MarkupSitemap.
 *
 * @copyright 2013, Mathew Davies <thepixeldeveloper@googlemail.com>
 * @license   MIT
 */

namespace Rockett\Sitemap\Elements;

use Rockett\Sitemap\Contracts\OutputContract;
use XMLWriter;

/**
 * Class Urlset
 *
 * @package Rockett\Sitemap\Elements
 */
class Urlset implements OutputContract
{
    /**
     * Sub-elements that have been appended to the collection attributes.
     *
     * @var AppendAttributeInterface[]
     */
    protected $appendedSubElements = [];

    /**
     * Array of URL objects.
     *
     * @var OutputContract[]
     */
    protected $urls = [];

    /**
     * Add a new URL object.
     *
     * @param  OutputContract $url
     * @return $this
     */
    public function addUrl(OutputContract $url)
    {
        $this->urls[] = $url;

        return $this;
    }

    /**
     * Appends the sub-element to the collection attributes if it has yet to be visited.
     *
     * @param  XMLWriter       $XMLWriter
     * @param  OutputContract $subElement
     * @return boolean
     */
    public function appendSubElementAttribute(XMLWriter $XMLWriter, OutputContract $subElement)
    {
        if (array_key_exists(get_class($subElement), $this->appendedSubElements)) {
            return false;
        }

        $subElement->appendAttributeToCollectionXML($XMLWriter);
        $this->appendedSubElements[get_class($subElement)] = $subElement;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function generateXML(XMLWriter $XMLWriter)
    {
        $XMLWriter->startElement('urlset');

        $XMLWriter->writeAttribute('xmlns:xsi', 'https://www.w3.org/2001/XMLSchema-instance');

        $XMLWriter->writeAttribute(
            'xsi:schemaLocation',
            'http://www.sitemaps.org/schemas/sitemap/0.9 ' .
            'https://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd'
        );

        $XMLWriter->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($this->getUrls() as $url) {
            foreach ($url->getSubElementsThatAppend() as $subElement) {
                $this->appendSubElementAttribute($XMLWriter, $subElement);
            }
        }

        foreach ($this->getUrls() as $url) {
            $url->generateXML($XMLWriter);
        }

        $XMLWriter->endElement();
    }

    /**
     * Get array of URL objects.
     *
     * @return OutputContract[]
     */
    public function getUrls()
    {
        return $this->urls;
    }
}
