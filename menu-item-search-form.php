<?php

/**
 Plugin Name: Menu Item Search Form
 Plugin URI: http://github.com/benignware/wp-menu-item-search-form
 Description: Add a search-form to menus
 Version: 0.0.1
 Author: Rafael Nowrotek, Benignware
 Author URI: http://benignware.com
 License: MIT
*/



add_action('admin_init', 'menu_item_search_form_nav_menu_meta_box');

function menu_item_search_form_nav_menu_meta_box() {
  add_meta_box(
      'menu-item-search-form-nav-box',
      __('Search Form'),
      'menu_item_search_form_display_menu_custom_box',
      'nav-menus',
      'side',
      'default'
  );
}

function menu_item_search_form_display_menu_custom_box() {
    ?>
    <div id="posttype-menu-item-search-form" class="posttypediv">
        <div id="tabs-panel-wishlist-login" class="tabs-panel tabs-panel-active">
          <ul id ="wishlist-login-checklist" class="categorychecklist form-no-clear">
            <li>
              <label class="menu-item-title">
                <input style="visibility: hidden; width: 0; min-width:0; height: 0; " checked type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1">
                <?= __('Display search-form'); ?>
              </label>
              <input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="search-form">
              <input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="<?= __('Search'); ?>">
              <input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="#search-form">
              <input type="hidden" class="menu-item-classes" name="menu-item[-1][menu-item-classes]" value="menu-item-search-form">
            </li>
          </ul>
        </div>
        <p class="button-controls">
          <span class="add-to-menu">
            <input type="submit" class="button-secondary submit-add-to-menu right" value="Add to Menu" name="add-post-type-menu-item" id="submit-posttype-menu-item-search-form">
            <span class="spinner"></span>
          </span>
        </p>
      </div>
    <?php
}


add_filter( 'nav_menu_link_attributes', function( $atts, $item, $args ) {
  if ($item->type === 'search-form') {
    $atts['data-menu-item-search-form'] = 'search-form';
  }

  return $atts;
}, 10, 3 );


add_filter( 'wp_nav_menu', function($nav_menu = '', $args = array()) {
  // Parse DOM
  $doc = new DOMDocument();
  @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $nav_menu );

  $doc_xpath = new DOMXpath($doc);
  $elements = $doc_xpath->query('//*[@data-menu-item-search-form="search-form"]');
  $search_form = get_search_form(false);

  foreach ($elements as $element) {
    $placeholder = uniqid();

    $fragment = $doc->createDocumentFragment();
    $fragment->appendXML($placeholder);

    $new_element = $doc->createElement('span');
    $new_element->appendChild($fragment);

    // Copy attributes
    if ($element->hasAttributes()) {
      foreach ($element->attributes as $attr) {
        $name = $attr->nodeName;
        $value = $attr->nodeValue;

        if ($name !== 'data-menu-item-search-form' && $name !== 'href') {
          $new_element->setAttribute($name, $value);
        }
      }
    }

    $element->parentNode->insertBefore($new_element, $element);
    $element->parentNode->removeChild($element);
  }

  $nav_menu = preg_replace('~(?:<\?[^>]*>|<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>)\s*~i', '', $doc->saveHTML());
  $nav_menu = str_replace($placeholder, $search_form, $nav_menu);

  return $nav_menu;
});
