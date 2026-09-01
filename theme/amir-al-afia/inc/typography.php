<?php
/**
 * Swappable heading and body fonts.
 *
 * The theme ships Playfair Display SC for headings and Poppins for body text,
 * self-hosted. An
 * administrator can replace either without touching code, from
 * Customizer > Amir Al Afia > Typography, in one of two ways:
 *
 *   1. Install a font at Appearance > Fonts (the WordPress Font Library) and
 *      pick it from the dropdown. This is the better route: the Font Library
 *      downloads the files onto this server, so the site keeps working if
 *      Google is blocked and no visitor IP is handed to a third party.
 *
 *   2. Paste a Google Fonts stylesheet URL. Convenient, but it adds a
 *      render-blocking request to a third-party host on every page load.
 *
 * Why this file has to exist at all: the Font Library installs fonts and
 * exposes them to the block editor, but it cannot restyle a classic theme.
 * Our stylesheet sets `--display` and `--body` explicitly, so something has to
 * translate the admin's choice into those two custom properties. That is all
 * this does — it prints one `:root` rule after the stylesheet.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hosts a font stylesheet may be loaded from.
 *
 * Anything else is refused. The value ends up in a `<link href>`, so an
 * unchecked URL here would be a way to inject a third-party stylesheet into
 * every page.
 *
 * @return string[]
 */
function aaa_font_hosts(): array {
	return array(
		'fonts.googleapis.com',
		'fonts.bunny.net',   // GDPR-friendly drop-in mirror of the Google API.
		'use.typekit.net',
		wp_parse_url( home_url(), PHP_URL_HOST ),
	);
}

/**
 * The theme's own defaults, used when nothing has been chosen.
 *
 * @return array<string, array<string, string>>
 */
function aaa_font_defaults(): array {
	return array(
		'heading' => array(
			'family' => 'Playfair Display SC',
			'stack'  => "'Playfair Display SC', Georgia, serif",
		),
		'body'    => array(
			'family' => 'Poppins',
			'stack'  => "'Poppins', system-ui, -apple-system, 'Segoe UI', sans-serif",
		),
	);
}

/**
 * Font families installed through Appearance > Fonts.
 *
 * The Font Library stores each family as a `wp_font_family` post whose content
 * is the theme.json font-family fragment.
 *
 * @return array<string, string> Family name keyed by itself.
 */
