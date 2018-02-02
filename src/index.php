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
  <?php include("{$config->paths->templates}/markup/head.php"); ?>

  <?php
    if("<%= htmlWebpackPlugin.options.env %>" === 'production') {
      $criticalCss = Helper::checkForCriticalCss($page->template->name);
      if($criticalCss) echo "<style>$criticalCss</style>";
    }
  ?>

  <% for (var css in htmlWebpackPlugin.files.css) { %>
    <link href="<%= htmlWebpackPlugin.files.css[css] %>" rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
      <link href="<%= htmlWebpackPlugin.files.css[css] %>" rel="stylesheet">
    </noscript>
  <% } %>

  <script>
    <?php
      // Include errorTracking.js inline
      if ("<%= htmlWebpackPlugin.options.env %>" === 'production')
        include("{$config->paths->root}<%= htmlWebpackPlugin.files.chunks.errorTracking.entry %>");

      // Uncomment if you want to test or get Error-Tracking in Development:
      // else echo file_get_contents("<%= htmlWebpackPlugin.files.chunks.errorTracking.entry %>");
    ?>
  </script>

  <script><?php include("{$config->paths->templates}/static/cssrelpreload.js"); ?></script>

  <script type="text/javascript">
    // Remove noJs Class:
    document.documentElement.classList.remove('noJs')

    window.lazySizesConfig = window.lazySizesConfig || {};
    // Disable Lazysizes Auto Init:
    // window.lazySizesConfig.init = false;
  </script>
</head>

<?php
  $layoutClass = '';
  // $layoutClass = 'layoutDefault';
  // if ($page->layout) $layoutClass = 'layout' . ucwords($page->layout);
?>

<body class="<?=$page->bodyClass?> <?=$layoutClass?>">
  <?=Helper::renderModuleTemplate('NoJsWarning');?>

  <?php
  if ($page->layout)
    include("{$config->paths->templates}/markup/layouts/{$page->layout}.php");
  else
    include("{$config->paths->templates}/markup/layouts/default.php");
  ?>

  <!-- Tracking-Code -->
  <?=$pages->get("template=global_settings")->code?>

  <script>
    document.config = {
      publicPath: "<%= htmlWebpackPlugin.options.publicPath %>",
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

<script type="text/javascript" src="<%= htmlWebpackPlugin.files.chunks.critical.entry %>" async></script>
<script type="text/javascript" src="<%= htmlWebpackPlugin.files.chunks.bundle.entry %>" async></script>
