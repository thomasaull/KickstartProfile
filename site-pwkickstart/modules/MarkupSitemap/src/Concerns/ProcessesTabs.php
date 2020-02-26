<?php

/**
 * Direct concerns for processing tabs.
 *
 * @author  Mike Rockett
 * @license ISC
 */

namespace Rockett\Concerns;

use ProcessWire\HookEvent;

trait ProcessesTabs
{
  /**
   * Default page config array, used for comparison at save-time
   */
  private static $defaultPageOptions = [
    'priority' => false,
    'excludes' => [
      'images' => false,
      'page' => false,
      'children' => false,
    ],
  ];

  /**
   * Process Sitemap fields from Settings tab
   *
   * @param  HookEvent $event
   * @return void
   */
  public function processSettingsTab(HookEvent $event): void
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

    $existingOptions = $this->modules->getConfig($this, "o{$page->id}");

    if ($existingOptions === null && $pageSitemapPageOptions === static::$defaultPageOptions) {
      return;
    }

    // Save options for this page
    if (!$this->commitPageSitemapOptions($page->id, $pageSitemapPageOptions)) {
      $this->error($this->_('Something went wrong, and the sitemap options for this page could not be saved.'));
    }
  }

  /**
   * Commit page sitemap options to module config
   * when page is saved. This is a centralised storage method
   * for all page sitemap options.
   *
   * @param array $options
   * @return bool
   */
  private function commitPageSitemapOptions($pageId, $options): bool
  {
    // Save the options for this page. Previous config is completely discarded.
    return $this->modules->saveConfig($this, "o{$pageId}", [
      'priority' => $options['priority'],
      'excludes' => $options['excludes'],
    ]);
  }
}
