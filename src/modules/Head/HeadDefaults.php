<?php namespace ProcessWire;

$page = wire('page');
$globalSettingsPage = wire('pages')->get(1029);

$data = new \StdClass();
$data->websiteName =  $globalSettingsPage->seoTitle;
$data->title = $page->title;
$data->canonical = null;
$data->keywords = $globalSettingsPage->seo->seoKeywords;
$data->description = $globalSettingsPage->seo->seoDescription;
$data->robots = 'index follow';
$data->ogDescription = $globalSettingsPage->seo->seoDescription;

$data->ogImage = null;
if($globalSettingsPage->seo->seoOgImage) {
  $data->ogImage = $globalSettingsPage->seo->seoOgImage;
}

$data->twitterHandle = $globalSettingsPage->seo->seoTwitterHandle;

return $data;
