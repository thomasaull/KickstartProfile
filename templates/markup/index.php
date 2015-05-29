<!DOCTYPE html>
<html lang="de">
<head>	
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    
	<title>### Titel eintragen ###</title>
	
	<!-- Meta Tags -->
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta name="author" content="Thomas Aull - http://www.thomas-aull.de" />
	<meta name="language" content="de" />
	<meta name="content-language" content="de" />
	<meta name="robots" content="index,follow" />

	<!-- Favicon -->
	<link rel="shortcut icon" href="favicon.ico" />
	
	<!-- CSS -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?=$config->urls->templates?>css/screen.css" />
    
</head>
<body>	



<?php	
if ($page->layout)
	include("./markup/layouts/{$page->layout}.php");
else
  	include("./markup/layouts/default.php");
?>


	
<!-- GLOBAL JS-VARIABLES -->
<?php		
	$config->jsvars->setArray(array(
		'url' => array(
			'templates' => $config->urls->templates,
			'root' => $config->urls->root,
		),
	));
?>

<script type="text/javascript">
	var config = <?=json_encode($config->jsvars->getArray());?>;
</script>

<!-- INLCUDE GLOBAL JAVASCRIPTS -->
<script type="text/javascript" src="<?= $config->urls->templates?>js/libs/MooTools-Core-1.5.1.js"></script>
<script type="text/javascript" src="<?= $config->urls->templates?>js/libs/MooTools-More-1.5.1.js"></script>
<script type="text/javascript" src="<?= $config->urls->templates?>js/libs/Asset.javascripts.js"></script>

<script type="text/javascript" src="<?= $config->urls->templates?>js/default.js"></script>

<!-- JS INIT FOR SPECIFIC PAGE -->
<?php 
if($page->js_init):
	foreach ($page->js_init as $init): ?>
		<script type="text/javascript" src="<?= $config->urls->templates?>js/init/<?=$init?>"></script>
	<?php endforeach;
endif; 
?>

</body>
</html>