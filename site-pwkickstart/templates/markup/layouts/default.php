<?php namespace ProcessWire;
  require_once "{$config->paths->templates}/markup/Helper.php";
?>

<div class="layoutDefault">
  <article class="layoutDefault-content">
    <?=$page->main;?>
  </article>
</div>

<?=Helper::renderModuleTemplate('Photoswipe');?>