function aaa_installed_fonts(): array {
	if ( ! post_type_exists( 'wp_font_family' ) ) {
		return array();
	}

	$families = get_posts(
		array(
			'post_type'      => 'wp_font_family',
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);

	$out = array();

	foreach ( $families as $family ) {
		$data = json_decode( $family->post_content, true );
		$name = '';

		if ( is_array( $data ) ) {
			$name = (string) ( $data['name'] ?? $data['fontFamily'] ?? '' );
		}
		$name = $name ?: (string) $family->post_title;
		$name = trim( explode( ',', $name )[0], " \t\n\r\0\x0B\"'" );

		if ( '' !== $name ) {
			$out[ $name ] = $name;
		}
	}

	return $out;
}

/**
 * Pull the first family name out of a Google Fonts stylesheet URL.
 *
 * Handles both `css?family=Foo+Bar:400` and `css2?family=Foo+Bar:wght@400..700`.
 *
 * @param string $url Stylesheet URL.
 */
function aaa_family_from_url( string $url ): string {
	$query = (string) wp_parse_url( $url, PHP_URL_QUERY );
	if ( '' === $query ) {
		return '';
	}

	parse_str( $query, $args );
	$family = $args['family'] ?? '';

	// css2 allows repeated family params; parse_str keeps only the last, which
	// is fine — any of them is a valid answer for a single-family choice.
	if ( is_array( $family ) ) {
		$family = reset( $family );
	}

	$family = explode( ':', (string) $family )[0];
	$family = str_replace( '+', ' ', $family );

	return sanitize_text_field( trim( $family ) );
}

/**
 * Resolve one role to a family name, a CSS stack and a stylesheet URL.
 *
 * @param string $role 'heading' or 'body'.
 * @return array{family:string, stack:string, url:string}
 */
function aaa_font_choice( string $role ): array {
	$defaults = aaa_font_defaults();
	$fallback = 'heading' === $role ? 'Georgia, serif' : "system-ui, -apple-system, 'Segoe UI', sans-serif";

	$source = (string) get_theme_mod( 'aaa_font_' . $role . '_source', 'default' );

	// A family installed through Appearance > Fonts. Its @font-face rules are
	// already printed by WordPress, so only the stack is needed.
	if ( 'library' === $source ) {
		$family = (string) get_theme_mod( 'aaa_font_' . $role . '_library', '' );
		if ( '' !== $family && isset( aaa_installed_fonts()[ $family ] ) ) {
			return array(
				'family' => $family,
				'stack'  => sprintf( "'%s', %s", $family, $fallback ),
				'url'    => '',
			);
		}
	}

	if ( 'url' === $source ) {
		$url  = (string) get_theme_mod( 'aaa_font_' . $role . '_url', '' );
		$host = (string) wp_parse_url( $url, PHP_URL_HOST );

		if ( $url && in_array( $host, aaa_font_hosts(), true ) ) {
			$family = (string) get_theme_mod( 'aaa_font_' . $role . '_family', '' );
			$family = $family ?: aaa_family_from_url( $url );

			if ( '' !== $family ) {
				return array(
					'family' => $family,
					'stack'  => sprintf( "'%s', %s", $family, $fallback ),
					'url'    => $url,
				);
			}
		}
	}

	return array(
		'family' => $defaults[ $role ]['family'],
		'stack'  => $defaults[ $role ]['stack'],
		'url'    => '',
	);
}

/**
 * Load any remote stylesheets the choices need, then override the two custom
 * properties the theme's CSS reads.
 */
function aaa_font_overrides(): void {
	$heading = aaa_font_choice( 'heading' );
	$body    = aaa_font_choice( 'body' );

	$urls = array_values( array_unique( array_filter( array( $heading['url'], $body['url'] ) ) ) );

	if ( $urls ) {
		// Both hops have to be warmed: the API host serves the CSS, a second
		// host serves the font files it points at.
		add_action(
			'wp_head',
			static function (): void {
				echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
				echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
			},
			0
		);

		foreach ( $urls as $i => $url ) {
			wp_enqueue_style( 'aaa-font-custom-' . $i, $url, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- the URL carries its own versioning.
		}
	}

	$defaults = aaa_font_defaults();

	// Only print the override when something actually differs, so the common
	// case adds no bytes.
	if ( $heading['stack'] === $defaults['heading']['stack'] && $body['stack'] === $defaults['body']['stack'] ) {
		return;
	}

	wp_add_inline_style(
		'aaa-main',
		sprintf(
			':root{--display:%s;--body:%s}',
			$heading['stack'],
			$body['stack']
		)
	);
}
add_action( 'wp_enqueue_scripts', 'aaa_font_overrides', 20 );

/**
 * Register the Typography section.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function aaa_customize_typography( $wp_customize ): void {
	$installed = aaa_installed_fonts();
	$defaults  = aaa_font_defaults();

	$wp_customize->add_section(
		'aaa_typography',
		array(
			'title'       => __( 'Typography', 'amir-al-afia' ),
			'panel'       => 'aaa_panel',
			'description' => __( 'The theme ships Playfair Display SC for headings and Poppins for body text, both stored on this server. To use a different font, install it first at Appearance → Fonts and pick it below — that keeps the files local. Pasting a Google Fonts link also works, but loads the font from Google on every page view.', 'amir-al-afia' ),
		)
	);

	$roles = array(
		'heading' => __( 'Headings', 'amir-al-afia' ),
		'body'    => __( 'Body text', 'amir-al-afia' ),
	);

	foreach ( $roles as $role => $label ) {
		$choices = array(
			/* translators: %s: the font the theme ships with for this role. */
			'default' => sprintf( __( 'Theme default (%s)', 'amir-al-afia' ), $defaults[ $role ]['family'] ),
		);
		if ( $installed ) {
			$choices['library'] = __( 'A font from Appearance → Fonts', 'amir-al-afia' );
		}
		$choices['url'] = __( 'A Google Fonts link', 'amir-al-afia' );

		$wp_customize->add_setting(
			'aaa_font_' . $role . '_source',
			array(
				'default'           => 'default',
				'sanitize_callback' => static function ( $value ) use ( $choices ): string {
					return isset( $choices[ $value ] ) ? $value : 'default';
				},
			)
		);
		$wp_customize->add_control(
			'aaa_font_' . $role . '_source',
			array(
				/* translators: %s: "Headings" or "Body text". */
				'label'   => sprintf( __( '%s — font source', 'amir-al-afia' ), $label ),
				'section' => 'aaa_typography',
				'type'    => 'select',
				'choices' => $choices,
			)
		);

		if ( $installed ) {
			$wp_customize->add_setting(
				'aaa_font_' . $role . '_library',
				array(
					'default'           => '',
					'sanitize_callback' => static function ( $value ) use ( $installed ): string {
						return isset( $installed[ $value ] ) ? $value : '';
					},
				)
			);
			$wp_customize->add_control(
				'aaa_font_' . $role . '_library',
				array(
					/* translators: %s: "Headings" or "Body text". */
					'label'       => sprintf( __( '%s — installed font', 'amir-al-afia' ), $label ),
					'description' => __( 'Used when the source above is set to the Font Library.', 'amir-al-afia' ),
					'section'     => 'aaa_typography',
					'type'        => 'select',
					'choices'     => array( '' => __( '— Select —', 'amir-al-afia' ) ) + $installed,
				)
			);
		}

		$wp_customize->add_setting(
			'aaa_font_' . $role . '_url',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			'aaa_font_' . $role . '_url',
			array(
				/* translators: %s: "Headings" or "Body text". */
				'label'       => sprintf( __( '%s — Google Fonts link', 'amir-al-afia' ), $label ),
				'description' => __( 'Paste the whole href from the Google Fonts "@import / link" panel, e.g. https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400..900&display=swap — pick a weight range that includes 800, since headings are bold. Used when the source above is set to a Google Fonts link.', 'amir-al-afia' ),
				'section'     => 'aaa_typography',
				'type'        => 'url',
			)
		);

		$wp_customize->add_setting(
			'aaa_font_' . $role . '_family',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'aaa_font_' . $role . '_family',
			array(
				/* translators: %s: "Headings" or "Body text". */
				'label'       => sprintf( __( '%s — font name override', 'amir-al-afia' ), $label ),
				'description' => __( 'Only needed if the name cannot be read from the link. Normally leave empty.', 'amir-al-afia' ),
				'section'     => 'aaa_typography',
				'type'        => 'text',
			)
		);
	}
}
add_action( 'customize_register', 'aaa_customize_typography', 20 );
