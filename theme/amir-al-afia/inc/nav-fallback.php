<?php
/**
 * Navigation fallbacks.
 *
 * The mockup's menu had two entries ("Projects" and "Trading") pointing at the
 * same anchor. The fallback below gives every entry a distinct destination that
 * exists on the page; assigning a real menu in Appearance > Menus overrides it.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * The default primary menu, used until a menu is assigned in the admin.
 *
 * @param array<string, mixed> $args wp_nav_menu() arguments.
 */
function aaa_default_primary_menu( $args = array() ): void {
	$class = $args['menu_class'] ?? 'nav-links';
	$home  = is_front_page() ? '' : home_url( '/' );

	// Properties and the Oman guide are real archives now, so they get real
	// URLs; the rest stay as anchors into the landing page.
	$items = array(
		array( get_post_type_archive_link( 'property' ) ?: $home . '#properties', __( 'Properties', 'amir-al-afia' ) ),
		array( $home . '#investors', __( 'Why Oman', 'amir-al-afia' ) ),
		array( $home . '#team', __( 'Our Team', 'amir-al-afia' ) ),
		array( get_post_type_archive_link( 'attraction' ) ?: $home . '#attractions', __( 'Oman', 'amir-al-afia' ) ),
		array( $home . '#contact', __( 'Contact', 'amir-al-afia' ) ),
	);

	printf( '<ul class="%s">', esc_attr( $class ) );
	foreach ( $items as $item ) {
		printf(
			'<li><a href="%1$s">%2$s</a></li>',
			esc_url( (string) $item[0] ),
			esc_html( $item[1] )
		);
	}
	echo '</ul>';
}

/**
 * The default footer menu.
 *
 * @param array<string, mixed> $args wp_nav_menu() arguments.
 */
function aaa_default_footer_menu( $args = array() ): void {
	$class = $args['menu_class'] ?? 'footer-nav-links';
	$home  = is_front_page() ? '' : home_url( '/' );

	$items = array(
		array( get_post_type_archive_link( 'property' ) ?: $home . '#properties', __( 'Properties', 'amir-al-afia' ) ),
		array( get_post_type_archive_link( 'attraction' ) ?: $home . '#attractions', __( 'Explore Oman', 'amir-al-afia' ) ),
		array( $home . '#team', __( 'About us', 'amir-al-afia' ) ),
		array( $home . '#contact', __( 'Contact us', 'amir-al-afia' ) ),
	);

	printf( '<ul class="%s">', esc_attr( $class ) );
	foreach ( $items as $item ) {
		printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( (string) $item[0] ), esc_html( $item[1] ) );
	}
	echo '</ul>';
}

/**
 * Give menu anchors the classes the stylesheet expects.
 *
 * @param string[] $classes Existing classes.
 * @return string[]
 */
function aaa_nav_link_classes( array $classes ): array {
	return $classes;
}
add_filter( 'nav_menu_css_class', 'aaa_nav_link_classes' );
