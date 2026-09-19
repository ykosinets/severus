<?php
/**
 * Field group: Review list
 *
 * Exported from the database so the definition ships with the theme.
 * Generated — edit the fields here, not in the ACF admin, which shows
 * groups registered in code as read-only.
 *
 * @package Severus_Noir
 */

defined( "ABSPATH" ) || exit;

return array(
  'key' => 'group_6616c60f42f5c',
  'title' => 'Review list',
  'fields' => 
  array(
    0 => 
    array(
      'key' => 'field_6616ccd021962',
      'label' => 'Subtitle',
      'name' => 'review-subtitle',
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
      'key' => 'field_6616cce521963',
      'label' => 'Title',
      'name' => 'review-title',
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
      'key' => 'field_6616c60f5c08e',
      'label' => 'Review list',
      'name' => 'review_list',
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
        0 => 'review',
      ),
      'post_status' => 
      array(
        0 => 'publish',
      ),
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
        'value' => '14',
      ),
      3 => 
      array(
        'param' => 'page_type',
        'operator' => '!=',
        'value' => 'front_page',
      ),
      4 => 
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
