<?php namespace ProcessWire;

class ApiHelper
{
  public static function noEndPoint() {
    return 'No Endpoint specified!';
  }

  public static function renderModuleTemplate($templateString, $data = []) {
    $templateStringLowerCase = strtolower($templateString);

    $t = new TemplateFile(wire('config')->paths->templates . "modules/$templateStringLowerCase/$templateString.php");
    foreach ($data as $key => $value) { $t->set($key, $value); }

    return $t->render();
  }

  public static function checkRequiredParameters($data, $params) {
    foreach ($params as $param) {
      if (!isset($data->$param)) throw new \Exception('Required parameter "' . $param .'" missing!', 400);
    }
  }

  public static function baseUrl() {
    // $site->urls->httpRoot
    return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
  }
}
