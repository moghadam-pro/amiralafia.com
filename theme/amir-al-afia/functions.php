<?php
/**
 * Amir Al Afia — theme bootstrap.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

define( 'AAA_VERSION', '1.6.1' );
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
require_once AAA_DIR . '/inc/typography.php';
require_once AAA_DIR . '/inc/svg-support.php';
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
	// Open Graph / Twitter card. 1.91:1 is what Facebook, WhatsApp, Telegram
	// and X all crop to; giving them exactly that avoids a re-crop.
	add_image_size( 'aaa-og', 1200, 630, true );

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
	// Only worth doing while the theme's own faces are the ones in use; a
	// swapped-in font is loaded from its own stylesheet.
	if ( 'default' !== get_theme_mod( 'aaa_font_heading_source', 'default' )
		|| 'default' !== get_theme_mod( 'aaa_font_body_source', 'default' ) ) {
		return;
	}

	foreach ( array( 'playfair-display-sc-700', 'poppins-400' ) as $face ) {
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
		. '<path d="M38.1733 13.1658C38.2776 12.9975 38.5224 12.9975 38.6267 13.1658L61.543 50.1444C61.6496 50.3164 61.4793 50.5263 61.289 50.4574L44.0133 44.1981C43.7989 44.1204 43.7746 43.8268 43.9735 43.7149L47.2384 41.8784C47.5066 41.7276 47.5918 41.3817 47.4245 41.1235L38.8476 27.8905C38.6373 27.5662 38.1627 27.5662 37.9525 27.8905L29.3755 41.1235C29.2082 41.3817 29.2934 41.7275 29.5616 41.8784L32.8266 43.7149C33.0254 43.8268 33.0011 44.1204 32.7867 44.1981L15.511 50.4574C15.3207 50.5263 15.1504 50.3164 15.257 50.1444L38.1733 13.1658Z" fill="#018ED5"/>'
		. '<path d="M24.84 13.1658C24.9443 12.9975 25.1891 12.9975 25.2933 13.1658L48.2097 50.1444C48.3163 50.3164 48.1459 50.5263 47.9557 50.4574L30.68 44.1981C30.4656 44.1204 30.4413 43.8268 30.6401 43.7149L33.9051 41.8784C34.1733 41.7276 34.2585 41.3817 34.0912 41.1235L25.5142 27.8905C25.304 27.5662 24.8293 27.5662 24.6191 27.8905L16.0422 41.1235C15.8748 41.3817 15.9601 41.7275 16.2283 41.8784L19.4932 43.7149C19.692 43.8268 19.6678 44.1204 19.4533 44.1981L2.17766 50.4574C1.98742 50.5263 1.81706 50.3164 1.92365 50.1444L24.84 13.1658Z" fill="#38B6FF"/>'
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

/**
 * Give the front page a descriptive title.
 *
 * With no tagline set, WordPress titles the home page with the site name
 * alone — which is what gets shared into WhatsApp and indexed by Google. This
 * appends the tagline, falling back to a description of the business so the
 * title is never just the name.
 *
 * @param array<string, string> $parts Title parts.
 * @return array<string, string>
 */
function aaa_document_title_parts( array $parts ): array {
	if ( ! is_front_page() ) {
		return $parts;
	}

	$tagline = trim( (string) get_bloginfo( 'description', 'display' ) );

	// WordPress ships this as the default tagline; it is not a description.
	if ( '' === $tagline || 'Just another WordPress site' === $tagline ) {
		$tagline = __( 'Property for Sale and Rent in Muscat, Oman', 'amir-al-afia' );
	}

	$parts['tagline'] = $tagline;

	return $parts;
}

/**
 * Title the Oman archive the way the page heading does.
 *
 * The post type's label is the short "Attractions", which reads oddly as a
 * browser tab and in a shared link.
 *
 * @param string $title Archive title.
 * @return string
 */
function aaa_archive_title( string $title ): string {
	if ( is_post_type_archive( 'attraction' ) ) {
		return aaa_option( 'aaa_attr_title' );
	}
	return $title;
}
add_filter( 'post_type_archive_title', 'aaa_archive_title' );
add_filter( 'document_title_parts', 'aaa_document_title_parts' );
