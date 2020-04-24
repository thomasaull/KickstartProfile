<?php namespace ProcessWire;

if($type) {
  if($link && $label) {
    echo Helper::renderModuleTemplate('Button', (object) [
      'label' => $label,
      'link' => $link,
      'modifier' => "Cta $modifier"
    ]);
  }
}
