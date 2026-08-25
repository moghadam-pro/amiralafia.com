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

	$items = array(
		'#properties' => __( 'Properties', 'amir-al-afia' ),
		'#investors'  => __( 'Why Oman', 'amir-al-afia' ),
		'#team'       => __( 'Our Team', 'amir-al-afia' ),
		'#attractions' => __( 'Oman', 'amir-al-afia' ),
		'#contact'    => __( 'Contact', 'amir-al-afia' ),
	);

	printf( '<ul class="%s">', esc_attr( $class ) );
	foreach ( $items as $anchor => $label ) {
		printf(
			'<li><a href="%1$s">%2$s</a></li>',
			esc_url( $home . $anchor ),
			esc_html( $label )
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
		get_post_type_archive_link( 'property' ) ?: $home . '#properties' => __( 'Properties', 'amir-al-afia' ),
		$home . '#team'    => __( 'About us', 'amir-al-afia' ),
		$home . '#investors' => __( 'Why Oman', 'amir-al-afia' ),
		$home . '#contact' => __( 'Contact us', 'amir-al-afia' ),
	);

	printf( '<ul class="%s">', esc_attr( $class ) );
	foreach ( $items as $url => $label ) {
		printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $url ), esc_html( $label ) );
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
