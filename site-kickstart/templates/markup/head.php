
<meta charset="UTF-8">
<title><?= $page->seo_title ? $page->seo_title : $page->title ?></title>

<?php if ($page->seo_canonical) { ?>
	<link rel="canonical" href="<?= $page->seo_canonical ?>" />
<?php } ?>

<?php if ($page->seo_keywords) { ?>
	<meta name="keywords" content="<?= $page->seo_keywords ?>" />
<?php } ?>

<?php if ($page->seo_description) { ?>
	<meta name="description" content="<?= $page->seo_description ?>" />
<?php } ?>

<meta name="robots" content="<?= $page->seo_noindex ? "noindex" : "index" ?>,<?= $page->seo_nofollow ? "nofollow" : "follow" ?>" />

<meta property="og:locale" content="de_DE" />
<meta property="og:type" content="article" />
<meta property="og:title" content="<?= $page->seo_title ? $page->seo_title : $page->title ?>" />

<?php if ($page->seo_og_description != "") { ?>
	<meta property="og:description" content="<?= $page->seo_og_description ?>" />
<?php } elseif ($page->seo_description != "") { ?>
	<meta property="og:description" content="<?= $page->seo_description ?>" />
<?php } ?>

<meta property="og:url" content="<?= $page->httpURL ?>" />
<meta property="og:site_name" content="rmsdruck.de" />

<?php if ($page->seo_og_image != "") { ?>
	<meta property="og:image" content="<?= $page->seo_og_image->url ?>" />
<?php } ?>

<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="translucent" />

<link rel="apple-touch-icon" sizes="180x180" href="<?= $config->urls->templates ?>favicons/apple-touch-icon.png">

<link rel="icon" type="image/png" href="<?= $config->urls->templates ?>favicons/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="<?= $config->urls->templates ?>favicons/favicon-16x16.png" sizes="16x16">

<link rel="manifest" href="<?= $config->urls->templates ?>favicons/manifest.json">
<link rel="mask-icon" href="<?= $config->urls->templates ?>favicons/safari-pinned-tab.svg" color="#59b99f">

<meta name="theme-color" content="#299676">