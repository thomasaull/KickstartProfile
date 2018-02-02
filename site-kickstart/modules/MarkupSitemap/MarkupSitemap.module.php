<?php

/**
 * Sitemap for ProcessWire
 *
 * Module class
 *
 * @author Mike Rockett <github@rockett.pw>
 * @copyright 2017
 * @license ISC
 */

// Require the classloader
require_once __DIR__ . '/ClassLoader.php';

use Rockett\Traits\BuilderTrait as BuildsSitemap;
use Rockett\Traits\DebugTrait as Debugs;
use Rockett\Traits\FieldsTrait as BuildsFields;

class MarkupSitemap extends WireData implements Module
{
    use BuildsFields, BuildsSitemap, Debugs;

    /**
     * Image fields: each field is mapped to the relavent
     * function for the Image sub-element
     */
    const IMAGE_FIELDS = [
        'Caption' => 'description',
        'License' => 'license',
        'Title' => 'title',
        'GeoLocation' => 'geo|location|geolocation',
    ];

    /**
     * Sitemap URI
     */
    const SITEMAP_URI = '/sitemap.xml';

    /**
     * Current request URI
     *
     * @var string
     */
    protected $requestUri = '';

    /**
     * Page selector
     *
     * @reserved
     * @var string
     */
    protected $selector = '';

    /**
     * Module installer
     * Requires ProcessWire 2.8.16+/3.0.16+ (saveConfig; getConfig)
     * @throws WireException
     */
    public function ___install()
    {
        $processWireVersion = $this->config->version;
        $applicableMajorMinor = ProcessWire::versionMajor === 2 ? '2.8' : '3.0';
        if (version_compare($processWireVersion, "{$applicableMajorMinor}.16") < 0) {
            throw new WireException("Requires ProcessWire {$applicableMajorMinor}.16+ to run.");
        }
    }

    /**
     * Class constructor
     * Get and assign the current request URI
     */
    public function __construct()
    {
        // Set the request URI
        $this->requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
    }

    /**
     * Commit page sitemap options to module config
     * when page is saved. This is a centralised storage method
     * for all page sitemap options.
     * @param array $options
     */
    public function commitPageSitemapOptions($pageId, $options)
    {
        // Save the options for this page. Previous
        // config is completely discarded.
        return $this->modules->saveConfig($this, "o{$pageId}", [
            'priority' => $options['priority'],
            'excludes' => $options['excludes'],
        ]);
    }

    /**
     * When a page is deleted (note: not trashed), then its
     * sitemap options also need to be deleted. We don't do this
     * when its trashed, just in case the page is restored later.
     * @param  HookEvent $event
     * @return void
     */
    public function deletePageSitemapOptions(HookEvent $event)
    {
        // Get the ID of the page that was deleted
        $pageId = $event->arguments(0)->id;

        // By saving a null value by omission, we're effectively deleting
        // the sitemap options for the deleted page.
        return $this->modules->saveConfig($this, "o{$pageId}");
    }

    /**
     * Return a POSTed value or its default if not available
     * @var    string  $valueKey
     * @var    mixed   $default
     * @return mixed
     */
    public function getPostedValue($valueKey, $default = false)
    {
        return $this->input->post->$valueKey ?: $default;
    }

    /**
     * Initiate the module
     *
     * @return void
     */
    public function init()
    {
        // If the request is valid (/sitemap.xml)...
        if ($this->isValidRequest()) {
            // Add the relevant page hooks for multi-language support
            // as these are not bootstrapped at the 404 event (for some reason...)
            if ($this->siteUsesLanguageSupportPageNames()) {
                foreach (['localHttpUrl', 'localName'] as $pageHook) {
                    $pageHookFunction = 'hookPage' . ucfirst($pageHook);
                    $this->addHook("Page::{$pageHook}", null, function ($event) use ($pageHookFunction) {
                        $this->modules->LanguageSupportPageNames->{$pageHookFunction}($event);
                    });
                }
            }
            // Add the hook to process and render the sitemap.
            $this->addHookBefore('ProcessPageView::pageNotFound', $this, 'render');
        }

        // Add hook to render Sitemap fields on the Settings tab of each page
        if ($this->user->hasPermission('page-edit')) {
            $this->addHookAfter('ProcessPageEdit::buildFormSettings', $this, 'setupSettingsTab');
            $this->addHookAfter('ProcessPageEdit::processInput', $this, 'processSettingsTab');
        }
        // If the user can delete pages, then we need to hook into delete
        // events to remove sitemap options for deleted pages
        if ($this->user->hasPermission('page-delete')) {
            $this->addHookAfter('Pages::deleted', $this, 'deletePageSitemapOptions');
        }
    }

