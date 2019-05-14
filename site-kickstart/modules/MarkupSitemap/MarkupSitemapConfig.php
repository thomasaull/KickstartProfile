<?php

/**
 * Sitemap for ProcessWire
 *
 * Module config class
 *
 * @author Mike Rockett <github@rockett.pw>
 * @copyright 2017-18
 * @license ISC
 */

require_once __DIR__ . '/ClassLoader.php';

use Rockett\Traits\FieldsTrait;

class MarkupSitemapConfig extends ModuleConfig
{
  use FieldsTrait;

  /**
   * Get default condifguration, automatically passed to input fields.
   * @return array
   */
  public function getDefaults()
  {
    return [
      'sitemap_stylesheet' => true,
      'sitemap_exclude_templates' => [],
      'sitemap_include_hidden' => false,
    ];
  }

  /**
   * Render input fields on config Page.
   * @return string
   */
  public function getInputFields()
  {
    // Gather a list of templates
    $allTemplates = $this->templates;
    foreach ($allTemplates as $template) {
      // Exclude system templates
      if ($template->flags & Template::flagSystem) {
        continue;
      }
      $templates[] = $template;
    }

    // If saving, remove the sitemap cache to effect
    // possible changes in configuration.
    $this->input->post->submit_save_module &&
    $this->removeSitemapCache() &&
    $this->message($this->_('Removed sitemap cache'));

    // Start inputfields
    $inputfields = parent::getInputfields();

    // Add the template-selector field
    $includeTemplatesField = $this->buildInputField('AsmSelect', [
      'name+id' => 'sitemap_include_templates',
      'label' => $this->_('Templates with sitemap options'),
      'description' => $this->_('Select which templates (and, therefore, all pages assigned to those templates) can have individual sitemap options. These options (shown in the Settings tab of the page editor) allow you to set which pages and, optionally, their children should be excluded from the sitemap when it is rendered; define which page’s images should not be included in the sitemap (provided that image fields have been added below); and, lastly, set an optional priority for each page.'),
      'notes' => $this->_("**Removal/Restoration:** Removing a template from this list will not delete any page options applicable to it. However, they will also not be read when rendering the sitemap. As such, when restoring a template to this list after having removed it, any previous options saved for a page that uses this template will be used when rendering the sitemap. The only time sitemap options are deleted is when either the page in question is completely deleted after having been trashed, or when the module is uninstalled.\n\n**A note about the home page: ** This page cannot be excluded from the sitemap. As such, the applicable exclusion options will not be available when editing it."),
      'icon' => 'cubes',
    ]);
    foreach ($templates as $template) {
      $includeTemplatesField->addOption($template->name, $template->get('label|name'));
    }
    $inputfields->add($includeTemplatesField);

    // Add the template-selector field that disables template access to the module.
    // The home template cannot be added to the exclusions list.
    $excludeTemplatesField = $this->buildInputField('AsmSelect', [
      'name+id' => 'sitemap_exclude_templates',
      'label' => $this->_('Templates without sitemap access'),
      'description' => $this->_('Select which templates (and, therefore, all pages assigned to those templates) should not have sitemap access.'),
      'notes' => $this->_('**Note:** Adding a template to this list overrides template-level functionality defined above. If a template is listed here, its pages will not have access to any sitemap functionality, including options, and will not be included in the rendered sitemap. However, the template will not be removed from the options list above, in the case that you wish to easily restore it. As such, this is a non-destructive configuration option.'),
      'icon' => 'remove',
      'collapsed' => Inputfield::collapsedBlank,
    ]);
    foreach ($templates as $template) {
      if ($template->id !== 1) {
        $excludeTemplatesField->addOption($template->name, $template->get('label|name'));
      }
    }
    $inputfields->add($excludeTemplatesField);

    // Add the image-field-selector field if image fields exist
    if ($imageFields = $this->fields->find('type=FieldtypeImage|FieldtypeCroppableImage3') and $imageFields->count) {
      $imageFieldsField = $this->buildInputField('AsmSelect', [
        'name+id' => 'sitemap_image_fields',
        'label' => $this->_('Image fields'),
        'description' => $this->_('If you’d like to include images in your sitemap (for somewhat enhanced Google Images support), specify the image fields you’d like MarkupSitemap to traverse and include. The sitemap will include images for every page that uses the field(s) you select below, except for pages that are set to not have their images included.'),
        'icon' => 'image',
        'collapsed' => Inputfield::collapsedBlank,
      ]);
      foreach ($imageFields as $field) {
        $imageFieldsField->addOption($field->name, "{$field->get('label|name')} (used in {$field->numFieldgroups()} templates)");
      }
      $inputfields->add($imageFieldsField);
    }

    // Add the default-language iso text field
    if ($this->siteUsesLanguageSupportPageNames()) {
      $inputfields->add($this->buildInputField('Text', [
        'name+id' => 'sitemap_default_iso',
        'label' => $this->_('ISO code for default language'),
        'description' => $this->_('If you’ve set your home page to not include a language ISO (default language name) via LanguageSupportPageNames **and** your home page’s default language name is empty, then you can set an ISO code here for the default language that will appear in the sitemap. This will prevent the sitemap from containing `hreflang="home"` for all default-language URLs.'),
        'notes' => $this->_('Note that if your home page has a name for the default language, then this option will not take any effect.'),
        'placeholder' => $this->_('Example: en'),
        'icon' => 'language',
        'collapsed' => Inputfield::collapsedBlank,
      ]));
    }

    $inputfields->add($this->buildInputField('Checkbox', [
      'name+id' => 'sitemap_include_hidden',
      'label' => $this->_('Include hidden or unpublished pages'),
      'description' => $this->_('When scanning for children, those that are hidden or unpublished will be included when this option is enabled.'),
      'icon' => 'eye-slash',
      'collapsed' => Inputfield::collapsedBlank,
    ]));

    // Create the stylesheet fieldset
    $stylesheetFieldset = $this->buildInputField('Fieldset', [
      'label' => $this->_('Stylesheet'),
      'collapsed' => Inputfield::collapsedBlank,
      'icon' => 'css3',
    ]);

    // Add the stylesheet checkbox
    $stylesheetFieldset->add($this->buildInputField('Checkbox', [
      'name+id' => 'sitemap_stylesheet',
      'label' => $this->_('Add a stylesheet to the sitemap'),
    ]));

    // Add the custom stylesheet text field
    $stylesheetFieldset->add($this->buildInputField('Text', [
      'name+id' => 'sitemap_stylesheet_custom',
      'label' => $this->_('Custom Stylesheet'),
      'description' => $this->_('If you would like to use your own stylesheet, enter the absolute URL to its file here.'),
      'placeholder' => $this->_('Example: https://example.tld/assets/sitemap-stylesheet.xsl'),
      'showIf' => 'sitemap_stylesheet=1',
      'notes' => $this->_('The default stylesheet is located at **assets/sitemap-stylesheet.xsl** in the module’s directory. If you leave this field blank or your input is not a valid URL, the default will be used.'),
      'icon' => 'file-o',
      'collapsed' => Inputfield::collapsedBlank,
    ]));

    // Add the stylesheet fieldset to the inputfields
    $inputfields->add($stylesheetFieldset);

    // Add the support-development markup field
    $supportText = $this->wire('sanitizer')->entitiesMarkdown($this->_('Sitemap is proudly [open-source](http://opensource.com/resources/what-open-source) and is [free to use](https://en.wikipedia.org/wiki/Free_software) for personal and commercial projects. Please consider [making a small donation](https://rockett.pw/donate) in support of the development of MarkupSitemap and other modules.'), ['fullMarkdown' => true]);
    $inputfields->add($this->buildInputField('Markup', [
      'id' => 'support_development',
      'label' => $this->_('Support Development'),
      'value' => $supportText,
      'icon' => 'paypal',
      'collapsed' => Inputfield::collapsedYes,
    ]));

    $this->config->scripts->add($this->urls->httpSiteModules . 'MarkupSitemap/assets/scripts/config.js');

    return $inputfields;
  }

  /**
   * Remove the sitemap cache
   * @return bool
   */
  protected function removeSitemapCache()
  {
    try {
      $cachePath = $this->config->paths->cache . 'MarkupCache/MarkupSitemap';
      $removed = (bool) CacheFile::removeAll($cachePath, true);
    } catch (\Exception $e) {
      $removed = false;
    }

    return $removed;
  }

  /**
   * Determine if the site uses the LanguageSupportPageNames module.
   * @return bool
   */
  protected function siteUsesLanguageSupportPageNames()
  {
    return $this->modules->isInstalled('LanguageSupportPageNames');
  }
}
