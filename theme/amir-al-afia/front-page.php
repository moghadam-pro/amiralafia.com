<?php
/**
 * The landing page.
 *
 * Each section of the design is its own template part, so sections can be
 * reordered, dropped, or reused on other templates without touching the rest.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/home/hero' );
get_template_part( 'template-parts/home/properties' );
get_template_part( 'template-parts/home/investors' );
get_template_part( 'template-parts/home/team' );
get_template_part( 'template-parts/home/contact' );
get_template_part( 'template-parts/home/attractions' );

get_footer();
