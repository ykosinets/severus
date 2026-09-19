<?php
/**
 * Field group: FAQ
 *
 * Exported from the database so the definition ships with the theme.
 * Generated — edit the fields here, not in the ACF admin, which shows
 * groups registered in code as read-only.
 *
 * @package Severus_Noir
 */

defined( "ABSPATH" ) || exit;

return array(
  'key' => 'group_69f1db89c5776',
  'title' => 'FAQ',
  'fields' => 
  array(
    0 => 
    array(
      'key' => 'field_69f1db8ab2ffd',
      'label' => 'Common Questions',
      'name' => 'faq',
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
          'key' => 'field_69f1dd4ab3000',
          'label' => 'Question',
          'name' => 'question',
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
          'parent_repeater' => 'field_69f1db8ab2ffd',
        ),
        1 => 
        array(
          'key' => 'field_69f1dd6cb3001',
          'label' => 'Answer',
          'name' => 'answer',
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
          'parent_repeater' => 'field_69f1db8ab2ffd',
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
        'value' => '12',
      ),
      1 => 
      array(
        'param' => 'page_type',
        'operator' => '!=',
        'value' => 'front_page',
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
