<?php

$page->main = "";
$page->js_init = array("default.js");

$t = new TemplateFile(wire('config')->paths->templates . 'markup/helpers/basic-page.php');
$t->set("title", $page->title);
$page->main .= $t->render();

$config->jsvars->set("name", $page->name);

$page->layout = "default";
include("./markup/index.php");