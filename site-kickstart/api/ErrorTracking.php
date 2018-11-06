<?php namespace ProcessWire;

class ErrorTracking
{
  public static function save($data)
  {
    $data = RestApiHelper::checkAndSanitizeRequiredParameters($data, ['message|text']);
    wire('log')->save('javascript-errors', $data->message);
  }
}
