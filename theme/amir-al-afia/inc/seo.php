<?php
/**
 * Meta description, Open Graph tags and JSON-LD.
 *
 * The brief rules out an SEO plugin, so the theme emits the tags itself. It
 * stays out of the way if an SEO plugin is ever added: every tag is skipped
 * when a known plugin is active.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * True when another plugin already owns the document head.
 */
function aaa_seo_handled_elsewhere(): bool {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || class_exists( 'AIOSEO\\Plugin\\AIOSEO' );
}

/**
 * A description for the current view: the excerpt, else the tagline.
 */
function aaa_meta_description(): string {
	if ( is_front_page() ) {
		$text = aaa_option( 'aaa_hero_desc' );
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		$text = $post instanceof WP_Post ? get_the_excerpt( $post ) : '';
	} elseif ( is_post_type_archive( 'property' ) ) {
		$text = aaa_option( 'aaa_props_sub' );
	} elseif ( is_tax() ) {
		$text = term_description();
	} else {
		$text = get_bloginfo( 'description' );
	}

	$text = wp_strip_all_tags( (string) $text, true );
	$text = $text ?: get_bloginfo( 'description' );

	return wp_html_excerpt( $text, 155, '…' );
}

/**
 * The best available share image for the current view.
 */
function aaa_share_image(): string {
	if ( is_singular() && has_post_thumbnail() ) {
		$src = wp_get_attachment_image_src( (int) get_post_thumbnail_id(), 'aaa-wide' );
		if ( $src ) {
			return $src[0];
		}
	}

	$collage = (int) get_theme_mod( 'aaa_collage_4', 0 );
	if ( $collage ) {
		$src = wp_get_attachment_image_src( $collage, 'aaa-wide' );
		if ( $src ) {
			return $src[0];
		}
	}

	return '';
}

/**
 * Print description, canonical and Open Graph / Twitter tags.
 */
function aaa_head_meta(): void {
	if ( aaa_seo_handled_elsewhere() ) {
		return;
	}

	$title = wp_get_document_title();
	$desc  = aaa_meta_description();
	$image = aaa_share_image();

	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:type" content="%s">' . "\n", is_singular() && ! is_front_page() ? 'article' : 'website' );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) );
	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( get_locale() ) );

	$url = is_singular() ? get_permalink() : home_url( add_query_arg( array() ) );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );

	if ( $image ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	} else {
		echo '<meta name="twitter:card" content="summary">' . "\n";
	}

	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $desc ) );
}
add_action( 'wp_head', 'aaa_head_meta', 3 );

/**
 * JSON-LD: a RealEstateAgent on the home page, a listing on single properties.
 */
function aaa_json_ld(): void {
	if ( aaa_seo_handled_elsewhere() ) {
		return;
	}

	$data = null;

	if ( is_front_page() ) {
		$same_as = array_values(
			array_filter(
				array(
					aaa_whatsapp_url( aaa_option( 'aaa_whatsapp' ) ),
					aaa_telegram_url( aaa_option( 'aaa_telegram' ) ),
				)
			)
		);

		$data = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'RealEstateAgent',
			'name'        => get_bloginfo( 'name' ),
			'url'         => home_url( '/' ),
			'description' => aaa_meta_description(),
			'telephone'   => aaa_option( 'aaa_phone' ),
			'areaServed'  => aaa_option( 'aaa_city' ),
			'address'     => array(
				'@type'           => 'PostalAddress',
				'addressLocality' => 'Muscat',
				'addressCountry'  => 'OM',
			),
			'sameAs'      => $same_as,
		);

		$email = aaa_option( 'aaa_email' );
		if ( $email ) {
			$data['email'] = $email;
		}

		$image = aaa_share_image();
		if ( $image ) {
			$data['image'] = $image;
		}
	} elseif ( is_singular( 'property' ) ) {
		$id    = get_the_ID();
		$price = get_post_meta( $id, '_aaa_price', true );

		$data = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'Residence',
			'name'        => get_the_title(),
			'url'         => get_permalink(),
			'description' => aaa_meta_description(),
		);

		$address = (string) get_post_meta( $id, '_aaa_address', true );
		if ( $address ) {
			$data['address'] = $address;
		}

		$beds = (string) get_post_meta( $id, '_aaa_beds', true );
		if ( is_numeric( $beds ) ) {
			$data['numberOfRooms'] = (int) $beds;
		}

		$area = (string) get_post_meta( $id, '_aaa_area', true );
		if ( is_numeric( $area ) ) {
			$data['floorSize'] = array(
				'@type'    => 'QuantitativeValue',
				'value'    => (float) $area,
				'unitCode' => 'FTK',
			);
		}

		if ( is_numeric( $price ) ) {
			$data['offers'] = array(
				'@type'         => 'Offer',
				'price'         => (float) $price,
				'priceCurrency' => 'USD',
				'availability'  => 'https://schema.org/InStock',
			);
		}

		$image = aaa_share_image();
		if ( $image ) {
			$data['image'] = $image;
		}
	}

	if ( ! $data ) {
		return;
	}

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
add_action( 'wp_head', 'aaa_json_ld', 20 );
