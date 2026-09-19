<?php
/**
 * Field group: Pages About Us
 *
 * Exported from the database so the definition ships with the theme.
 * Generated — edit the fields here, not in the ACF admin, which shows
 * groups registered in code as read-only.
 *
 * @package Severus_Noir
 */

defined( "ABSPATH" ) || exit;

return array(
  'key' => 'group_6620d0561eb88',
  'title' => 'Pages About Us',
  'fields' => 
  array(
    0 => 
    array(
      'key' => 'field_6620d0589dd45',
      'label' => 'Top content',
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
      'key' => 'field_6620d0b29dd46',
      'label' => 'Heading H1',
      'name' => 'heading_h1',
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
      'key' => 'field_6620d0e89dd47',
      'label' => 'Heading H2',
      'name' => 'heading_h2',
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
      'key' => 'field_6620d0f49dd48',
      'label' => 'Description',
      'name' => 'description',
      'aria-label' => '',
      'type' => 'wysiwyg',
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
      'tabs' => 'all',
      'toolbar' => 'full',
      'media_upload' => 1,
      'delay' => 0,
    ),
    4 => 
    array(
      'key' => 'field_6620e3e63ea39',
      'label' => 'Services list',
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
    5 => 
    array(
      'key' => 'field_6620e4063ea3a',
      'label' => 'Services section',
      'name' => 'services-section',
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
          'key' => 'field_6620e50cd553e',
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
          'key' => 'field_6620e51bd553f',
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
          'key' => 'field_6620e42f3ea3b',
          'label' => 'Services list',
          'name' => 'services_list',
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
          'layout' => 'row',
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
              'key' => 'field_6620e4483ea3c',
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
              'parent_repeater' => 'field_6620e42f3ea3b',
            ),
            1 => 
            array(
              'key' => 'field_6620e4593ea3d',
              'label' => 'Description',
              'name' => 'description',
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
              'parent_repeater' => 'field_6620e42f3ea3b',
            ),
            2 => 
            array(
              'key' => 'field_6620e4643ea3e',
              'label' => 'Link',
              'name' => 'link',
              'aria-label' => '',
              'type' => 'link',
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
              'parent_repeater' => 'field_6620e42f3ea3b',
            ),
            3 => 
            array(
              'key' => 'field_6620e4733ea3f',
              'label' => 'Image',
              'name' => 'image',
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
              'return_format' => 'array',
              'library' => 'all',
              'min_width' => '',
              'min_height' => '',
              'min_size' => '',
              'max_width' => '',
              'max_height' => '',
              'max_size' => '',
              'mime_types' => '',
              'preview_size' => 'thumbnail',
              'parent_repeater' => 'field_6620e42f3ea3b',
            ),
          ),
        ),
      ),
    ),
    6 => 
    array(
      'key' => 'field_6620ef8fa4c54',
      'label' => 'FAQ',
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
    7 => 
    array(
      'key' => 'field_6620efa3a4c55',
      'label' => 'FAQ-section',
      'name' => 'faq-section',
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
          'key' => 'field_6620efd7a4c56',
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
        1 => 
        array(
          'key' => 'field_6620efe2a4c57',
          'label' => 'Description',
          'name' => 'description',
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
          'key' => 'field_6620efeda4c58',
          'label' => 'FAQ list',
          'name' => 'faq_list',
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
          'layout' => 'row',
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
              'key' => 'field_6620f021a4c59',
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
              'parent_repeater' => 'field_6620efeda4c58',
            ),
            1 => 
            array(
              'key' => 'field_6620f02ba4c5a',
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
              'parent_repeater' => 'field_6620efeda4c58',
            ),
          ),
        ),
      ),
    ),
    8 => 
    array(
      'key' => 'field_6620fc0451769',
      'label' => 'Other',
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
    9 => 
    array(
      'key' => 'field_6620fbda51768',
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
  'location' => 
  array(
    0 => 
    array(
      0 => 
      array(
        'param' => 'page',
        'operator' => '==',
        'value' => '14',
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
