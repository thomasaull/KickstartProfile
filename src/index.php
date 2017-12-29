<?php namespace ProcessWire; require_once("{$config->paths->templates}/markup/Helper.php"); ?>

<!DOCTYPE html>
<html lang="de" class="no-js">
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

  <script type="text/javascript" src="<%= htmlWebpackPlugin.files.chunks.critical.entry %>"></script>
</head>

<body class="<?=$page->bodyClass?>">
  <noscript>
    <div class="jswarning">
      <div class="jswarning-text">
        Diese Website benötigt Javascript um richtig zu funktionieren. Leider ist bei deinem Browser Javascript deaktiviert.
        Wenn du es aktivieren möchtest, aber nicht weiß wie das geht, folge einfach <a href="http://enable-javascript.com/de/">diesem Link</a> für eine Anleitung.
      </div>
    </div>
  </noscript>

  <?php
  if ($page->layout)
    include("{$config->paths->templates}/markup/layouts/{$page->layout}.php");
  else
    include("{$config->paths->templates}/markup/layouts/default.php");
  ?>

  <!-- Tracking-Code -->
  <?=$pages->get("template=global_settings")->code?>

  <script>
    document.config = { publicPath: "<%= htmlWebpackPlugin.options.publicPath %>" }
    document.jsvars = <?=json_encode($config->jsvars->getArray());?>;
  </script>

  <script type="text/javascript" src="<%= htmlWebpackPlugin.files.chunks.bundle.entry %>"></script>
</body>
</html>
