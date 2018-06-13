<?php namespace ProcessWire; ?>

<div class="Download">

  <?php if(isset($text)): ?>
  <div class="Download-text defaultText"><?=Helper::replaceQuotes($text)?></div>
  <?php endif; ?>

  <div class="defaultText defaultText--lists">
  <ul class="Download-files">
    <?php foreach($files as $file): ?>
    <li class="Download-file">
      <a href="<?=$file->url?>" class="Download-link">
        <?=$file->basename?> (<?=$file->filesizeStr?>)
      </a>
      <div class="Download-description"><?=Helper::replaceQuotes($file->description)?></div>
    </li>
    <?php endforeach; ?>
  </ul>
  </div>

</div>
