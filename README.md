# Kickstart Site Profile

**Disclaimer**
This is mainly a personal site profile to kickstart my own ProcessWire Projects. Nevertheless, Feel free to dig around and copy whatever you find useful.

### Features
- easy deployment process (all sources outside of processwire)
- module based approach for templates/js/scss
- Vue Single File Components
- minification of css and js resources
- optimized webfont loading with https://github.com/typekit/webfontloader
- automated generation of critical css

### Install

- create vhost
- create hosts entry
- create database

Grab a copy of processwire and place the contents of this repository in the root of your ProcessWire directory. Install ProcessWire as usual (don’t forget to pick the site profile).

- create .gitkeep in site/assets/ cache, files, logs, sessions
- adjust .htaccess settings to your needs

Install the composer modules:

```
composer require nikic/fast-route
composer require firebase/php-jwt
```

- in config.php add at the bottom: `require("environment.php");`
- create environment.php:

```
<?php;

/* 
 * Database
*/ 
$config->dbHost = 'localhost';
$config->dbName = 'your-database-name';
$config->dbUser = 'user';
$config->dbPass = 'password';
$config->dbPort = '3306';

/* 
 * Config-Flags
*/ 

$config->debug = true;
```

- In src/package.json change url to your local development url
- in webpack.config.js change dev server proxy url to your development url

```
cd src
npm install
```

run `npm run build` and check if the site is working

For Development run `npm run dev` and open your browser at `http://localhost:8080`

### Critical CSS
To generate critical css for all necessary templates, add entries to package.json "criticalCSS" accordingly
