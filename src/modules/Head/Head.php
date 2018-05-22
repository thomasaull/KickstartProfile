
<meta charset="UTF-8">

<title><?=$title?></title>

<?php if ($canonical):?>
  <link rel="canonical" href="<?= $canonical?>"/>
<?php endif; ?>

<?php if ($keywords):?>
	<meta name="keywords" content="<?=$keywords?>"/>
<?php endif; ?>

<?php if ($description):?>
	<meta name="description" content="<?=$description?>"/>
<?php endif; ?>

<meta name="robots" content="<?=$noIndex?> <?=$noFollow?>"/>

<meta property="og:locale" content="de_DE"/>
<meta property="og:type" content="article"/>
<meta property="og:title" content="<?=$title?>"/>

<?php if ($ogDescription):?>
	<meta property="og:description" content="<?=$ogDescription?>"/>
<?php endif; ?>

<meta property="og:url" content="<?=$page->httpURL?>"/>
<meta property="og:site_name" content="DESAG"/>

<?php if ($ogImage):?>
	<meta property="og:image" content="<?=$ogImage->httpUrl?>"/>
<?php endif; ?>

<meta name="language" content="de"/>
<meta name="content-language" content="de"/>

<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="translucent" />

<!-- Generate Favicons here: https://realfavicongenerator.net/ -->
<!-- PASTE GENERATED FAVICONS CODE HERE -->
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=Km2E207Xpy">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=Km2E207Xpy">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=Km2E207Xpy">
<link rel="manifest" href="/site.webmanifest?v=Km2E207Xpy">
<link rel="mask-icon" href="/safari-pinned-tab.svg?v=Km2E207Xpy" color="#1d3661">
<link rel="shortcut icon" href="/favicon.ico?v=Km2E207Xpy">
<meta name="msapplication-TileColor" content="#1d3661">
<meta name="theme-color" content="#1d3661">
