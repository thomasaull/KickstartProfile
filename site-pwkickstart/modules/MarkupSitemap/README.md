## Sitemap for ProcessWire

Building on [MarkupSitemapXML](https://github.com/Notanotherdotcom/MarkupSitemapXML) by Pete, MarkupSitemap adds multi-language support using the built-in LanguageSupportPageNames. Where multi-language pages are available, they are added to the sitemap by means of an alternate link in that page’s `<url>`. Support for listing images in the sitemap on a page-by-page basis and using a sitemap stylesheet are also added.

---

### Getting Started

```
composer require rockett/sitemap
```

OR

In ProcessWire, install MarkupSitemap via the module installer. Enter `MarkupSitemap` into Modules > Install > New > Add Module from Directory. After installation, the sitemap will immediately be made available at `/sitemap.xml`.

If you’re looking for a basic sitemap, there’s nothing more you need to do. 🎇

---

### Configuration

If you’d like to fine-tune things a little, the module provides support for page-by-page configuration. If you’d like to make use of this, head to the module’s configuration page to get started.

#### Templates with sitemap options

With this option, you can select which templates (and, therefore, all pages assigned to those templates) can have individual sitemap options. These options allow you to —

- set which pages and, optionally, their children should be excluded from the sitemap (these options are independent of one another, so have the ability to hide a parent, but keep it’s children);
- define which page’s images should not be included in the sitemap (provided that image fields have been configured); and
- set an optional priority for each page.

When you add a template to the list and save, sitemap options will be made available to pages that use that template (in the Settings tab).

**Removal/Restoration:** Removing a template from the list will not delete any page options applicable to it. However, they will also not be read when rendering the sitemap. As such, when restoring a template to the list after having removed it, any previous options saved for a page that uses this template will be used when rendering the sitemap. The only time sitemap options are deleted is when either the page in question is completely deleted after having been trashed, or when the module is uninstalled.

**A note about the home page:** This page cannot be excluded from the sitemap. As such, the applicable exclusion options will not be available when editing it.

#### Templates without sitemap access

You can also set which templates should not have sitemap access at all. Pages belonging to templates listed here will (a) not be shown in the sitemap and (b) will not be able to change their options, even if listed in the previous section’s template list. As such, this option is non-destructive.

#### Image fields

If you’d like to include images in your sitemap (for somewhat enhanced Google Images support), you can specify the image fields you’d like MarkupSitemap to traverse and include. The sitemap will include images for every page that uses the field(s) you select, except for pages that are set to not have their images included (Settings tab).

#### Stylesheet

In the module’s configuration, you can also disable the stylesheet, which is turned on be default. If you’d like to use your own, you’ll need to specify an absolute URL to it (also be sure to use one that has mult-language and sub-element features).

#### ISO code for default language

If you’ve set your home page to not include a language ISO (default language name) **and** your home page’s default language name is empty, then you can set an ISO code here for the default language. This will prevent the sitemap from containing `hreflang="home"` for all default-language URLs.

#### Page priority

On each page that has sitemap options, you can set a priority between 0.0 and 1.0. You may not need to use this any many cases, but you may wish to give emphasis to certain child pages over their parents. Search engines tend to use other factors in determining priority, and so this option is not guaranteed to make a difference to your rankings.

#### Cache

By default, Sitemap will cache the output of your sitemap to improve request-performance. Thanks to an update from [@teppo](https://processwire.com/talk/profile/175-teppo/), you can now select the caching method you’d like to use (MarkupCache or WireCache, the former being the default), and set the Cache TTL (expiry period).

---

### Discussion & Support

Visit [processwire.com/talk/topic/17068-markupsitemap](https://processwire.com/talk/topic/17068-markupsitemap/) to discuss the module and obtain support.

---

### Credits

I’d like to thank [Mathew Davies](https://github.com/ThePixelDeveloper) for his [sitemap package](https://github.com/ThePixelDeveloper/Sitemap). It’s really great, and much better than most packages out there, as far as I’m concerned. There were a few bugs, which is why the package is locally maintained. Whilst the bugs have been fixed, the local package remains, for the time being.

---

Sitemap for Processwire is released under the [ISC License](LICENSE.md). The Sitemap package by Matthew Davies is licensed under the [MIT License](https://github.com/ThePixelDeveloper/Sitemap/blob/master/LICENSE).
