<?php

/**
 * Field helpers for ProcessWire
 * Allows field-building on the fly.
 * Add namespace and `use BuildsInputFields` in your class.
 *
 * @author  Mike Rockett
 * @license ISC
 */

namespace Rockett\Concerns;

use ProcessWire\Pageimage;
use Thepixeldeveloper\Sitemap\Extensions\Image;

trait SupportsImages
{
  /**
   * Generate an image tag for the current image in the loop
   *
   * @param  Pageimage $image
   * @param  Language  $language
   * @return Image
   */
  protected function newImage($image, $language = null): Image
  {
    $locImage = new Image($image->httpUrl);

    foreach (static::$imageFields as $imageMetaMethod => $imageMetaValues) {
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
   *
   * @param Url $url
   * @param Language $language
   * @return void
   */
  protected function addImages($page, $url, $language = null): void
  {
    // Loop through declared image fields and skip non image fields
    if ($this->sitemap_image_fields) {
      foreach ($this->sitemap_image_fields as $imageFieldName) {
        $page->of(false);
        $imageField = $page->$imageFieldName;

        if ($imageField) {
          foreach ($imageField as $image) {
            if ($image instanceof Pageimage) {
              $url->addExtension($this->newImage($image, $language));
            }
          }
        }
      }
    }
  }
}
