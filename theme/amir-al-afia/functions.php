<?php
/**
 * Amir Al Afia — theme bootstrap.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

define( 'AAA_VERSION', '1.1.0' );
define( 'AAA_DIR', get_template_directory() );
define( 'AAA_URI', get_template_directory_uri() );

require_once AAA_DIR . '/inc/helpers.php';
require_once AAA_DIR . '/inc/icons.php';
require_once AAA_DIR . '/inc/nav-fallback.php';
require_once AAA_DIR . '/inc/post-type-property.php';
require_once AAA_DIR . '/inc/meta-property.php';
require_once AAA_DIR . '/inc/post-type-agent.php';
require_once AAA_DIR . '/inc/post-type-attraction.php';
require_once AAA_DIR . '/inc/post-type-lead.php';
require_once AAA_DIR . '/inc/customizer.php';
require_once AAA_DIR . '/inc/lead-form.php';
require_once AAA_DIR . '/inc/property-query.php';
require_once AAA_DIR . '/inc/seo.php';
require_once AAA_DIR . '/inc/demo-content.php';

/**
 * Theme supports and registered menus.
 */
function aaa_setup(): void {
	load_theme_textdomain( 'amir-al-afia', AAA_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 64,
		'width'       => 179,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	// Card thumbnails are rendered at 320x180 CSS px; 2x for retina.
	add_image_size( 'aaa-card', 640, 360, true );
	add_image_size( 'aaa-collage', 600, 600, true );
	add_image_size( 'aaa-wide', 1400, 900, true );

	register_nav_menus( array(
		'primary' => __( 'Primary menu', 'amir-al-afia' ),
		'footer'  => __( 'Footer menu', 'amir-al-afia' ),
	) );
}
add_action( 'after_setup_theme', 'aaa_setup' );

/**
 * Front-end assets. One stylesheet, one deferred script, self-hosted fonts.
 */
function aaa_assets(): void {
	wp_enqueue_style( 'aaa-fonts', AAA_URI . '/assets/fonts/fonts.css', array(), AAA_VERSION );
	wp_enqueue_style( 'aaa-main', AAA_URI . '/assets/css/main.css', array( 'aaa-fonts' ), AAA_VERSION );

	wp_enqueue_script( 'aaa-main', AAA_URI . '/assets/js/main.js', array(), AAA_VERSION, array( 'strategy' => 'defer' ) );
	wp_localize_script( 'aaa-main', 'aaaData', array(
		'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
		'filterNonce'  => wp_create_nonce( 'aaa_filter_properties' ),
		'i18n'         => array(
			'noResults' => __( 'No properties match these filters yet.', 'amir-al-afia' ),
			'loading'   => __( 'Loading…', 'amir-al-afia' ),
		),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'aaa_assets' );

/**
 * Preload the two font faces that paint above the fold, so the hero heading
 * does not swap after first paint.
 */
function aaa_preload_fonts(): void {
	foreach ( array( 'barlow-condensed-900', 'barlow-400' ) as $face ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( AAA_URI . '/assets/fonts/' . $face . '.woff2' )
		);
	}
}
add_action( 'wp_head', 'aaa_preload_fonts', 1 );

/**
 * The site is a bespoke front end — core block CSS and emoji scripts are dead weight.
 */
function aaa_trim_head(): void {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
}
add_action( 'init', 'aaa_trim_head' );

/**
 * Drop block-library CSS on the front end. The theme renders no blocks there;
 * the editor keeps its own copy.
 */
function aaa_dequeue_block_css(): void {
	if ( is_admin() ) {
		return;
	}
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'aaa_dequeue_block_css', 100 );

/**
 * Favicon built from the brand mark, so no uploaded icon is required.
 */
function aaa_favicon(): void {
	if ( has_site_icon() ) {
		return;
	}
	$svg = 'data:image/svg+xml,' . rawurlencode(
		'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 63 64">'
		. '<path d="M25.2933 50.8342C25.1891 51.0025 24.9443 51.0025 24.84 50.8342L1.92364 13.8556C1.81706 13.6836 1.98741 13.4737 2.17765 13.5426L19.4533 19.8019C19.6678 19.8796 19.692 20.1732 19.4932 20.2851L16.2283 22.1216C15.9601 22.2725 15.8748 22.6183 16.0422 22.8765L24.6191 36.1095C24.8293 36.4338 25.304 36.4338 25.5142 36.1095L34.0911 22.8765C34.2585 22.6183 34.1732 22.2725 33.9051 22.1216L30.6401 20.2851C30.4413 20.1732 30.4656 19.8796 30.68 19.8019L47.9557 13.5426C48.1459 13.4737 48.3163 13.6836 48.2097 13.8556L25.2933 50.8342Z" fill="#21ABFC"/>'
		. '<path d="M38.6267 50.8342C38.5224 51.0025 38.2776 51.0025 38.1733 50.8342L15.257 13.8556C15.1504 13.6836 15.3208 13.4737 15.511 13.5426L32.7867 19.8019C33.0011 19.8796 33.0254 20.1732 32.8266 20.2851L29.5616 22.1216C29.2934 22.2725 29.2082 22.6183 29.3755 22.8765L37.9525 36.1095C38.1627 36.4338 38.6373 36.4338 38.8476 36.1095L47.4245 22.8765C47.5918 22.6183 47.5066 22.2725 47.2384 22.1216L43.9735 20.2851C43.7746 20.1732 43.7989 19.8796 44.0133 19.8019L61.289 13.5426C61.4793 13.4737 61.6496 13.6836 61.543 13.8556L38.6267 50.8342Z" fill="#4ACBF0"/>'
		. '</svg>'
	);
	printf( '<link rel="icon" type="image/svg+xml" href="%s">' . "\n", esc_attr( $svg ) );
}
add_action( 'wp_head', 'aaa_favicon', 2 );

/**
 * Body classes used by the stylesheet to branch layout.
 */
function aaa_body_class( array $classes ): array {
	if ( is_front_page() ) {
		$classes[] = 'is-landing';
	}
	return $classes;
}
add_filter( 'body_class', 'aaa_body_class' );
