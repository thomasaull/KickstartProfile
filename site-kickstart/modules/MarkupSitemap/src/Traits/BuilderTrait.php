<?php

/**
 * Sitemap builder trait. Contains all methods needed for
 * putting a new sitemap together.
 *
 * @author  Mike Rockett
 * @license ISC
 */

namespace Rockett\Traits;

use Rockett\Sitemap\Elements\Url;
use Rockett\Sitemap\Elements\Urlset;
use Rockett\Sitemap\Output;
use Rockett\Sitemap\SubElements\Image;
use Rockett\Sitemap\SubElements\Link;

trait BuilderTrait
{
    /**
     * Current UrlSet
     *
     * @var Urlset
     */
    protected $urlSet;

    /**
     * Add alternative languges, including current.
     * @param Page $page
     * @param Url  $url
     */
    protected function addAltLanguages($page, $url)
    {
        foreach ($this->languages as $altLanguage) {
            if ($this->pageLanguageInvalid($altLanguage, $page)) {
                continue;
            }
            if ($altLanguage->isDefault()
                && $this->pages->get(1)->name === 'home'
                && !$this->modules->LanguageSupportPageNames->useHomeSegment
                && !empty($this->sitemap_default_iso)) {
                $languageIsoName = $this->sitemap_default_iso;
            } else {
                $languageIsoName = $this->pages->get(1)->localName($altLanguage);
            }
            $url->addSubElement(new Link($languageIsoName, $page->localHttpUrl($altLanguage)));
        }
    }

    /**
     * Generate an image tag for the current image in the loop
     * @param  Pageimage $image
     * @param  Language  $language
     * @return Image
     */
    protected function addImage($image, $language = null)
    {
        $locImage = new Image($image->httpUrl);
        foreach (self::IMAGE_FIELDS as $imageMetaMethod => $imageMetaValues) {
            foreach (explode('|', $imageMetaValues) as $imageMetaValue) {
                if ($language != null && !$language->isDefault() && $image->{"$imageMetaValue{$language->id}"}) {
                    $imageMetaValue .= $language->id;
                }
                if ($image->$imageMetaValue) {
                    if ($imageMetaMethod === 'License') {
                        // Skip invalid licence URLs
                        if (!filter_var($image->$imageMetaValue, FILTER_VALIDATE_URL)) {
                            continue;
                        }
                    }
                    $locImage->{"set{$imageMetaMethod}"}($image->$imageMetaValue);
                }
            }
        }

        return $locImage;
    }

    /**
     * Add images to the current Url
     * @param Url      $url
     * @param Language $language
     */
    protected function addImages($page, $url, $language = null)
    {
        // Loop through declared image fields and skip non image fields
        if ($this->sitemap_image_fields) {
            foreach ($this->sitemap_image_fields as $imageFieldName) {
                $page->of(false);
                $imageField = $page->$imageFieldName;
                if ($imageField) {
                    foreach ($imageField as $image) {
                        if ($image instanceof Pageimage || $image instanceof \ProcessWire\Pageimage) {
                            $url->addSubElement($this->addImage($image, $language));
                        }
                    }
                }
            }
        }
    }

    /**
     * Determine if a page can be included in the sitemap
     * @param  $page
     * @param  $options
     * @return bool
     */
    public function pageIsIncludible($page, $options)
    {
        // If it's the home page, it's always includible.
        if ($page->id === 1) {
            return true;
        }

        // If the page's template is excluded from accessing Sitemap,
        // then it's not includible.
        if (in_array($page->template->name, $this->sitemap_exclude_templates)) {
            return false;
        }

        // Otherwise, check to see if the page itself has been excluded
        // via Sitemap options.
        return !$options['excludes']['page'];
    }

    /**
     * Recursively add pages in each language with
     * alternate language and image sub-elements.
     * @param  $page
     */
    protected function addPages($page)
    {
        // Get the saved options for this page
        $pageSitemapOptions = $this->modules->getConfig($this, "o{$page->id}");

        // If the template that this page belongs to is not using sitemap options
        // (per the module's current configuration), then we need to revert the keys
        // in $pageSitemapOptions to their defaults so as to prevent their
        // saved options from being used in this cycle.
        if ($this->sitemap_include_templates !== null
            && !in_array($page->template->name, $this->sitemap_include_templates)
            && is_array($pageSitemapOptions)) {
            array_walk_recursive($pageSitemapOptions, function (&$value) {
                $value = false;
            });
        }

        // If the page is viewable and not excluded or we’re working with the root page,
        // begin generating the sitemap by adding pages recursively. (Root is always added.)
        if ($page->viewable() && $this->pageIsIncludible($page, $pageSitemapOptions)) {
            // If language support is enabled, then we need to loop through each language
            // to generate <loc> for each language with all alternates, including the
            // current language. Then add image references with multi-language support.
            if ($this->siteUsesLanguageSupportPageNames()) {
                foreach ($this->languages as $language) {
                    if ($this->pageLanguageInvalid($language, $page) || !$page->viewable($language)) {
                        continue;
                    }
                    $url = new Url($page->localHttpUrl($language));
                    $url->setLastMod(date('c', $page->modified));
                    $this->addAltLanguages($page, $url);
                    if ($pageSitemapOptions['priority']) {
                        $url->setPriority($this->formatPriorityFloat($pageSitemapOptions['priority']));
                    }
                    if (!$pageSitemapOptions['excludes']['images']) {
                        $this->addImages($page, $url, $language);
                    }
                    $this->urlSet->addUrl($url);
                }
            } else {
                // If multi-language support is not enabled, then we only need to
                // add the current URL to a new <loc>, along with images.
                $url = new Url($page->httpUrl);
                $url->setLastMod(date('c', $page->modified));
                if ($pageSitemapOptions['priority']) {
                    $url->setPriority($this->formatPriorityFloat($pageSitemapOptions['priority']));
                }
                if (!$pageSitemapOptions['excludes']['images']) {
                    $this->addImages($page, $url);
                }
                $this->urlSet->addUrl($url);
            }
        }

        // Check for children, if allowed
        // * Recursive process
        if (!$pageSitemapOptions['excludes']['children']) {
            $children = $page->children($this->selector);
            if (count($children)) {
                foreach ($children as $child) {
                    $this->addPages($child);
                }
            }
        }
    }

    /**
     * Build a new sitemap (called when cache doesn't have one or we're debugging)
     * @return string
     */
    protected function buildNewSitemap($rootPage)
    {
        $this->urlSet = new Urlset();
        $this->addPages($this->pages->get($rootPage));
        $sitemapOutput = new Output();
        if ($this->sitemap_stylesheet) {
            $sitemapOutput->addProcessingInstruction(
                'xml-stylesheet',
                'type="text/xsl" href="' . $this->getStylesheetUrl() . '"'
            );
        }

        return $sitemapOutput->setIndented(true)->getOutput($this->urlSet);
    }

    /**
     * If using a stylesheet, return its absolute Url\
     * @return string
     */
    protected function getStylesheetUrl()
    {
        if ($this->sitemap_stylesheet_custom
            && filter_var($this->sitemap_stylesheet_custom, FILTER_VALIDATE_URL)) {
            return $this->sitemap_stylesheet_custom;
        }

        return $stylesheetPath = $this->urls->httpSiteModules . 'MarkupSitemap/assets/sitemap-stylesheet.xsl';
    }
}
