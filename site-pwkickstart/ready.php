<?php namespace ProcessWire;

if($config->environment === 'development') {
  $wire->addHookAfter('Pagefile::url, Pagefile::filename', function($event) {
    $config = $event->wire('config');
    $file = $event->return;
    
    if($event->method == 'url') {
      // convert url to disk path
      $file = $config->paths->root . substr($file, strlen($config->urls->root));
    }
    
    if(!file_exists($file)) {
      try {
        // download file from source if it doesn't exist here
        $src = wire('config')->liveUrl;
        $url = str_replace($config->paths->files, $src, $file);
        $http = new WireHttp();
        $http->download($url, $file);
      } catch (\Throwable $th) {
        // do nothing if it fails…
      }
    }
  });
}