<meta charset="UTF-8">

<title><?=$title?> – <?=$websiteName?></title>

<?php if ($canonical):?>
  <link rel="canonical" href="<?= $canonical?>"/>
<?php endif; ?>

<?php if ($keywords):?>
	<meta name="keywords" content="<?=$keywords?>"/>
<?php endif; ?>

<?php if ($description):?>
	<meta name="description" content="<?=$description?>"/>
<?php endif; ?>

<meta name="robots" content="<?=$robots?>"/>

<meta property="og:locale" content="de_DE"/>
<meta property="og:type" content="website"/>
<meta property="og:title" content="<?=$title?>"/>

<?php if ($ogDescription):?>
	<meta property="og:description" content="<?=$ogDescription?>"/>
<?php endif; ?>

<meta property="og:url" content="<?=$page->httpURL?>"/>
<meta property="og:site_name" content="<?=$websiteName?>"/>

<?php if ($ogImage):?>
  <meta property="og:image" content="<?=$ogImage->httpUrl?>"/>
  <meta property="og:image:width" content="<?=$ogImage->width?>" />
  <meta property="og:image:height" content="<?=$ogImage->height?>" />
  <meta name="twitter:image" content="<?=$ogImage->httpUrl?>"/>
<?php endif; ?>

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="<?=$twitterHandle?>" />
<meta name="twitter:creator" content="<?=$twitterHandle?>" />
<meta name="twitter:title" content="<?=$title?>" />

<meta name="language" content="de"/>
<meta name="content-language" content="de"/>

<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="translucent" />

<!-- Generate Favicons here: https://realfavicongenerator.net/ -->
<!-- PASTE GENERATED FAVICONS CODE HERE -->
