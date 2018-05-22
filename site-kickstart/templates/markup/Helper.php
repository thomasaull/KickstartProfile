<?php namespace ProcessWire;

class Helper
{
  public static function renderModuleTemplate($templateString, $dataFromModuleCall = []) {
    $folder = wire('config')->paths->templates . "dist/modules/$templateString";

    // clean data from module call
    self::clean($dataFromModuleCall);

    // get Defaults
    $dataDefaults = self::getDataFromFile("$folder/${templateString}Defaults.php");

    // merge data for controller – data from module call overrides defaults:
    $dataForController = (object) array_merge((array) $dataDefaults, (array) $dataFromModuleCall);

    // process data with controller
    $dataFromController = self::getDataFromFile("$folder/${templateString}Controller.php", clone $dataForController);
    // clean data from controller
    self::clean($dataFromController);

    // final merge
    $data = (object) array_merge((array) $dataForController, (array) $dataFromController);

    // don't render if render option is false:
    if (isset($data->render) && $data->render === false) return;

    $t = new TemplateFile(wire('config')->paths->templates . "dist/modules/$templateString/$templateString.php");
    foreach ($data as $key => $value) { $t->set($key, $value); }

    return $t->render();
  }

  private static function getDataFromFile($path, $data = []) {
    if (!file_exists($path)) {
      return $data;
    }

    $data = (object) $data;

    return require($path);
  }

  private static function clean($data) {
    if (!isset($data)) return;

    foreach($data as $key => $property) {
      if($property === null || $property === '') {
        // \TD::fireLog("$key is no good data");
        unset($data->$key);
      }
    }

    // objects get passed by reference, therefore no need to return anything
  }

  public static function checkForCriticalCss ($templateName) {
    $path = wire('config')->paths->templates . "dist/critical/{$templateName}_critical.min.css";

    if (!\file_exists($path)) return; // no file

    $critical = \file_get_contents($path);
    return $critical;
  }

  public static function inlineIcon ($iconName) {
    $path = wire('config')->paths->templates . "dist/icons/";
    $svg = \file_get_contents($path . $iconName . ".svg");
    return $svg;
  }

  public static function spriteIcon ($iconName, $class = 'icon') {
    return "<svg class='$class'><use xlink:href='#$iconName--sprite'></use></svg>";
  }

  public static function createModifierString($selectOptions) {
    $modifier = "";

    if(!$selectOptions) return '';

    foreach ($selectOptions as $option) {
      $modifier .= $option->value . " ";
    }

    return $modifier;
  }

  /* Includes a modifier variable if present, use like:
  /* <div class="myElement <?=ProcessWire\Helper::modifier($this)?>">
  */
  public static function modifier ($template) {
    if (isset($template->modifier)) return $template->modifier;
  }

  // muss getestet ggf. angepasst werden:
  /*public static function hasModifier($selectOptions, $modifier) {
    if($selectOptions instanceof PageArray)
    {
      if($selectOptions->has("class=$modifier"))
        return true;
    }

    if($selectOptions instanceof Page)
    {
      if($selectOptions->class == $modifier)
        return true;
    }

    return false;
  }*/

  // Source: https://css-tricks.com/snippets/php/truncate-string-by-words/
  public static function stripTextAfterWordLimit ($text, $limit, $append = ' &hellip;') {
    // Add 1 to the specified limit becuase arrays start at 0
    $limit = $limit+1;
    // Store each individual word as an array element
    // Up to the limit
    $text = explode(' ', $text, $limit);
    // Shorten the array by 1 because that final element will be the sum of all the words after the limit
    array_pop($text);
    // Implode the array for output, and append an ellipse
    $text = implode(' ', $text) . $append;
    // Return the result
    return $text;
  }

  public static function getBase64ImageFor($image) {
    $path = $image->filename;
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $imgData = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imgData);

    return $base64;
  }

  public static function addIdsToHeadings($content) {
    $content = preg_replace_callback('/<h([1-6])(.*)>([^<]+)<\/h[1-6]>/i', function($match) {
      $id = wire('sanitizer')->pageName($match[3]);
      $tag = "h" . $match[1];
      $title = $match[3];
      $headingWithId = "<$tag id='$id'>$title</$tag>";

      return $headingWithId;

    }, $content);

    return $content;
  }
}
