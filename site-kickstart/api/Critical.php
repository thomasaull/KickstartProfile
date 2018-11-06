<?php namespace ProcessWire;

class Critical
{
  private static $fallbackTemplates = ['contentbuilder'];
  private static $additionalHiddenPages = [1068, 1060, 1064];

  public static function generateCriticalRoutes($data) {
    $routes = [];
    $templates = [];
    $home = wire('pages')->get(1);

    self::addPageToRoutes($routes, $home);
    self::checkChildren($routes, $home);

    foreach (self::$additionalHiddenPages as $id) {
      $page = wire('pages')->get($id);
      if($page->id) self::addPageToRoutes($routes, $page);
    }

    self::addFallbackTemplates($templates);

    $response = new \StdClass();
    $response->routes = $routes;
    $response->templates = $templates;

    return $response;
  }

  private static function checkChildren(&$routes, $page) {
    if(!$page->children()->count) return;

    foreach ($page->children as $child) {
      self::addPageToRoutes($routes, $child);
      self::checkChildren($routes, $child);
    }
  }

  private static function addPageToRoutes (&$routes, $page) {
    $item = new \StdClass();
    $item->url = $page->url;
    $item->id = $page->id;

    array_push($routes, $item);
  }

  private static function addFallbackTemplates(&$templates) {
    foreach(self::$fallbackTemplates as $template) {

      $firstPageWithTemplate = wire('pages')->get("template=$template");
      if($firstPageWithTemplate->id) {
        $item = new \StdClass();
        $item->url = $firstPageWithTemplate->url;
        $item->id = $firstPageWithTemplate->template->id;

        array_push($templates, $item);
      }
    }
  }
}
