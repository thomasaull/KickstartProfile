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

use Rockett\Sitemap\Contracts\AppendAttributeContract;
use Rockett\Sitemap\Contracts\OutputContract;
use XMLWriter;

/**
 * Class Url
 *
 * @package Rockett\Sitemap\Elements
 */
class Url implements OutputContract
{
    /**
     * Change frequency of the location.
     *
     * @var string
     */
    protected $changeFreq;

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
     * Priority of page importance.
     *
     * @var string
     */
    protected $priority;

    /**
     * Array of sub-elements.
     *
     * @var OutputContract[]
     */
    protected $subElements = [];

    /**
     * Sub-elements that append to the collection attributes.
     *
     * @var AppendAttributeContract[]
     */
    protected $subElementsThatAppend = [];

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
     * Add a new sub element.
     *
     * @param  OutputContract $subElement
     * @return $this
     */
    public function addSubElement(OutputContract $subElement)
    {
        $this->subElements[] = $subElement;

        if ($this->isSubElementGoingToAppend($subElement)) {
            $this->subElementsThatAppend[get_class($subElement)] = $subElement;
        }

        return $this;
    }

    /**
     * @param XMLWriter $XMLWriter
     */
    public function generateXML(XMLWriter $XMLWriter)
    {
        $XMLWriter->startElement('url');
        $XMLWriter->writeElement('loc', $this->getLoc());

        $this->optionalWriteElement($XMLWriter, 'lastmod', $this->getLastMod());
        $this->optionalWriteElement($XMLWriter, 'changefreq', $this->getChangeFreq());
        $this->optionalWriteElement($XMLWriter, 'priority', $this->getPriority());

        foreach ($this->getSubElements() as $subElement) {
            $subElement->generateXML($XMLWriter);
        }

        $XMLWriter->endElement();
    }

    /**
     * Get change frequency.
     *
     * @return null|string
     */
    public function getChangeFreq()
    {
        return $this->changeFreq;
    }

    /**
     * Get last modification time.
     *
     * @return null|string
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
     * Url priority.
     *
     * @return null|string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Array of sub-elements.
     *
     * @return OutputContract[]
     */
    public function getSubElements()
    {
        return $this->subElements;
    }

    /**
     * Array of sub-elements that append to the collections attributes.
     *
     * @return AppendAttributeContract[]
     */
    public function getSubElementsThatAppend()
    {
        return $this->subElementsThatAppend;
    }

    /**
     * Set change frequency.
     *
     * @param  string  $changeFreq
     * @return $this
     */
    public function setChangeFreq($changeFreq)
    {
        $this->changeFreq = $changeFreq;

        return $this;
    }

    /**
     * Set last modification time.
     *
     * @param  string  $lastMod
     * @return $this
     */
    public function setLastMod($lastMod)
    {
        $this->lastMod = $lastMod;

        return $this;
    }

    /**
     * Set priority.
     *
     * @param  string  $priority
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Checks if the sub-element is going to append collection attributes.
     *
     * @param  OutputContract $subElement
     * @return boolean
     */
    protected function isSubElementGoingToAppend(OutputContract $subElement)
    {
        if (!$subElement instanceof AppendAttributeContract) {
            return false;
        }

        return !in_array(get_class($subElement), $this->subElementsThatAppend, false);
    }

    /**
     * Only write the XML element if it has a truthy value.
     *
     * @param XMLWriter $XMLWriter
     * @param string    $name
     * @param string    $value
     */
    protected function optionalWriteElement(XMLWriter $XMLWriter, $name, $value)
    {
        if ($value) {
            $XMLWriter->writeElement($name, $value);
        }
    }
}