    /**
     * Process Sitemap fields from Settings tab
     * @param  HookEvent $event
     * @return void
     */
    public function processSettingsTab(HookEvent $event)
    {
        // Prevent recursion
        if (($level = $event->arguments(1)) > 0) {
            return;
        }

        // Get the current page and stop if we're working
        // with an admin or trashed page.
        $page = $event->object->getPage();
        if ($page->matches("has_parent={$this->config->adminRootPageID}|{$this->config->trashPageID}")) {
            return;
        }

        // Build the options instance for this page.
        // If saving the home page, excludes.page and excludes.children
        // are saved as false. The data is kept for the purposes
        // of code simplification, and has no effect on
        // how things work.
        $pageSitemapPageOptions = [
            'priority' => $this->getPostedValue('sitemap_priority'),
            'excludes' => [
                'images' => $this->getPostedValue('sitemap_exclude_images'),
                'page' => $this->getPostedValue('sitemap_exclude_page'),
                'children' => $this->getPostedValue('sitemap_exclude_children'),
            ],
        ];

        // Save options for this page
        if (!$this->commitPageSitemapOptions($page->id, $pageSitemapPageOptions)) {
            $this->error($this->_('Something went wrong, and the sitemap options for this page could not be saved.'));
        }
    }

    /**
     * Initiate the sitemap render by getting the root URI (giving
     * consideration to multi-site setups) and passing it to the
     * first/parent recursive render-method (addPages).
     *
     * MarkupCache is used to cache the entire sitemap, and the cache
     * is destroyed when settings are saved and, if set up, a page is saved.
     *
     * @param HookEvent $event
     */
    public function render(HookEvent $event)
    {
        // Get the initial root URI.
        $rootPage = $this->getRootPageUri();

        // If multi-site is present and active, prepend the subdomain prefix.
        if ($this->modules->isInstalled('MultiSite')) {
            $multiSite = $this->modules->get('MultiSite');
            if ($multiSite->subdomain) {
                $rootPage = "/{$multiSite->subdomain}{$rootPage}";
            }
        }

        // Make sure that the root page exists.
        if (!$this->pages->get($rootPage) instanceof NullPage) {
            // Check for cached sitemap or regenerate if it doesn't exist
            // $rootPageName = $this->sanitizer->pageName($rootPage);
            $markupCache = $this->modules->MarkupCache;
            if ((!$output = $markupCache->get('MarkupSitemap', 3600)) || $this->config->debug) {
                $output = $this->buildNewSitemap($rootPage);
                $markupCache->save($output);
                header('X-SitemapRetrievedFromCache: no');
            } else {
                header('X-SitemapRetrievedFromCache: yes');
            }
            header('Content-Type: application/xml', true, 200);
            $event->return = $output;

            // Prevent further hooks. This stops
            // SystemNotifications from displaying a 404 event
            // when /sitemap.xml is requested. Additionall,
            // it prevents further modification to the sitemap.
            $event->replace = true;
            $event->cancelHooks = true;
        }
    }

