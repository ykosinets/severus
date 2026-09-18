<?php
/**
 * The home page. Each section is a component that reads its own fields, so the
 * order here is the whole page.
 *
 * @package Severus_Noir
 */

defined( 'ABSPATH' ) || exit;

get_header();

severus_component( 'hero' );
severus_component( 'familiar' );
severus_component( 'turn' );
severus_component( 'services' );
severus_component( 'method' );
severus_component( 'impact' );
severus_component( 'results' );
severus_component( 'trust' );
severus_component( 'voices' );
severus_component( 'journal' );
severus_component( 'people' );
severus_component( 'faq' );
severus_component( 'talk' );

get_footer();
