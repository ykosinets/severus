<?php
/**
 * Field group: Insights List
 *
 * Exported from the database so the definition ships with the theme.
 * Generated — edit the fields here, not in the ACF admin, which shows
 * groups registered in code as read-only.
 *
 * @package Severus_Noir
 */

defined( "ABSPATH" ) || exit;

return array(
  'key' => 'group_661937734d889',
  'title' => 'Insights List',
  'fields' => 
  array(
    0 => 
    array(
      'key' => 'field_66193c5e2d35d',
      'label' => 'Subtitle',
      'name' => 'insights-subtitle',
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
      'key' => 'field_66193c482d35c',
      'label' => 'Title',
      'name' => 'insights-title',
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
      'key' => 'field_66193c182d35b',
      'label' => 'Post list',
      'name' => 'insights_list',
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
        0 => 'post',
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
      'key' => 'field_66293fed9824d',
      'label' => 'Enable autoplay carousel',
      'name' => 'insights-autoplay',
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
  'menu_order' => 8,
  'position' => 'normal',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => '',
  'active' => true,
  'description' => '',
  'show_in_rest' => 0,
);
