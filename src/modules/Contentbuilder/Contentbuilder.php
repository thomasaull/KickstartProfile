<?php namespace ProcessWire;

foreach (wire('page')->contentbuilder as $item) {

  if ($item->type === 'text') {
    echo Helper::renderModuleTemplate('Text', (object) [
      'text' => $item->text
    ]);
  }

  if ($item->type === 'image') {
    echo Helper::renderModuleTemplate('Image', (object) [
      'image' => $item->images->first()
    ]);
  }

  if ($item->type === 'gallery') {
    echo Helper::renderModuleTemplate('Gallery', (object) [
      'images' => $item->images,
      'text' => $item->text
    ]);
  }

  if ($item->type === 'download') {
    echo Helper::renderModuleTemplate('Download', (object) [
      'files' => $item->files,
      'text' => $item->text
    ]);
  }

  if ($item->type === 'contactForm') {
    echo Helper::renderModuleTemplate('ContactForm', (object) [
      'text' => $item->text
    ]);
  }
}
