<?php namespace ProcessWire;

require_once "{$config->paths->templates}/api/Example.php";
require_once "{$config->paths->templates}/markup/Helper.php";

// $page->main .= Helper::renderModuleTemplate('ContactForm');

// Option 1: Putting Data directly
$page->main .= Helper::renderModuleTemplate('TestModule', (object) ['name' => 'Thomas', 'age' => 29, 'location' => 'Würzburg']);

// Option 2: Using a controller (optional – does overwrite this directly put data)
$page->main .= Helper::renderModuleTemplate('TestModuleWithController', (object) ['name' => 'Thomas', 'age' => 29, 'location' => 'Würzburg']);

// Option 3: Use the API by acessing functions directly
$page->main .= Helper::renderModuleTemplate('TestModule', Example::getAllUsers());

// $page->main .= Helper::renderModuleTemplate('Kitchensink');

// foreach($page->contentbuilder as $content) {
//   $page->main .= $content->render();
// }
$page->main .= Helper::renderModuleTemplate('Contentbuilder');

include("dist/index.php");
