<?php namespace ProcessWire;

$page = wire('page');

$data = new \StdClass();
$data->title = $page->title;
$data->canonical = null;
$data->keywords = null;
$data->description = null;
$data->robots = 'index follow';
$data->ogDescription = null;
$data->ogImage = null;

return $data;
