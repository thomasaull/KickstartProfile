<?php namespace ProcessWire;

require_once dirname(__FILE__) . "/ApiHelper.php";

class ErrorTracking
{
  public static function save($data)
  {
    $data = ApiHelper::checkAndSanitizeRequiredParameters($data, ['message|text']);
    wire('log')->save('javascript-errors', $data->message);
  }
}
