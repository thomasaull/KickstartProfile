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
 * Class Output
 *
 * @package Rockett\Sitemap
 */
class Output
{
    /**
     * What string is used for indentation.
     *
     * @var string
     */
    protected $indentString = '    ';

    /**
     * Is the output indented.
     *
     * @var boolean
     */
    protected $indented = true;

    /**
     * Processing instructions.
     *
     * @var array
     */
    protected $processingInstructions = [];

    /**
     * Adds a processing instruction.
     *
     * @param  string  $target
     * @param  string  $content
     * @return $this
     */
    public function addProcessingInstruction($target, $content)
    {
        $this->processingInstructions[$target] = $content;

        return $this;
    }

    /**
     * String used for indentation.
     *
     * @return string
     */
    public function getIndentString()
    {
        return $this->indentString;
    }

    /**
     * Renders the Sitemap as an XML string.
     *
     * @param  OutputContract $collection
     * @return string
     */
    public function getOutput(OutputContract $collection)
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent($this->isIndented());
        $xmlWriter->startDocument('1.0', 'UTF-8');

        foreach ($this->processingInstructions as $target => $content) {
            $xmlWriter->writePi($target, $content);
        }

        $xmlWriter->setIndentString($this->getIndentString());

        $collection->generateXML($xmlWriter);

        return trim($xmlWriter->flush(true));
    }

    /**
     * Output indented?
     *
     * @return boolean
     */
    public function isIndented()
    {
        return $this->indented;
    }

    /**
     * Set the string used for indentation.
     *
     * @param  string  $indentString
     * @return $this
     */
    public function setIndentString($indentString)
    {
        $this->indentString = $indentString;

        return $this;
    }

    /**
     * Indent the output?
     *
     * @param  boolean $indented
     * @return $this
     */
    public function setIndented($indented)
    {
        $this->indented = $indented;

        return $this;
    }
}
