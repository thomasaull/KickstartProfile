<?php namespace ProcessWire;

// Set Timezone
date_default_timezone_set('Europe/Berlin');
setlocale(LC_TIME, "de_DE.utf8");

class Helper
{
  public static function renderModuleTemplate($templateString, $data = []) {
    $folder = wire('config')->paths->templates . "dist/modules/$templateString";

    // get and merge data from controller
    $data = (object) array_merge((array) $data, (array) self::getControllerData("$folder/${templateString}Controller.php"));

    $t = new TemplateFile(wire('config')->paths->templates . "dist/modules/$templateString/$templateString.php");
    foreach ($data as $key => $value) { $t->set($key, $value); }

    return $t->render();
  }
  
  private static function getControllerData($path) {
    if (!file_exists($path)) {
      return;
    }

    $data = require_once($path);
    
    return $data;
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
}

?>
