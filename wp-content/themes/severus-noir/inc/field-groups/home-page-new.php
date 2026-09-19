<?php
/**
 * Field group: Home page (new)
 *
 * Exported from the database so the definition ships with the theme.
 * Generated — edit the fields here, not in the ACF admin, which shows
 * groups registered in code as read-only.
 *
 * @package Severus_Noir
 */

defined( "ABSPATH" ) || exit;

return array(
  'key' => 'group_6a59c69c97b86',
  'title' => 'Home page (new)',
  'fields' => 
  array(
    0 => 
    array(
      'key' => 'field_6a59c69cdf538',
      'label' => 'Hero',
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
      'placement' => 'left',
      'endpoint' => 0,
    ),
    1 => 
    array(
      'key' => 'field_6a59c7192ff06',
      'label' => 'Hero Title',
      'name' => 'hero_title',
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
    2 => 
    array(
      'key' => 'field_6a59cbe72cd12',
      'label' => 'Hero intro',
      'name' => 'hero_intro',
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
    3 => 
    array(
      'key' => 'field_6a59cc052cd13',
      'label' => 'Hero button (white)',
      'name' => 'hero_btn_primary',
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
      'return_format' => 'array',
    ),
    4 => 
    array(
      'key' => 'field_6a59cc1c2cd14',
      'label' => 'Hero button (green)',
      'name' => 'hero_btn_secondary',
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
      'return_format' => 'array',
    ),
    5 => 
    array(
      'key' => 'field_6a59cda32b4d9',
      'label' => 'Hero video',
      'name' => 'hero_video',
      'aria-label' => '',
      'type' => 'file',
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
      'min_size' => '',
      'max_size' => '',
      'mime_types' => '',
    ),
    6 => 
    array(
      'key' => 'field_6a59cdb32b4da',
      'label' => 'Hero poster',
      'name' => 'hero_poster',
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
    7 => 
    array(
      'key' => 'field_6a59cdcd2b4db',
      'label' => 'Hero video duration',
      'name' => 'hero_video_duration',
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
    8 => 
    array(
      'key' => 'field_6a59cfbc512d5',
      'label' => 'Hero Stats',
      'name' => 'hero_stats',
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
          'key' => 'field_6a59cfdd512d6',
          'label' => 'Stats text',
          'name' => 'stats_text',
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
          'parent_repeater' => 'field_6a59cfbc512d5',
        ),
        1 => 
        array(
          'key' => 'field_6a59cff9512d7',
          'label' => 'Stats icon',
          'name' => 'stats_icon',
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
          'parent_repeater' => 'field_6a59cfbc512d5',
        ),
        2 => 
        array(
          'key' => 'field_6a59d00c512d8',
          'label' => 'Stats link',
          'name' => 'stats_link',
          'aria-label' => '',
          'type' => 'url',
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
          'placeholder' => '',
          'parent_repeater' => 'field_6a59cfbc512d5',
        ),
      ),
    ),
    9 => 
    array(
      'key' => 'field_6a59d420a13d9',
      'label' => 'Sound Familiar?',
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
      'placement' => 'left',
      'endpoint' => 0,
    ),
    10 => 
    array(
      'key' => 'field_6a59d42ea13da',
      'label' => 'Title',
      'name' => 'sound_familiar_title',
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
    11 => 
    array(
      'key' => 'field_6a59d447a13db',
      'label' => 'Intro',
      'name' => 'sound_familiar_intro',
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
    12 => 
    array(
      'key' => 'field_6a59d7695c2a5',
      'label' => 'Sound Familiar Cards',
      'name' => 'sound_familiar_cards',
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
          'key' => 'field_6a59d7845c2a6',
          'label' => 'Card Image',
          'name' => 'card_image',
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
          'parent_repeater' => 'field_6a59d7695c2a5',
        ),
        1 => 
        array(
          'key' => 'field_6a59d7a25c2a7',
          'label' => 'Card Heading',
          'name' => 'card_heading',
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
          'parent_repeater' => 'field_6a59d7695c2a5',
        ),
        2 => 
        array(
          'key' => 'field_6a59d7bb5c2a8',
          'label' => 'Card text',
          'name' => 'card_text',
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
          'parent_repeater' => 'field_6a59d7695c2a5',
        ),
      ),
    ),
    13 => 
    array(
      'key' => 'field_6a59db80cce40',
      'label' => 'Info Section',
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
    14 => 
    array(
      'key' => 'field_6a59db4acce3f',
      'label' => 'Text',
      'name' => 'section_info_title',
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
    15 => 
    array(
      'key' => 'field_6a59e0c29ed83',
      'label' => 'Start With Problem',
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
      'placement' => 'left',
      'endpoint' => 0,
    ),
    16 => 
    array(
      'key' => 'field_6a59e0d99ed84',
      'label' => 'Title',
      'name' => 'swp_title',
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
    17 => 
    array(
      'key' => 'field_6a59e0f69ed85',
      'label' => 'Subtitle',
      'name' => 'swp_subtitle',
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
    18 => 
    array(
      'key' => 'field_6a59e1089ed86',
      'label' => 'Description',
      'name' => 'swp_description',
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
    19 => 
    array(
      'key' => 'field_6a59e25630484',
      'label' => 'List',
      'name' => 'scrollact_items',
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
          'key' => 'field_6a59e2d230485',
          'label' => 'Item Text',
          'name' => 'swp_item_text',
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
          'parent_repeater' => 'field_6a59e25630484',
        ),
      ),
    ),
    20 => 
    array(
      'key' => 'field_6a59e8400b4bf',
      'label' => 'What We Actually Do',
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
    21 => 
    array(
      'key' => 'field_6a59e8630b4c0',
      'label' => 'Title',
      'name' => 'services_title',
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
    22 => 
    array(
      'key' => 'field_6a59e8780b4c1',
      'label' => 'Subtitle',
      'name' => 'services_subtitle',
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
    23 => 
    array(
      'key' => 'field_6a59e89a0b4c2',
      'label' => 'Services List',
      'name' => 'services_list',
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
        0 => 'service',
      ),
      'post_status' => '',
      'taxonomy' => '',
      'filters' => 
      array(
        0 => 'search',
        1 => 'post_type',
        2 => 'taxonomy',
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
    24 => 
    array(
      'key' => 'field_6a59e8e50b4c3',
      'label' => 'Services Button',
      'name' => 'services_button',
      'aria-label' => '',
      'type' => 'link',
      'instructions' => '',
      'required' => false,
      'conditional_logic' => 0,
      'wrapper' => 
      array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'return_format' => 'array',
    ),
    25 => 
    array(
      'key' => 'field_6a59f3e9b5981',
      'label' => 'Where We Create Impact',
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
    26 => 
    array(
      'key' => 'field_6a59f3f1b5982',
      'label' => 'Title',
      'name' => 'impact_title',
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
    27 => 
    array(
      'key' => 'field_6a59f463b5983',
      'label' => 'Subtitle',
      'name' => 'impact_subtitle',
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
    28 => 
    array(
      'key' => 'field_6a59f526c176f',
      'label' => 'Impact items',
      'name' => 'impact_items',
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
          'key' => 'field_6a59f561c1770',
          'label' => 'Image',
          'name' => 'impact_image',
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
          'parent_repeater' => 'field_6a59f526c176f',
        ),
        1 => 
        array(
          'key' => 'field_6a59f575c1771',
          'label' => 'Title',
          'name' => 'impact_title',
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
          'parent_repeater' => 'field_6a59f526c176f',
        ),
        2 => 
        array(
          'key' => 'field_6a59f57ec1772',
          'label' => 'Text',
          'name' => 'impact_text',
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
          'parent_repeater' => 'field_6a59f526c176f',
        ),
        3 => 
        array(
          'key' => 'field_6a59f595c1773',
          'label' => 'Button',
          'name' => 'impact_button',
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
          'return_format' => 'array',
          'parent_repeater' => 'field_6a59f526c176f',
        ),
      ),
    ),
    29 => 
    array(
      'key' => 'field_6a59f9dc1718c',
      'label' => 'Results, Not Promises (Cases)',
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
      'placement' => 'left',
      'endpoint' => 0,
    ),
    30 => 
    array(
      'key' => 'field_6a59f9f51718d',
      'label' => 'Title',
      'name' => 'results_title',
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
    31 => 
    array(
      'key' => 'field_6a59fa061718e',
      'label' => 'Subtitle',
      'name' => 'results_subtitle',
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
    32 => 
    array(
      'key' => 'field_6a59fa3b1718f',
      'label' => 'Results / Cases List',
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
        1 => 'post_type',
        2 => 'taxonomy',
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
    33 => 
    array(
      'key' => 'field_6a5a02486c23a',
      'label' => 'Why Companies Trust Severus',
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
      'placement' => 'left',
      'endpoint' => 0,
    ),
    34 => 
    array(
      'key' => 'field_6a5a02526c23b',
      'label' => 'Logo (background)',
      'name' => 'trust_logo',
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
    ),
    35 => 
    array(
      'key' => 'field_6a5a032fde404',
      'label' => 'Title',
      'name' => 'trust_title',
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
    36 => 
    array(
      'key' => 'field_6a5a0345de405',
      'label' => 'Description',
      'name' => 'trust_descr',
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
    37 => 
    array(
      'key' => 'field_6a5a03c16f1f0',
      'label' => 'Button',
      'name' => 'trust_button',
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
      'return_format' => 'array',
    ),
    38 => 
    array(
      'key' => 'field_6a5a044b71542',
      'label' => 'Trust Stats-lg',
      'name' => 'trust_stats_lg',
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
          'key' => 'field_6a5a049e71543',
          'label' => 'Number',
          'name' => 'stlg_number',
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
          'parent_repeater' => 'field_6a5a044b71542',
        ),
        1 => 
        array(
          'key' => 'field_6a5a04c471544',
          'label' => 'Text',
          'name' => 'stlg_text',
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
          'parent_repeater' => 'field_6a5a044b71542',
        ),
      ),
    ),
    39 => 
    array(
      'key' => 'field_6a5a04f771545',
      'label' => 'Trust Stats-sm',
      'name' => 'trust_stats_sm',
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
          'key' => 'field_6a5a051071546',
          'label' => 'Text',
          'name' => 'stsm_text',
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
          'parent_repeater' => 'field_6a5a04f771545',
        ),
      ),
    ),
    40 => 
    array(
      'key' => 'field_6a5a090ad2fc4',
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
      'placement' => 'left',
      'endpoint' => 0,
    ),
    41 => 
    array(
      'key' => 'field_6a5a093ad2fc5',
      'label' => 'Review List',
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
      'post_status' => '',
      'taxonomy' => '',
      'filters' => 
      array(
        0 => 'search',
        1 => 'post_type',
        2 => 'taxonomy',
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
    42 => 
    array(
      'key' => 'field_6a5a09ead2fc6',
      'label' => 'Title',
      'name' => 'testimonials_title',
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
    43 => 
    array(
      'key' => 'field_6a5a0a0fd2fc7',
      'label' => 'Subtitle',
      'name' => 'testimonials_subheading',
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
    44 => 
    array(
      'key' => 'field_6a5a0a5009ebd',
      'label' => 'Text',
      'name' => 'testimonials_text',
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
    45 => 
    array(
      'key' => 'field_6a5a0a7409ebe',
      'label' => 'Button',
      'name' => 'testimonials_button',
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
      'return_format' => 'array',
    ),
    46 => 
    array(
      'key' => 'field_6a5a0d5670f4f',
      'label' => 'Straight Talk',
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
      'placement' => 'left',
      'endpoint' => 0,
    ),
    47 => 
    array(
      'key' => 'field_6a5a0d6170f50',
      'label' => 'Title',
      'name' => 'products_title',
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
    48 => 
    array(
      'key' => 'field_6a5a0d7a70f51',
      'label' => 'Card List',
      'name' => 'products_list',
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
        1 => 'post_type',
        2 => 'taxonomy',
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
    49 => 
    array(
      'key' => 'field_6a5a0dea70f52',
      'label' => 'Button',
      'name' => 'products_button',
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
      'return_format' => 'array',
    ),
    50 => 
    array(
      'key' => 'field_6a5a0f98b6c5e',
      'label' => 'Our Experts',
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
      'placement' => 'left',
      'endpoint' => 0,
    ),
    51 => 
    array(
      'key' => 'field_6a5a0fa6b6c5f',
      'label' => 'Title',
      'name' => 'experts_title',
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
    52 => 
    array(
      'key' => 'field_6a5a0ff3b6c60',
      'label' => 'Text',
      'name' => 'experts_intro',
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
    53 => 
    array(
      'key' => 'field_6a5a113ac1817',
      'label' => 'Experts list',
      'name' => 'experts_list',
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
          'key' => 'field_6a5a11d6c1818',
          'label' => 'Photo',
          'name' => 'expert_photo',
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
          'parent_repeater' => 'field_6a5a113ac1817',
        ),
        1 => 
        array(
          'key' => 'field_6a5a11e5c1819',
          'label' => 'Quote',
          'name' => 'expert_quote',
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
          'parent_repeater' => 'field_6a5a113ac1817',
        ),
        2 => 
        array(
          'key' => 'field_6a5a11f9c181a',
          'label' => 'Overlay Role',
          'name' => 'expert_overlay_role',
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
          'parent_repeater' => 'field_6a5a113ac1817',
        ),
        3 => 
        array(
          'key' => 'field_6a5a120ec181b',
          'label' => 'Name',
          'name' => 'expert_name',
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
          'parent_repeater' => 'field_6a5a113ac1817',
        ),
        4 => 
        array(
          'key' => 'field_6a5a121ac181c',
          'label' => 'Linkedin link',
          'name' => 'expert_linkedin',
          'aria-label' => '',
          'type' => 'url',
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
          'placeholder' => '',
          'parent_repeater' => 'field_6a5a113ac1817',
        ),
        5 => 
        array(
          'key' => 'field_6a5a125fc181d',
          'label' => 'Role',
          'name' => 'expert_role',
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
          'parent_repeater' => 'field_6a5a113ac1817',
        ),
      ),
    ),
    54 => 
    array(
      'key' => 'field_6a5a1597e06a2',
      'label' => 'Common Questions',
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
      'placement' => 'left',
      'endpoint' => 0,
    ),
    55 => 
    array(
      'key' => 'field_6a5a159fe06a3',
      'label' => 'Title',
      'name' => 'faq_title',
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
    56 => 
    array(
      'key' => 'field_6a5a16aeba718',
      'label' => 'Faq List',
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
          'key' => 'field_6a5a16cdba719',
          'label' => 'Item Title',
          'name' => 'faq_item_title',
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
          'parent_repeater' => 'field_6a5a16aeba718',
        ),
        1 => 
        array(
          'key' => 'field_6a5a16f2ba71a',
          'label' => 'Item Description',
          'name' => 'faq_item_descr',
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
          'parent_repeater' => 'field_6a5a16aeba718',
        ),
      ),
    ),
    57 => 
    array(
      'key' => 'field_6a5a18d17faf4',
      'label' => 'Contact Section',
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
      'placement' => 'left',
      'endpoint' => 0,
    ),
    58 => 
    array(
      'key' => 'field_6a5a18e27faf5',
      'label' => 'Logo',
      'name' => 'contact_logo',
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
    59 => 
    array(
      'key' => 'field_6a5a18f67faf6',
      'label' => 'Title',
      'name' => 'contact_title',
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
    60 => 
    array(
      'key' => 'field_6a5a190b7faf7',
      'label' => 'Text',
      'name' => 'contact_text',
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
  ),
  'location' => 
  array(
    0 => 
    array(
      0 => 
      array(
        'param' => 'page_type',
        'operator' => '==',
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
