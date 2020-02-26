<?php namespace ProcessWire; require_once("{$config->paths->templates}/markup/Helper.php"); ?>

<?php
  if ($config->maintenanceFrontend === true) {
    echo "Website is in maintenance mode";
    if (!$user->isSuperuser()) exit();
  }
?>

<!DOCTYPE html>
<html lang="de" class="noJs">
<head>
  <?php echo Helper::renderModuleTemplate('Head');?>

  <?php
    if("development" === 'production') {
      $criticalCss = Helper::checkForCriticalCss($page);
      if($criticalCss) echo "<style>$criticalCss</style>";
    }
  ?>

  <link href="https://fonts.googleapis.com/css?family=Kreon:300,400,700" rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet';">
  <noscript>
    <link href="https://fonts.googleapis.com/css?family=Kreon:300,400,700" rel="stylesheet">
  </noscript>

  

  <script>
    <?php
      // Include errorTracking.js inline
      if ("development" === 'production')
        include("{$config->paths->root}http://localhost:8080/js/errorTracking.b740b3.js");

      // Uncomment if you want to test or get Error-Tracking in Development:
      // else echo file_get_contents("http://localhost:8080/js/errorTracking.b740b3.js");
    ?>
  </script>

  <script><?php include("{$config->paths->templates}/static/cssrelpreload.js"); ?></script>
  <script><?php include("{$config->paths->EmailObfuscation}/emo.min.js"); ?></script>

  <script type="text/javascript">
    // Remove noJs Class:
    document.documentElement.classList.remove('noJs')

    window.lazySizesConfig = window.lazySizesConfig || {};
    // Disable Lazysizes Auto Init:
    // window.lazySizesConfig.init = false;
  </script>

  <!-- Hide v-cloak -->
  <style>
    [v-cloak] { display: none !important; }
    .noJs .hiddenWithoutJs { display: none !important; }
  </style>

</head>

<?php
  $layoutClass = '';
  // $layoutClass = 'layoutDefault';
  // if ($page->layout) $layoutClass = 'layout' . ucwords($page->layout);

  $fontsLoadedClass = '';
  if($input->cookie('fontsLoaded') === 'true') $fontsLoadedClass = '-fontsLoaded';
  if($input->get->fontsLoaded === 'true') $fontsLoadedClass = '-fontsLoaded';
?>

<body class="<?=$page->bodyClass?> <?=$layoutClass?> <?=$fontsLoadedClass?>">
  <?=Helper::renderModuleTemplate('NoJsWarning');?>

  <?php
  if ($page->layout)
    include("{$config->paths->templates}/markup/layouts/{$page->layout}.php");
  else
    include("{$config->paths->templates}/markup/layouts/default.php");
  ?>

  <script>
    document.config = {
      publicPath: "http://localhost:8080/",
      page: {
        id: <?=$page->id?>,
        name: '<?=$page->name?>',
        title: '<?=$page->title?>'
      }
    }
    document.jsvars = <?=json_encode($config->jsvars->getArray());?>;
  </script>

</body>
</html>

<script type="text/javascript" src="http://localhost:8080/js/critical.b740b3.js" defer></script>
<script type="text/javascript" src="http://localhost:8080/js/bundle.b740b3.js" defer></script>