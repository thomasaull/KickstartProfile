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
 * Class Sitemap
 *
 * @package Rockett\Sitemap
 */
class Sitemap implements OutputContract
{
    /**
     * Last modified time.
     *
     * @var string
     */
    protected $lastMod;

    /**
     * Location (URL).
     *
     * @var string
     */
    protected $loc;

    /**
     * Url constructor
     *
     * @param string $loc
     */
    public function __construct($loc)
    {
        $this->loc = $loc;
    }

    /**
     * {@inheritdoc}
     */
    public function generateXML(XMLWriter $XMLWriter)
    {
        $XMLWriter->startElement('sitemap');
        $XMLWriter->writeElement('loc', $this->getLoc());

        if ($lastMod = $this->getLastMod()) {
            $XMLWriter->writeElement('lastmod', $lastMod);
        }

        $XMLWriter->endElement();
    }

    /**
     * Get the last modification time.
     *
     * @return string|null
     */
    public function getLastMod()
    {
        return $this->lastMod;
    }

    /**
     * Get location (URL).
     *
     * @return string
     */
    public function getLoc()
    {
        return $this->loc;
    }

    /**
     * Set the last modification time.
     *
     * @param  string  $lastMod
     * @return $this
     */
    public function setLastMod($lastMod)
    {
        $this->lastMod = $lastMod;

        return $this;
    }
}
