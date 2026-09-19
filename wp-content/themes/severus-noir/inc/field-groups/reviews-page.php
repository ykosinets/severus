<?php
/**
 * Field group: Reviews page
 *
 * Exported from the database so the definition ships with the theme.
 * Generated — edit the fields here, not in the ACF admin, which shows
 * groups registered in code as read-only.
 *
 * @package Severus_Noir
 */

defined( "ABSPATH" ) || exit;

return array(
  'key' => 'group_6616c41a36922',
  'title' => 'Reviews page',
  'fields' => 
  array(
    0 => 
    array(
      'key' => 'field_6616c41a5a7ec',
      'label' => 'Reviews',
      'name' => 'reviews',
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
          'key' => 'field_6616c45c5a7ed',
          'label' => 'Profession',
          'name' => 'profession',
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
          'key' => 'field_6616c4695a7ee',
          'label' => 'Rating',
          'name' => 'rating',
          'aria-label' => '',
          'type' => 'range',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => 
          array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'default_value' => 5,
          'min' => 1,
          'max' => 5,
          'step' => '',
          'prepend' => '',
          'append' => '',
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
        'value' => 'review',
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
