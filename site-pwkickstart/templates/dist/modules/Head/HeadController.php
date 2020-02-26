<?php namespace ProcessWire;

$page = wire('page');

$data->title = $page->seo->seoTitle;
$data->canonical = $page->seo->seoCanonical;
$data->keywords = $page->seo->seoKeywords;
$data->description = $page->seo->seoDescription;

$noIndex = $page->seo->seoNoIndex ? 'noindex' : 'index';
$noFollow = $page->seo->seoNoFollow ? 'nofollow' : 'follow';
$data->robots = "$noIndex $noFollow";

$data->ogDescription = $page->seo->seoOgDescription ? $page->seo->seoOgDescription : $data->description;
$data->ogImage = $page->seo->seoOgImage;

return $data;
