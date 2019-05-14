<?php

/**
 * Add applicable namespaces to the ProcessWire classLoader.
 */
wire('classLoader')->addNamespace('Rockett\Sitemap', __DIR__ . '/src/Sitemap');
wire('classLoader')->addNamespace('Rockett\Traits', __DIR__ . '/src/Traits');
