<?php namespace ProcessWire;

class Critical
{
  public static function generateCriticalRoutes($data) {
    $routes = [];
    $home = wire('pages')->get(1);
    $additionalHiddenPages = []; // pageIds of additional (hidden) pages

    self::addPageToRoutes($routes, $home);
    self::checkChildren($routes, $home);

    foreach ($additionalHiddenPages as $id) {
      $page = wire('pages')->get($id);
      if($page->id) self::addPageToRoutes($routes, $page);
    }

    return $routes;
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
}
