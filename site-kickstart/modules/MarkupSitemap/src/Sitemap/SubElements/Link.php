<?php

/**
 * Sitemap for PHP. Eloquent sitemap creation with sub-element support.
 * https://github.com/ThePixelDeveloper/Sitemap/
 * Local fork maintained by Mike Rockett for MarkupSitemap.
 *
 * @copyright 2013, Mathew Davies <thepixeldeveloper@googlemail.com>
 * @license   MIT
 */

namespace Rockett\Sitemap\SubElements;

use Rockett\Sitemap\Contracts\AppendAttributeContract;
use Rockett\Sitemap\Contracts\OutputContract;
use XMLWriter;

/**
 * Class Link
 *
 * @package Rockett\Sitemap\SubElements
 */
class Link implements OutputContract, AppendAttributeContract
{
    /**
     * Location of the translated page.
     *
     * @var string
     */
    protected $href;

    /**
     * Language code for the page.
     *
     * @var string
     */
    protected $hrefLang;

    /**
     * Link constructor.
     *
     * @param string $hrefLang
     * @param string $href
     */
    public function __construct($hrefLang, $href)
    {
        $this->hrefLang = $hrefLang;
        $this->href = $href;
    }

    /**
     * {@inheritdoc}
     */
    public function appendAttributeToCollectionXML(XMLWriter $XMLWriter)
    {
        $XMLWriter->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
    }

    /**
     * {@inheritdoc}
     */
    public function generateXML(XMLWriter $XMLWriter)
    {
        $XMLWriter->startElement('xhtml:link');
        $XMLWriter->writeAttribute('rel', 'alternate');
        $XMLWriter->writeAttribute('hreflang', $this->hrefLang);
        $XMLWriter->writeAttribute('href', $this->href);
        $XMLWriter->endElement();
    }

    /**
     * Location of the translated page.
     *
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Language code for the page.
     *
     * @return string
     */
    public function getHrefLang()
    {
        return $this->hrefLang;
    }
}
