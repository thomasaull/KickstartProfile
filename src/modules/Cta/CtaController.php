<?php namespace ProcessWire;

if($data->cta->ctaType) {
  $data->type = $data->cta->ctaType->value;

  // Button:
  if($data->cta->ctaType->value === 'link') {
    if($data->cta->linkInternal) {
      $data->link = $data->cta->linkInternal->url;
      $data->label = $data->cta->linkInternal->title;
    }

    if($data->cta->linkExternal) {
      $data->link = $data->cta->linkExternal;
      $data->label = $data->cta->linkExternal;
    }
  }

  // Email
  if($data->cta->ctaType->value === 'email') {
    $email = wire('pages')->get(1029)->email;
    $subject = rawurlencode($data->cta->subject);
    $message = rawurlencode($data->cta->message);

    $data->link = "mailto:$email?subject=$subject&body=$message";
  }

  // Override label if set
  if($data->cta->label) {
    $data->label = $data->cta->label;
  }
}

return $data;
