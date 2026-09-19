<?php
/**
 * Field group: Home page
 *
 * Exported from the database so the definition ships with the theme.
 * Generated — edit the fields here, not in the ACF admin, which shows
 * groups registered in code as read-only.
 *
 * @package Severus_Noir
 */

defined( "ABSPATH" ) || exit;

return array(
  'key' => 'group_661625ab66258',
  'title' => 'Home page',
  'fields' => 
  array(
    0 => 
    array(
      'key' => 'field_661625f74df05',
      'label' => 'Home group fields',
      'name' => 'home_group_fields',
      'aria-label' => '',
      'type' => 'group',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'layout' => 'block',
      'sub_fields' => 
      array(
        0 => 
        array(
          'key' => 'field_6616264b4df06',
          'label' => 'Heading',
          'name' => 'heading',
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
        1 => 
        array(
          'key' => 'field_661626594df07',
          'label' => 'Welcome text',
          'name' => 'welcome_text',
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
        2 => 
        array(
          'key' => 'field_6616288df846e',
          'label' => 'Video Poster',
          'name' => 'video_poster',
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
          'return_format' => 'url',
          'library' => 'all',
          'min_width' => '',
          'min_height' => '',
          'min_size' => '',
          'max_width' => '',
          'max_height' => '',
          'max_size' => '',
          'mime_types' => '',
          'preview_size' => 'medium',
        ),
        3 => 
        array(
          'key' => 'field_661626704df08',
          'label' => 'Video',
          'name' => 'video',
          'aria-label' => '',
          'type' => 'file',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => 
          array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'return_format' => 'url',
          'library' => 'all',
          'min_size' => '',
          'max_size' => '',
          'mime_types' => '',
        ),
        4 => 
        array(
          'key' => 'field_66192dea46ccb',
          'label' => 'Show get started block',
          'name' => 'show_get_started',
          'aria-label' => '',
          'type' => 'true_false',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => 
          array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'message' => '',
          'default_value' => 1,
          'ui' => 0,
          'ui_on_text' => '',
          'ui_off_text' => '',
        ),
      ),
    ),
  ),
  'location' => 
  array(
    0 => 
    array(
      0 => 
      array(
        'param' => 'post_type',
        'operator' => '==',
        'value' => 'page',
      ),
      1 => 
      array(
        'param' => 'page_type',
        'operator' => '!=',
        'value' => 'front_page',
      ),
      2 => 
      array(
        'param' => 'page',
        'operator' => '!=',
        'value' => '1203',
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
