<?php
/**
 * Field group: Cases page
 *
 * Exported from the database so the definition ships with the theme.
 * Generated — edit the fields here, not in the ACF admin, which shows
 * groups registered in code as read-only.
 *
 * @package Severus_Noir
 */

defined( "ABSPATH" ) || exit;

return array(
  'key' => 'group_66178661ca047',
  'title' => 'Cases page',
  'fields' => 
  array(
    0 => 
    array(
      'key' => 'field_662380a12b74b',
      'label' => 'Case info',
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
      'key' => 'field_661786629fd77',
      'label' => 'Cases Fields',
      'name' => 'cases_fields',
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
          'key' => 'field_6617870b9fd7a',
          'label' => 'Short description',
          'name' => 'short_description',
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
        1 => 
        array(
          'key' => 'field_661786b79fd78',
          'label' => 'Advantages list',
          'name' => 'advantages_list',
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
          'max' => 3,
          'collapsed' => '',
          'button_label' => 'Add Row',
          'rows_per_page' => 20,
          'sub_fields' => 
          array(
            0 => 
            array(
              'key' => 'field_661786db9fd79',
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
              'parent_repeater' => 'field_661786b79fd78',
            ),
          ),
        ),
        2 => 
        array(
          'key' => 'field_661787469fd7b',
          'label' => 'Case info',
          'name' => 'case_info',
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
              'key' => 'field_661787a79fd7c',
              'label' => 'Client',
              'name' => 'client',
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
              'key' => 'field_661787b59fd7d',
              'label' => 'Industry',
              'name' => 'industry',
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
              'key' => 'field_661787c69fd7e',
              'label' => 'Country',
              'name' => 'country',
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
        ),
      ),
    ),
    2 => 
    array(
      'key' => 'field_662380bb2b74c',
      'label' => 'Testimonials',
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
    3 => 
    array(
      'key' => 'field_6623818fa36de',
      'label' => 'Case testimonial',
      'name' => 'case_testimonial',
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
          'key' => 'field_662381c9a36df',
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
        1 => 
        array(
          'key' => 'field_662381d3a36e0',
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
          'key' => 'field_662381dea36e1',
          'label' => 'Testimonial',
          'name' => 'testimonial',
          'aria-label' => '',
          'type' => 'post_object',
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
          'post_status' => '',
          'taxonomy' => '',
          'return_format' => 'object',
          'multiple' => 0,
          'allow_null' => 0,
          'bidirectional' => 0,
          'ui' => 1,
          'bidirectional_target' => 
          array(
          ),
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
        'value' => 'case',
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
