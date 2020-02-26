<?php

/**
 * Direct concerns for building out tabs.
 *
 * @author  Mike Rockett
 * @license ISC
 */

namespace Rockett\Concerns;

use ProcessWire\HookEvent;
use ProcessWire\Inputfield;

trait ConfiguresTabs
{
  /**
   * Add sitemap fields to the Settings tab.
   * Responds to ProcessPageEdit::buildFormSettings hook
   *
   * @param  HookEvent $event
   * @return void
   */
  public function setupSettingsTab(HookEvent $event): void
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
}
