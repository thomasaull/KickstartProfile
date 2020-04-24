# Kickstart Site Profile

**Disclaimer**
This is mainly a personal site profile to kickstart my own ProcessWire Projects. Also most of the following instructions are for my very specific setup routine. Nevertheless, Feel free to dig around and copy whatever you find useful.

### Features
- easy deployment process (all sources outside of processwire)
- module based approach for templates/js/scss
- Vue Single File Components
- minification of css and js resources
- optimized webfont loading with https://github.com/bramstein/fontfaceobserver
- automated generation of critical css
- error reporting of javascript errors in ProcessWire logs
- Maintenance Modes for Frontend and Backend
- Easy testing from devices on local network
- Integrates RestApi: https://github.com/thomasaull/RestApi

### Install

- create vhost
- create hosts entry
- create database

Grab a copy of processwire and place the contents of this repository in the root of your ProcessWire directory (including hidden files!). Install ProcessWire as usual (don’t forget to pick the site profile). If you want to keep the .gitignore, make sure to disable the checkbox at the last step of the Processwire installation routine.

- create .gitkeep in site/assets/ cache, files, logs, sessions
```
If Terminal is open in /
touch dist/site/assets/cache/.gitkeep dist/site/assets/files/.gitkeep dist/site/assets/logs/.gitkeep dist/site/assets/sessions/.gitkeep
```
- adjust .htaccess settings to your needs
- adjust .gitignore to your needs, I usually use this as a starting point:
```
# ProcessWire
/dist/site/assets/cache/*
!/dist/site/assets/cache/.gitkeep

/dist/site/assets/files/*
!/dist/site/assets/files/.gitkeep

/dist/site/assets/logs/*
!/dist/site/assets/logs/.gitkeep

/dist/site/assets/sessions/*
!/dist/site/assets/sessions/.gitkeep

/dist/site/assets/backups/
/dist/site/templates/dist/

/dist/site/environment.php
/dist/.htaccess

# OSX
.DS_Store

# Node
node_modules
```

- in config.php add at the bottom: `require("environment.php");`
- create environment.php:

```
<?php namespace ProcessWire;

$config->environment = 'development';

// Database
$config->dbHost = 'localhost';
$config->dbName = 'pwkickstart';
$config->dbUser = 'root';
$config->dbPass = '';
$config->dbPort = '3306';

// Config Flags
$config->debug = true;
$config->maintenanceBackend = false;
$config->maintenanceFrontend = false;

// HTTP Hosts
$config->httpHosts = array('localhost:8000');

// Get notified about critical errors:
$config->adminEmail = 'your@email.com';
```

- In src/package.json change url to your local development url

```
cd src
npm install
```

run `npm run build` and check if the site is working

For Development run `npm run dev` and open your browser at `http://localhost:8080`

### Critical CSS
Critical CSS gets generated for all visible pages automatically. If you want to add hidden pages, change `$additionalHiddenPages` in site/api/Critial.php

You can decide to create the critical CSS from the live site by adjusting `urls.critical` in `package.json`

Also, if you want to use critical CSS make sure to activate the flag `$useCriticalCss` in `src/index.php`

### Contentbuilder

I use FieldtypeRepeaterMatrix for the Contentbuilder. Since it’s  a Pro Module I’m not allowed to include it here. So you need to install it yourself (or alternatively delete it or use another approach for the contentbuilder).

### VS Code
Open „Workspace Settings“ and add this to `settings`:
```
"search.exclude": {
  "**/dist": true
}
```

Save Workspace in your project folder