    /**
     * Add sitemap fields to the Settings tab.
     * Responds to ProcessPageEdit::buildFormSettings hook
     * @param  HookEvent $event
     * @return void
     */
    public function setupSettingsTab(HookEvent $event)
    {
        // Get the current page
        $page = $event->object->getPage();

        // We only need to proceed with this process if the current page's
        // template has been assigned as configurable in the module's configuration.
        if ($this->sitemap_include_templates !== null
            && in_array($page->template->name, $this->sitemap_include_templates)
            && !in_array($page->template->name, $this->sitemap_exclude_templates)
        ) {
            // Get the settings tab inputfields
            $inputFields = $event->return;

            // Get the saved options for this page
            $pageOptions = $this->modules->getConfig($this, "o{$page->id}");

            // Sitemap fieldset
            $sitemapFieldset = $this->buildInputField('Fieldset', [
                'label' => 'Sitemap',
                'icon' => 'sitemap',
                'collapsed' => Inputfield::collapsedBlank,
            ]);

            // Add priority field
            $sitemapFieldset->append($this->buildInputField('Text', [
                'name' => 'sitemap_priority',
                'label' => $this->_('Page Priority'),
                'description' => $this->_('Set this page’s priority on a scale of 0.0 to 1.0.'),
                'notes' => $this->_('This field is optional, and the priority will only be included if it is set here.'),
                'columnWidth' => '50%',
                'pattern' => "(0(\.\d+)?|1(\.0+)?)",
                'value' => $pageOptions['priority'],
            ]));

            // Add exclude_images field
            $sitemapFieldset->append($this->buildInputField('Checkbox', [
                'name' => 'sitemap_exclude_images',
                'label' => $this->_('Exclude Images'),
                'label2' => $this->_('Do not add images to the sitemap for this page’s entry'),
                'description' => $this->_('By default, all image fields for this page will be included in the sitemap. If you don’t want this to happen, you can exclude such inclusion for this page by checking the box below.'),
                'columnWidth' => '50%',
                'autocheck' => true,
                'value' => $pageOptions['excludes']['images'],
            ]));

            // These fields may only be added to non-root pages.
            if ($page->id !== 1) {
                // Add exclude_page field
                $sitemapFieldset->append($this->buildInputField('Checkbox', [
                    'name' => 'sitemap_exclude_page',
                    'label' => $this->_('Exclude Page'),
                    'label2' => $this->_('Do not include this page in the sitemap'),
                    'description' => $this->_('If you’d like to skip the inclusion of this page (not considering its children, if any) from the sitemap, you can check the box below.'),
                    'columnWidth' => '50%',
                    'autocheck' => true,
                    'value' => $pageOptions['excludes']['page'],
                ]));

                // Add exclude_children field
                $sitemapFieldset->append($this->buildInputField('Checkbox', [
                    'name' => 'sitemap_exclude_children',
                    'label' => $this->_('Exclude Children'),
                    'label2' => $this->_('Do not include this page’s children (if any) in sitemap.xml'),
                    'description' => $this->_('If you’d like to skip the inclusion of this page’s children (if any, and not considering the page itself) from the sitemap, you can check the box below.'),
                    'columnWidth' => '50%',
                    'autocheck' => true,
                    'value' => $pageOptions['excludes']['children'],
                ]));
            }

            // Add the new fieldset to the Settings tab (fieldset)
            $inputFields->insertBefore($sitemapFieldset, $inputFields->find('name=status')->first());
        }
    }

    /**
     * Correctly format the priority float to one decimal
     * @param  float
     * @return string
     */
    protected function formatPriorityFloat($priority)
    {
        return sprintf('%.1F', (float) $priority);
    }

    /**
     * Get the root page URI
     * @return string
     */
    protected function getRootPageUri()
    {
        return (string) str_ireplace(
            trim($this->config->urls->root, '/'),
            '',
            $this->sanitizer->path(dirname($this->requestUri))
        );
    }

    /**
     * Determine if the request is valud
     * @return boolean
     */
    protected function isValidRequest()
    {
        $valid = (bool) (
            $this->requestUri !== null &&
            strlen($this->requestUri) - strlen(self::SITEMAP_URI) === strrpos($this->requestUri, self::SITEMAP_URI)
        );

        return $valid;
    }

    /**
     * Check if the language is not default and that the
     * page is not available/statused in the default language.
     * @param  string $language
     * @param  Page   $page
     * @return bool
     */
    protected function pageLanguageInvalid($language, $page)
    {
        return (!$language->isDefault() && !$page->{"status{$language->id}"});
    }

    /**
     * Determine if the site uses the LanguageSupportPageNames module.
     * @return bool
     */
    protected function siteUsesLanguageSupportPageNames()
    {
        return $this->modules->isInstalled('LanguageSupportPageNames');
    }
}
