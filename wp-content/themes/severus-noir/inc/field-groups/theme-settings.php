<?php
/**
 * Field group: Theme settings
 *
 * Exported from the database so the definition ships with the theme.
 * Generated — edit the fields here, not in the ACF admin, which shows
 * groups registered in code as read-only.
 *
 * @package Severus_Noir
 */

defined( "ABSPATH" ) || exit;

return array(
  'key' => 'group_662346e6d605e',
  'title' => 'Theme settings',
  'fields' => 
  array(
    0 => 
    array(
      'key' => 'field_662346e713fcb',
      'label' => 'Cases list page',
      'name' => '',
      'aria-label' => '',
      'type' => 'tab',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'placement' => 'top',
      'endpoint' => 0,
    ),
    1 => 
    array(
      'key' => 'field_66234a1da6879',
      'label' => 'Case List Title',
      'name' => 'case-list-title',
      'aria-label' => '',
      'type' => 'text',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'maxlength' => '',
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
    ),
    2 => 
    array(
      'key' => 'field_662354f9a687a',
      'label' => 'Case List Subtitle',
      'name' => 'case-list-subtitle',
      'aria-label' => '',
      'type' => 'text',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'maxlength' => '',
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
    ),
    3 => 
    array(
      'key' => 'field_6623472213fcc',
      'label' => 'Case List Description',
      'name' => 'case-list-description',
      'aria-label' => '',
      'type' => 'textarea',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'maxlength' => '',
      'rows' => '',
      'placeholder' => '',
      'new_lines' => '',
    ),
    4 => 
    array(
      'key' => 'field_6a5a22bfea0ae',
      'label' => '404',
      'name' => '',
      'aria-label' => '',
      'type' => 'tab',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'placement' => 'top',
      'endpoint' => 0,
    ),
    5 => 
    array(
      'key' => 'field_6a5a22c8ea0af',
      'label' => 'Title',
      'name' => 'error_title',
      'aria-label' => '',
      'type' => 'textarea',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'maxlength' => '',
      'rows' => '',
      'placeholder' => '',
      'new_lines' => 'br',
    ),
    6 => 
    array(
      'key' => 'field_6a5a22e2ea0b0',
      'label' => 'text',
      'name' => 'error_text',
      'aria-label' => '',
      'type' => 'textarea',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'maxlength' => '',
      'rows' => '',
      'placeholder' => '',
      'new_lines' => 'br',
    ),
    7 => 
    array(
      'key' => 'field_6a5a26fccf70b',
      'label' => 'move to',
      'name' => 'move_text',
      'aria-label' => '',
      'type' => 'text',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'maxlength' => '',
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
    ),
    8 => 
    array(
      'key' => 'field_6a5a23b9d14c5',
      'label' => 'Text link',
      'name' => 'link_to_home',
      'aria-label' => '',
      'type' => 'link',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'return_format' => 'array',
    ),
    9 => 
    array(
      'key' => 'field_6a5a22fbea0b1',
      'label' => 'Image',
      'name' => 'error_image',
      'aria-label' => '',
      'type' => 'image',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'return_format' => 'array',
      'library' => 'all',
      'min_width' => '',
      'min_height' => '',
      'min_size' => '',
      'max_width' => '',
      'max_height' => '',
      'max_size' => '',
      'mime_types' => '',
      'preview_size' => 'thumbnail',
    ),
    10 => 
    array(
      'key' => 'field_6a5a2307ea0b2',
      'label' => 'Bottom Links',
      'name' => 'error_links',
      'aria-label' => '',
      'type' => 'repeater',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'layout' => 'table',
      'pagination' => 0,
      'min' => 0,
      'max' => 0,
      'collapsed' => '',
      'button_label' => 'Add Row',
      'rows_per_page' => 20,
      'sub_fields' => 
      array(
        0 => 
        array(
          'key' => 'field_6a5a231bea0b3',
          'label' => 'Links',
          'name' => 'error_botlinks',
          'aria-label' => '',
          'type' => 'link',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => 
          array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'return_format' => 'array',
          'parent_repeater' => 'field_6a5a2307ea0b2',
        ),
      ),
    ),
    11 => 
    array(
      'key' => 'field_6a5a280818d8f',
      'label' => 'Copyright text',
      'name' => 'copyright_text',
      'aria-label' => '',
      'type' => 'text',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'maxlength' => '',
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
    ),
    12 => 
    array(
      'key' => 'field_6a5c7ef3613b1',
      'label' => 'Footer',
      'name' => '',
      'aria-label' => '',
      'type' => 'tab',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'placement' => 'top',
      'endpoint' => 0,
    ),
    13 => 
    array(
      'key' => 'field_6a5c7f56613b2',
      'label' => 'Footer tagline',
      'name' => 'footer_tagline',
      'aria-label' => '',
      'type' => 'textarea',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'maxlength' => '',
      'rows' => '',
      'placeholder' => '',
      'new_lines' => 'br',
    ),
    14 => 
    array(
      'key' => 'field_6a5c7fd5d41c0',
      'label' => 'Reach Out title',
      'name' => 'footer_reach_title',
      'aria-label' => '',
      'type' => 'text',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'maxlength' => '',
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
    ),
    15 => 
    array(
      'key' => 'field_6a5c7ff5d41c1',
      'label' => 'Email label',
      'name' => 'footer_email_label',
      'aria-label' => '',
      'type' => 'text',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'maxlength' => '',
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
    ),
    16 => 
    array(
      'key' => 'field_6a5c8006d41c2',
      'label' => 'Email address',
      'name' => 'footer_email',
      'aria-label' => '',
      'type' => 'email',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
    ),
    17 => 
    array(
      'key' => 'field_6a5c807310ace',
      'label' => 'Offices title',
      'name' => 'footer_offices_title',
      'aria-label' => '',
      'type' => 'text',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'maxlength' => '',
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
    ),
    18 => 
    array(
      'key' => 'field_6a5c808110acf',
      'label' => 'Offices',
      'name' => 'footer_offices',
      'aria-label' => '',
      'type' => 'repeater',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'layout' => 'table',
      'pagination' => 0,
      'min' => 0,
      'max' => 0,
      'collapsed' => '',
      'button_label' => 'Add Row',
      'rows_per_page' => 20,
      'sub_fields' => 
      array(
        0 => 
        array(
          'key' => 'field_6a5c809510ad0',
          'label' => 'Flag',
          'name' => 'office_flag',
          'aria-label' => '',
          'type' => 'image',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => 
          array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'return_format' => 'array',
          'library' => 'all',
          'min_width' => '',
          'min_height' => '',
          'min_size' => '',
          'max_width' => '',
          'max_height' => '',
          'max_size' => '',
          'mime_types' => '',
          'preview_size' => 'medium',
          'parent_repeater' => 'field_6a5c808110acf',
        ),
        1 => 
        array(
          'key' => 'field_6a5c80aa10ad1',
          'label' => 'Country',
          'name' => 'office_country',
          'aria-label' => '',
          'type' => 'text',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => 
          array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'default_value' => '',
          'maxlength' => '',
          'placeholder' => '',
          'prepend' => '',
          'append' => '',
          'parent_repeater' => 'field_6a5c808110acf',
        ),
        2 => 
        array(
          'key' => 'field_6a5c80b510ad2',
          'label' => 'Address',
          'name' => 'office_address',
          'aria-label' => '',
          'type' => 'textarea',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => 
          array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'default_value' => '',
          'maxlength' => '',
          'rows' => '',
          'placeholder' => '',
          'new_lines' => '',
          'parent_repeater' => 'field_6a5c808110acf',
        ),
      ),
    ),
    19 => 
    array(
      'key' => 'field_6a5c816540b5b',
      'label' => 'Explore title',
      'name' => 'footer_explore_title',
      'aria-label' => '',
      'type' => 'text',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'maxlength' => '',
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
    ),
    20 => 
    array(
      'key' => 'field_6a5c829ec6939',
      'label' => 'Services title',
      'name' => 'footer_services_title',
      'aria-label' => '',
      'type' => 'text',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'maxlength' => '',
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
    ),
    21 => 
    array(
      'key' => 'field_6a5c8350510b5',
      'label' => 'Copyright text',
      'name' => 'copyright_text',
      'aria-label' => '',
      'type' => 'text',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'default_value' => '',
      'maxlength' => '',
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
    ),
  ),
  'location' => 
  array(
    0 => 
    array(
      0 => 
      array(
        'param' => 'options_page',
        'operator' => '==',
        'value' => 'theme-settings',
      ),
    ),
  ),
  'menu_order' => 0,
  'position' => 'normal',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => '',
  'active' => true,
  'description' => '',
  'show_in_rest' => 0,
);
