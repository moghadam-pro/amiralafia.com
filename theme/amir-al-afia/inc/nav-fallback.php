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
		$url     = (string) $item[0];
		$section = aaa_nav_section( $url );

		printf(
			'<li><a href="%1$s"%3$s>%2$s</a></li>',
			esc_url( $url ),
			esc_html( $item[1] ),
			$section ? sprintf( ' data-section="%s"', esc_attr( $section ) ) : ''
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
 * The landing-page section a menu URL leads to, if any.
 *
 * The scroll spy needs to know which section belongs to which link, and the
 * href alone is not enough to tell it: two of the entries point at real
 * archives (`/properties/`, `/oman/`) rather than at `#properties` and
 * `#attractions`, so matching on the fragment would leave those two permanently
 * unlit while the visitor scrolls straight through their sections.
 *
 * @param string $url Menu item URL.
 * @return string Section element id, or '' when the link leads elsewhere.
 */
function aaa_nav_section( string $url ): string {
	$sections = array( 'home', 'properties', 'investors', 'team', 'contact', 'attractions' );

	$fragment = (string) wp_parse_url( $url, PHP_URL_FRAGMENT );
	if ( in_array( $fragment, $sections, true ) ) {
		return $fragment;
	}

	// An archive link still scrolls past that section's teaser on the landing
	// page, so it lights up there too.
	$archives = array(
		'properties'  => get_post_type_archive_link( 'property' ),
		'attractions' => get_post_type_archive_link( 'attraction' ),
	);

	$path = untrailingslashit( (string) wp_parse_url( $url, PHP_URL_PATH ) );

	foreach ( $archives as $section => $archive ) {
		if ( $archive && untrailingslashit( (string) wp_parse_url( $archive, PHP_URL_PATH ) ) === $path ) {
			return $section;
		}
	}

	return '';
}

/**
 * Tag menu anchors with the section they lead to, for the scroll spy.
 *
 * @param array<string, string> $atts Anchor attributes.
 * @param object                $item Menu item.
 * @return array<string, string>
 */
function aaa_nav_link_attributes( array $atts, $item = null ): array {
	$url     = isset( $atts['href'] ) ? (string) $atts['href'] : '';
	$section = $url ? aaa_nav_section( $url ) : '';

	if ( $section ) {
		$atts['data-section'] = $section;
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'aaa_nav_link_attributes', 10, 2 );
