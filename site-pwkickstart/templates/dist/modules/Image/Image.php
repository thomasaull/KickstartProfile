<div class="Image">
  <img
    class="Image-image lazyload"
    style="object-position: <?=$image->focus()['left']?>% <?=$image->focus()['top']?>%;"
    src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
    data-sizes="auto"
    data-srcset="
      <?=$image->width(300)->url?> 300w,
      <?=$image->width(600)->url?> 600w,
      <?=$image->width(1200)->url?> 1200w
    "
    alt="<?=$image->description?>"
  />
</div>
