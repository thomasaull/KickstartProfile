<?php namespace ProcessWire; ?>

<div class="Gallery">

  <?php if(isset($text)): ?>
  <div class="Gallery-text defaultText"><?=Helper::replaceQuotes($text)?></div>
  <?php endif; ?>

  <div class="Gallery-images" ref="images">
    <?php
      $index = 0;
      foreach($images as $image):
    ?>
      <a
        href="<?=$image->width(1920)->url?>"
        width="<?=$image->width(1920)->width?>"
        height="<?=$image->width(1920)->height?>"
        index="<?=$index?>"
        class="Gallery-link"
        @click.prevent="openGallery(<?=$index?>)"
      >
        <img
          class="Gallery-image lazyload"
          style="object-position: <?=$image->focus()['left']?>% <?=$image->focus()['top']?>%;"
          src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
          data-sizes="auto"
          data-srcset="
            <?=$image->width(100)->url?> 100w,
            <?=$image->width(200)->url?> 200w,
            <?=$image->width(400)->url?> 400w
          "
          alt="<?=Helper::replaceQuotes($image->description)?>"
        />
      </a>
    <?php
      $index++;
      endforeach;
    ?>
  </div>
</div>
