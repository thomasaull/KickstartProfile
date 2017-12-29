# SelectorTest Process Module

This module creates a page in the ProcessWire admin where you can test and build
selectors without editing a template file or a bootstrapped script.

## Features

* Edit selector string and display results (and possible errors as reported by ProcessWire)
* Explore properties and data of matching pages in a tree view
  * Language aware: multi-language and language-alternate fields supported
  * Repeater fields and values
  * Images and their variations on disk
  * More data is loaded on-demand as the tree is traversed deeper
* See page permissions for the chosen role
* Quick links to edit/view pages, edit templates and run new selectors (select pages with the same template or children of a page)
* Page statuses visualized like in default admin theme
* Add pagination

## Changelog

**1.13 / 2013-09-20**

* Fixed: multi-lingual titles are now displayed correctly and page name is used as a fallback in case of empty/undefined title.
* Added: page permissions are shown for the chosen role

**1.12 / 2013-05-02**

* Fixed: Single page fields are now handled correctly.

**1.11 / 2013-02-07**

* Added: all field and template data shown + cached at template level
* Added: fields inside a fieldset are wrapped into a new subtree (page data only - fields inside a template are in a flat list)

**1.1 / 2012-11-10**

* Explore properties and data of matching pages in a tree view
  * Language aware: multi-language and language-alternate fields supported
  * Repeater fields and values
  * Images and their variations on disk
  * More data is loaded on-demand as the tree is traversed deeper
* Quick links to edit/view pages, edit templates and run new selectors (select pages with the same template or children of a page)
* Page statuses visualized like in default admin theme

**1.0 / 2012-09-23**

* Initial release
* Edit selector string and display results (and possible errors as reported by ProcessWire)
* Add pagination

------
SelectorTest Copyright 2012, 2013 Niklas Lakanen

jqTree (https://github.com/mbraak/jqTree) Copyright 2012 Marco Braak
