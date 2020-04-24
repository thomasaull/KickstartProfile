<?php namespace ProcessWire;

require_once("{$config->paths->templates}/markup/Helper.php");

$useCriticalCss = false;

function getLayoutPath() {
  $userCanViewWhileInFrontendMaintenance = wire('user')->isSuperuser() || wire('user')->hasRole('redakteur');
  $templatesPath = wire('config')->paths->templates;

  if(wire('config')->maintenanceFrontend === true && !$userCanViewWhileInFrontendMaintenance) {
    return "$templatesPath/markup/layouts/maintenanceFrontend.php";
  }

  if (wire('page')->layout) {
    return "$templatesPath/markup/layouts/{$page->layout}.php";
  } else {
    return "$templatesPath/markup/layouts/default.php";
  }
}

?>

<!DOCTYPE html>
<html lang="de" class="is-noJs">
<head>
  <?php echo Helper::renderModuleTemplate('Head');?>

  <?php
    if($useCriticalCss === true && "<%= htmlWebpackPlugin.options.env %>" === 'production') {
      $criticalCss = Helper::checkForCriticalCss($page);
      if($criticalCss) echo "<style>$criticalCss</style>";
    }
  ?>

  <?php if($useCriticalCss):?>
  <?php // Source for Loading CSS async technique: https://www.filamentgroup.com/lab/load-css-simpler/ ?>

  <% for (var css in htmlWebpackPlugin.files.css) { %>
    <link rel="stylesheet" href="<%= htmlWebpackPlugin.files.css[css] %>" media="print" onload="this.media='all';window.dispatchEvent(new Event('CSSLoaded'))">
    <noscript>
      <link href="<%= htmlWebpackPlugin.files.css[css] %>" rel="stylesheet">
    </noscript>
  <% } %>
  <?php else:?>
    <% for (var css in htmlWebpackPlugin.files.css) { %>
      <link href="<%= htmlWebpackPlugin.files.css[css] %>" rel="stylesheet">
    <% } %>
  <?php endif?>

  <script>
    <?php
      // Include errorTracking.js inline
      if ("<%= htmlWebpackPlugin.options.env %>" === 'production')
        include("{$config->paths->root}<%= htmlWebpackPlugin.files.js[0] %>");

      // Uncomment if you want to test or get Error-Tracking in Development:
      // else echo file_get_contents("<%= htmlWebpackPlugin.files.js[0] %>");
    ?>
  </script>

  <script><?php include("{$config->paths->EmailObfuscation}/emo.min.js"); ?></script>

  <script type="text/javascript">
    // Remove is-noJs Class:
    document.documentElement.classList.remove('is-noJs')

    window.lazySizesConfig = window.lazySizesConfig || {};
    // Disable Lazysizes Auto Init:
    // window.lazySizesConfig.init = false;
  </script>

  <!-- Hide v-cloak -->
  <style>
    [v-cloak] { display: none !important; }
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


  <div id="vue" class="layoutContainer">
    <?php
    $layoutPath = getLayoutPath();
    include($layoutPath);
    ?>
  </div>

  <script>
    document.config = {
      publicPath: "<%= htmlWebpackPlugin.options.publicPath %>",
      page: {
        id: <?=$page->id?>,
        name: '<?=$page->name?>',
        title: '<?=$page->title?>'
      }
    }
    document.jsVariables = <?=json_encode($config->jsVariables->getArray());?>;
  </script>

</body>
</html>


<?php
// 0: Error Tracking
// 1: Critical
// 2: Bundle
// => see webpack.config.js
?>
<script type="text/javascript" src="<%= htmlWebpackPlugin.files.js[1] %>" defer></script>
<script type="text/javascript" src="<%= htmlWebpackPlugin.files.js[2] %>" defer></script>
