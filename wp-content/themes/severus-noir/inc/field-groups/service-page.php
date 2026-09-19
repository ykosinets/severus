<?php
/**
 * Field group: Service page
 *
 * Exported from the database so the definition ships with the theme.
 * Generated — edit the fields here, not in the ACF admin, which shows
 * groups registered in code as read-only.
 *
 * @package Severus_Noir
 */

defined( "ABSPATH" ) || exit;

return array(
  'key' => 'group_6623c9ff90600',
  'title' => 'Service page',
  'fields' => 
  array(
    0 => 
    array(
      'key' => 'field_6623d0a06b620',
      'label' => 'Content',
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
      'key' => 'field_6623d0bb6b621',
      'label' => 'Title (h1)',
      'name' => 'service_title',
      'aria-label' => '',
      'type' => 'text',
      'instructions' => '',
      'required' => 1,
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
      'key' => 'field_6623d0e26b622',
      'label' => 'Short description',
      'name' => 'service_short_description',
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
    3 => 
    array(
      'key' => 'field_6623d3bb46dfb',
      'label' => 'Service content',
      'name' => 'service_content',
      'aria-label' => '',
      'type' => 'flexible_content',
      'instructions' => '',
      'required' => 0,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'layouts' => 
      array(
        'layout_6623e76a27209' => 
        array(
          'key' => 'layout_6623e76a27209',
          'name' => 'content_with_image',
          'label' => 'Content with image',
          'display' => 'row',
          'sub_fields' => 
          array(
            0 => 
            array(
              'key' => 'field_6623e7a546dfe',
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
            1 => 
            array(
              'key' => 'field_6623e7c946dff',
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
              'preview_size' => 'medium',
            ),
            2 => 
            array(
              'key' => 'field_6623e87046e02',
              'label' => 'Image aligment',
              'name' => 'image_aligment',
              'aria-label' => '',
              'type' => 'radio',
              'instructions' => '',
              'required' => 0,
              'conditional_logic' => 0,
              'wrapper' => 
              array(
                'width' => '',
                'class' => '',
                'id' => '',
              ),
              'choices' => 
              array(
                'left' => 'Left',
                'right' => 'Right',
              ),
              'default_value' => 'left',
              'return_format' => 'value',
              'allow_null' => 0,
              'other_choice' => 0,
              'layout' => 'vertical',
              'save_other_choice' => 0,
            ),
            3 => 
            array(
              'key' => 'field_6623e7ed46e00',
              'label' => 'Show logo',
              'name' => 'show_logo',
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
          'min' => '',
          'max' => '',
        ),
        'layout_6623e77546dfc' => 
        array(
          'key' => 'layout_6623e77546dfc',
          'name' => 'content_carousel_block',
          'label' => 'Content carousel block',
          'display' => 'row',
          'sub_fields' => 
          array(
            0 => 
            array(
              'key' => 'field_6623f8b1c8bbe',
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
              'key' => 'field_6623e8da46e03',
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
              'key' => 'field_6623e90646e04',
              'label' => 'Content list',
              'name' => 'content_list',
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
              'min' => 0,
              'max' => 0,
              'collapsed' => '',
              'button_label' => 'Add Row',
              'rows_per_page' => 20,
              'sub_fields' => 
              array(
                0 => 
                array(
                  'key' => 'field_6623e92546e05',
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
                  'parent_repeater' => 'field_6623e90646e04',
                ),
                1 => 
                array(
                  'key' => 'field_6623e94c46e06',
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
                  'parent_repeater' => 'field_6623e90646e04',
                ),
                2 => 
                array(
                  'key' => 'field_6623e96546e07',
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
                  'preview_size' => 'medium',
                  'parent_repeater' => 'field_6623e90646e04',
                ),
              ),
            ),
          ),
          'min' => '',
          'max' => '',
        ),
      ),
      'min' => '',
      'max' => '',
      'button_label' => 'Add Row',
    ),
    4 => 
    array(
      'key' => 'field_6623ca003b070',
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
    5 => 
    array(
      'key' => 'field_6623ca3e3b071',
      'label' => 'Service Faq list',
      'name' => 'service_faq_list',
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
          'key' => 'field_6623ca993b073',
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
          'parent_repeater' => 'field_6623ca3e3b071',
        ),
        1 => 
        array(
          'key' => 'field_6623ca8d3b072',
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
          'parent_repeater' => 'field_6623ca3e3b071',
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
        'value' => 'service',
      ),
    ),
  ),
  'menu_order' => 0,
  'position' => 'normal',
  'style' => 'default',
  'label_placement' => 'top',
  'instruction_placement' => 'label',
  'hide_on_screen' => '',
  'active' => false,
  'description' => '',
  'show_in_rest' => 0,
);
