<?php namespace ProcessWire;

require_once "{$config->paths->templates}/api/ApiHelper.php";
require_once "{$config->paths->templates}/api/Test.php";
require_once "{$config->paths->templates}/markup/Helper.php";

// $data = Test::getSomeData();
// $page->main .= ApiHelper::renderModuleTemplate('Blupp', $data);

$page->main .= ApiHelper::renderModuleTemplate('ContactForm');

// $page->main .= ApiHelper::renderModuleTemplate('Bla');


// foreach($page->contentbuilder as $content) {
//   $page->main .= $content->render();
// }

include("index.php");
