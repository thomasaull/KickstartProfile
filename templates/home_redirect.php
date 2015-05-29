<?php 

$otherPage = $pages->get('/homepage');
$otherPage->you_be_homepage = true; 
echo $otherPage->render();