<?php
/**
 * Field group: Industries (New)
 *
 * Exported from the database so the definition ships with the theme.
 * Generated — edit the fields here, not in the ACF admin, which shows
 * groups registered in code as read-only.
 *
 * @package Severus_Noir
 */

defined( "ABSPATH" ) || exit;

return array(
  'key' => 'group_6a6a0829920de',
  'title' => 'Industries (New)',
  'fields' => 
  array(
    0 => 
    array(
      'key' => 'field_6a6a08293df68',
      'label' => 'Text',
      'name' => 'industries_text',
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
    1 => 
    array(
      'key' => 'field_6a6a0a388f99d',
      'label' => 'Industries section title',
      'name' => 'industries_section_title',
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
        'param' => 'page_template',
        'operator' => '==',
        'value' => 'page-industries.php',
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
