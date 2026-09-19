<?php
/**
 * Field group: Contact From
 *
 * Exported from the database so the definition ships with the theme.
 * Generated — edit the fields here, not in the ACF admin, which shows
 * groups registered in code as read-only.
 *
 * @package Severus_Noir
 */

defined( "ABSPATH" ) || exit;

return array(
  'key' => 'group_6616a62e22a45',
  'title' => 'Contact From',
  'fields' => 
  array(
    0 => 
    array(
      'key' => 'field_6616a62e38c7d',
      'label' => 'Contact Form',
      'name' => 'contact_form',
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
          'key' => 'field_6616a65f38c7e',
          'label' => 'Under title',
          'name' => 'under_title',
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
          'key' => 'field_6616a6e138c7f',
          'label' => 'Title',
          'name' => 'title',
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
          'key' => 'field_6616a70338c80',
          'label' => 'Subtitle',
          'name' => 'subtitle',
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
          'key' => 'field_6616a71a38c81',
          'label' => 'Text',
          'name' => 'text',
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
      ),
    ),
  ),
  'location' => 
  array(
    0 => 
    array(
      0 => 
      array(
        'param' => 'page',
        'operator' => '==',
        'value' => '16',
      ),
    ),
  ),
  'menu_order' => 20,
  'position' => 'normal',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => '',
  'active' => true,
  'description' => '',
  'show_in_rest' => 0,
);
