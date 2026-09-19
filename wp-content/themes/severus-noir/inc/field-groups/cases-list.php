<?php
/**
 * Field group: Cases List
 *
 * Exported from the database so the definition ships with the theme.
 * Generated — edit the fields here, not in the ACF admin, which shows
 * groups registered in code as read-only.
 *
 * @package Severus_Noir
 */

defined( "ABSPATH" ) || exit;

return array(
  'key' => 'group_66179832cc963',
  'title' => 'Cases List',
  'fields' => 
  array(
    0 => 
    array(
      'key' => 'field_66179865a8ad5',
      'label' => 'Subtitle',
      'name' => 'case-subtitle',
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
      'default_value' => 'Cases',
      'maxlength' => '',
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
    ),
    1 => 
    array(
      'key' => 'field_6617987aa8ad6',
      'label' => 'Title',
      'name' => 'case-title',
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
      'default_value' => 'Our portfolio',
      'maxlength' => '',
      'placeholder' => '',
      'prepend' => '',
      'append' => '',
    ),
    2 => 
    array(
      'key' => 'field_66179833a8ad4',
      'label' => 'Cases list',
      'name' => 'case-list',
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
        0 => 'case',
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
      5 => 
      array(
        'param' => 'post_type',
        'operator' => '!=',
        'value' => 'service',
      ),
    ),
  ),
  'menu_order' => 3,
  'position' => 'normal',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => '',
  'active' => true,
  'description' => '',
  'show_in_rest' => 0,
);
