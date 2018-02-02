<?php namespace ProcessWire;

class Test
{
  public static function putSomeData($data) {
    $data = ApiHelper::checkAndSanitizeRequiredParameters($data, ['name|text', 'email|email']);

    // return 'Api Endpoint: ' . date(DATE_ISO8601);
    $data = new \StdClass();
    $data->user = wire('user')->name;

    return $data;
  }

  public static function getSomeData() {
    $data = new \StdClass();
    $data->name = wire('user')->name;
    $data->age = 50;
    $data->location = 'Greetings from the API';

    return $data;
  }
}
