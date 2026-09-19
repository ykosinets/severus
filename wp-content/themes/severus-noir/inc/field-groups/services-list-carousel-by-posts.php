<?php
/**
 * Field group: Services List Carousel by Posts
 *
 * Exported from the database so the definition ships with the theme.
 * Generated — edit the fields here, not in the ACF admin, which shows
 * groups registered in code as read-only.
 *
 * @package Severus_Noir
 */

defined( "ABSPATH" ) || exit;

return array(
  'key' => 'group_66177694dbb57',
  'title' => 'Services List Carousel by Posts',
  'fields' => 
  array(
    0 => 
    array(
      'key' => 'field_661776e0ab6b7',
      'label' => 'Subtitle',
      'name' => 'service-subtitle',
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
      'key' => 'field_661776f7ab6b8',
      'label' => 'Title',
      'name' => 'service-title',
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
      'key' => 'field_66177695ab6b6',
      'label' => 'Services list',
      'name' => 'services_list',
      'aria-label' => '',
      'type' => 'relationship',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'post_type' => 
      array(
        0 => 'service',
      ),
      'post_status' => '',
      'taxonomy' => '',
      'filters' => 
      array(
        0 => 'search',
      ),
      'return_format' => 'object',
      'min' => '',
      'max' => '',
      'elements' => '',
      'bidirectional' => 0,
      'bidirectional_target' => 
      array(
      ),
    ),
    3 => 
    array(
      'key' => 'field_661781490b896',
      'label' => 'Enable autoplay carousel',
      'name' => 'service-autoplay',
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
      'default_value' => 0,
      'ui' => 0,
      'ui_on_text' => '',
      'ui_off_text' => '',
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
        'param' => 'page',
        'operator' => '!=',
        'value' => '21',
      ),
      2 => 
      array(
        'param' => 'page',
        'operator' => '!=',
        'value' => '16',
      ),
      3 => 
      array(
        'param' => 'page',
        'operator' => '!=',
        'value' => '14',
      ),
      4 => 
      array(
        'param' => 'page_type',
        'operator' => '!=',
        'value' => 'front_page',
      ),
      5 => 
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
