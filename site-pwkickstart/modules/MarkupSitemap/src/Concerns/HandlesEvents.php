<?php

/**
 * Direct concerns for building out tabs.
 *
 * @author  Mike Rockett
 * @license ISC
 */

namespace Rockett\Concerns;

use ProcessWire\HookEvent;

trait HandlesEvents
{
  /**
   * When a page is deleted (note: not trashed), then its
   * sitemap options also need to be deleted. We don't do this
   * when its trashed, just in case the page is restored later.
   *
   * @param HookEvent $event
   * @return bool
   */
  public function deletePageSitemapOptions(HookEvent $event): bool
  {
    // Get the ID of the page that was deleted
    $pageId = $event->arguments(0)->id;

    // By saving a null value by omission, we're effectively deleting
    // the sitemap options for the deleted page.
    return $this->modules->saveConfig($this, "o$pageId");
  }
}
