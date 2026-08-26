<?php
/**
 * Document head: canonical, robots, Open Graph, Twitter cards and JSON-LD.
 *
 * The brief rules out an SEO plugin, so the theme emits the tags itself. It
 * steps aside entirely if an SEO plugin is ever installed.
 *
 * The Open Graph block is what makes a link pasted into WhatsApp, Telegram,
 * Facebook, LinkedIn or X render as a card. Two details matter more than the
 * rest and are easy to get wrong:
 *
 *   - og:image must be an absolute URL that resolves without a redirect and
 *     without a login. Ours come straight from the uploads directory.
 *   - og:image:width and og:image:height must be present. Without them
 *     Facebook and WhatsApp fall back to a small thumbnail on first scrape,
 *     because they will not block on downloading the image to measure it.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * True when another plugin already owns the document head.
 */
function aaa_seo_handled_elsewhere(): bool {
	return defined( 'WPSEO_VERSION' )
		|| defined( 'RANK_MATH_VERSION' )
		|| defined( 'SEOPRESS_VERSION' )
		|| class_exists( 'AIOSEO\\Plugin\\AIOSEO' );
}

/**
 * A description for the current view.
 */
function aaa_meta_description(): string {
	if ( is_front_page() ) {
		$text = aaa_option( 'aaa_hero_desc' );
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		$text = $post instanceof WP_Post ? get_the_excerpt( $post ) : '';
	} elseif ( is_post_type_archive( 'property' ) ) {
		$text = aaa_option( 'aaa_props_sub' );
	} elseif ( is_post_type_archive( 'attraction' ) ) {
		$text = __( 'Beaches, mountains, wadis and old quarters worth knowing before you choose where in Oman to live.', 'amir-al-afia' );
	} elseif ( is_tax() ) {
		$text = term_description();
	} else {
		$text = get_bloginfo( 'description' );
	}

	$text = wp_strip_all_tags( (string) $text, true );
	$text = $text ?: get_bloginfo( 'description' );
	$text = $text ?: aaa_option( 'aaa_hero_desc' );

	return wp_html_excerpt( $text, 155, '…' );
}

/**
 * The canonical URL for the current view.
 */
function aaa_canonical_url(): string {
	if ( is_singular() ) {
		return (string) get_permalink();
	}
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_post_type_archive() ) {
		$url = get_post_type_archive_link( (string) get_query_var( 'post_type' ) );
		if ( $url ) {
			return $url;
		}
	}
	if ( is_tax() || is_category() || is_tag() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$url = get_term_link( $term );
			if ( ! is_wp_error( $url ) ) {
				return $url;
			}
		}
	}

	return home_url( add_query_arg( array() ) );
}

/**
 * The share image for the current view, with the dimensions the scrapers need.
 *
 * Falls back through: the post's featured image, the first property with a
 * photo (on the properties archive), the hero collage, and finally the
 * bundled default card.
 *
 * @return array{url:string, width:int, height:int, alt:string, type:string}
 */
function aaa_share_image(): array {
	$candidates = array();

	if ( is_singular() && has_post_thumbnail() ) {
		$candidates[] = (int) get_post_thumbnail_id();
	}

	if ( is_post_type_archive( 'property' ) || is_tax( array( 'property_type', 'deal_type', 'property_location' ) ) ) {
		$first = get_posts(
			array(
				'post_type'      => 'property',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_thumbnail_id',
			)
		);
		if ( $first ) {
			$candidates[] = (int) get_post_thumbnail_id( $first[0] );
		}
	}

	$collage = (int) get_theme_mod( 'aaa_collage_4', 0 );
	if ( $collage ) {
		$candidates[] = $collage;
	}

	foreach ( array_filter( $candidates ) as $id ) {
		$src = wp_get_attachment_image_src( $id, 'aaa-og' );
		if ( ! $src ) {
			continue;
		}

		// wp_get_attachment_image_src() silently falls back to the full image
		// when the source was too small to crop to 1200x630. A tall-and-thin
		// or short-and-wide card previews badly in WhatsApp and Telegram, so
		// fall through to the next candidate instead of shipping it.
		if ( (int) $src[1] < 600 || (int) $src[2] < 315 ) {
			continue;
		}

		return array(
			'url'    => $src[0],
			'width'  => (int) $src[1],
			'height' => (int) $src[2],
			'alt'    => (string) get_post_meta( $id, '_wp_attachment_image_alt', true ),
			'type'   => (string) get_post_mime_type( $id ) ?: 'image/jpeg',
		);
	}

	// The bundled card, so a link always previews with something on brand.
	//
	// Versioned deliberately. The host sits behind a CDN that caches static
	// assets with a ten-year max-age, so an unversioned filename would keep
	// serving the previous card long after a rebrand — and this is the exact
	// URL the chat apps scrape.
	return array(
		'url'    => add_query_arg( 'ver', AAA_VERSION, AAA_URI . '/assets/img/share-default.png' ),
		'width'  => 1200,
		'height' => 630,
		'alt'    => get_bloginfo( 'name' ) . ' — ' . __( 'Real estate in Muscat, Oman', 'amir-al-afia' ),
		'type'   => 'image/png',
	);
}

/**
 * Print canonical, robots, Open Graph and Twitter card tags.
 */
function aaa_head_meta(): void {
	if ( aaa_seo_handled_elsewhere() ) {
		return;
	}

	$title = wp_get_document_title();
	$desc  = aaa_meta_description();
	$url   = aaa_canonical_url();
	$image = aaa_share_image();

	// Canonical. Core only prints one on singular views.
	if ( ! is_singular() ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $url ) );
	}

	// Keep thin views out of the index, but let crawlers follow the links.
	if ( is_search() || is_404() || is_paged() ) {
		echo '<meta name="robots" content="noindex, follow">' . "\n";
	} else {
		echo '<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">' . "\n";
	}

	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	echo '<meta name="theme-color" content="#018ED5">' . "\n";

	// --- Open Graph -----------------------------------------------------
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:type" content="%s">' . "\n", ( is_singular() && ! is_front_page() ) ? 'article' : 'website' );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( get_locale() ) );

	printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image['url'] ) );
	printf( '<meta property="og:image:secure_url" content="%s">' . "\n", esc_url( $image['url'] ) );
	printf( '<meta property="og:image:type" content="%s">' . "\n", esc_attr( $image['type'] ) );
	printf( '<meta property="og:image:width" content="%d">' . "\n", $image['width'] );
	printf( '<meta property="og:image:height" content="%d">' . "\n", $image['height'] );
	printf( '<meta property="og:image:alt" content="%s">' . "\n", esc_attr( $image['alt'] ?: $title ) );

	if ( is_singular() && ! is_front_page() ) {
		printf( '<meta property="article:published_time" content="%s">' . "\n", esc_attr( (string) get_the_date( 'c' ) ) );
		printf( '<meta property="article:modified_time" content="%s">' . "\n", esc_attr( (string) get_the_modified_date( 'c' ) ) );
	}

	// --- Twitter / X ----------------------------------------------------
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $desc ) );
	printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image['url'] ) );
	printf( '<meta name="twitter:image:alt" content="%s">' . "\n", esc_attr( $image['alt'] ?: $title ) );

	// A listing's headline numbers, shown by X and some chat clients.
	if ( is_singular( 'property' ) ) {
		$price = get_post_meta( get_the_ID(), '_aaa_price', true );
		$beds  = (string) get_post_meta( get_the_ID(), '_aaa_beds', true );

		printf( '<meta name="twitter:label1" content="%s">' . "\n", esc_attr__( 'Price', 'amir-al-afia' ) );
		printf( '<meta name="twitter:data1" content="%s">' . "\n", esc_attr( aaa_format_price( $price ) ) );

		if ( $beds ) {
			printf( '<meta name="twitter:label2" content="%s">' . "\n", esc_attr__( 'Bedrooms', 'amir-al-afia' ) );
			printf( '<meta name="twitter:data2" content="%s">' . "\n", esc_attr( $beds ) );
		}
	}
}
add_action( 'wp_head', 'aaa_head_meta', 3 );

/**
 * Decode HTML entities for use inside a JSON-LD script block.
 *
 * wp_get_document_title() and the excerpt helpers return entity-encoded text,
 * which is correct for an HTML attribute but wrong inside <script>: a JSON
 * parser does not decode entities, so a title would carry a literal "&#8211;"
 * into the structured data.
 *
 * @param string $text Encoded text.
 */
function aaa_schema_text( string $text ): string {
	return trim( html_entity_decode( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
}

/**
 * The organisation node, referenced by every other JSON-LD node.
 *
 * @return array<string, mixed>
 */
function aaa_schema_organization(): array {
	$same_as = array_values(
		array_filter(
			array(
				aaa_whatsapp_url( aaa_option( 'aaa_whatsapp' ) ),
				aaa_telegram_url( aaa_option( 'aaa_telegram' ) ),
			)
		)
	);

	$node = array(
		'@type'      => 'RealEstateAgent',
		'@id'        => home_url( '/#organization' ),
		'name'       => aaa_schema_text( get_bloginfo( 'name' ) ),
		'url'        => home_url( '/' ),
		'telephone'  => aaa_option( 'aaa_phone' ),
		'areaServed' => array(
			'@type' => 'City',
			'name'  => 'Muscat',
		),
		'address'    => array(
			'@type'           => 'PostalAddress',
			'addressLocality' => 'Muscat',
			'addressCountry'  => 'OM',
		),
		'sameAs'     => $same_as,
	);

	$email = aaa_option( 'aaa_email' );
	if ( $email ) {
		$node['email'] = $email;
	}

	$logo = (int) get_theme_mod( 'custom_logo', 0 );
	if ( $logo ) {
		$src = wp_get_attachment_image_src( $logo, 'full' );
		if ( $src ) {
			$node['logo'] = array(
				'@type'  => 'ImageObject',
				'url'    => $src[0],
				'width'  => (int) $src[1],
				'height' => (int) $src[2],
			);
		}
	} else {
		$node['logo'] = array(
			'@type' => 'ImageObject',
			'url'   => AAA_URI . '/assets/img/logo.svg',
		);
	}

	return $node;
}

/**
 * A BreadcrumbList for the current view, or null on the home page.
 *
 * @return array<string, mixed>|null
 */
function aaa_schema_breadcrumbs(): ?array {
	if ( is_front_page() ) {
		return null;
	}

	$items = array(
		array(
			'name' => __( 'Home', 'amir-al-afia' ),
			'url'  => home_url( '/' ),
		),
	);

	if ( is_singular( 'property' ) || is_post_type_archive( 'property' ) || is_tax( array( 'property_type', 'deal_type', 'property_location' ) ) ) {
		$items[] = array(
			'name' => __( 'Properties', 'amir-al-afia' ),
			'url'  => (string) get_post_type_archive_link( 'property' ),
		);
	}

	if ( is_singular( 'attraction' ) || is_post_type_archive( 'attraction' ) ) {
		$items[] = array(
			'name' => aaa_option( 'aaa_attr_title' ),
			'url'  => (string) get_post_type_archive_link( 'attraction' ),
		);
	}

	if ( is_singular() ) {
		$items[] = array(
			'name' => get_the_title(),
			'url'  => (string) get_permalink(),
		);
	} elseif ( is_tax() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$link    = get_term_link( $term );
			$items[] = array(
				'name' => $term->name,
				'url'  => is_wp_error( $link ) ? '' : $link,
			);
		}
	}

	$list = array();
	foreach ( $items as $i => $item ) {
		$list[] = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'name'     => aaa_schema_text( (string) $item['name'] ),
			'item'     => $item['url'],
		);
	}

	return array(
		'@type'           => 'BreadcrumbList',
		'@id'             => aaa_canonical_url() . '#breadcrumbs',
		'itemListElement' => $list,
	);
}

/**
 * The entity node describing what this page is actually about.
 *
 * @return array<string, mixed>|null
 */
function aaa_schema_entity(): ?array {
	if ( is_singular( 'property' ) ) {
		$id    = get_the_ID();
		$price = get_post_meta( $id, '_aaa_price', true );
		$beds  = (string) get_post_meta( $id, '_aaa_beds', true );
		$baths = (string) get_post_meta( $id, '_aaa_baths', true );
		$area  = (string) get_post_meta( $id, '_aaa_area', true );
		$addr  = (string) get_post_meta( $id, '_aaa_address', true );

		$types = get_the_terms( $id, 'property_type' );
		$slug  = ( $types && ! is_wp_error( $types ) ) ? $types[0]->slug : '';
		$kind  = 'apartment' === $slug ? 'Apartment' : 'SingleFamilyResidence';

		$about = array(
			'@type' => $kind,
			'name'  => aaa_schema_text( get_the_title() ),
		);

		if ( $addr ) {
			$about['address'] = array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => $addr,
				'addressLocality' => 'Muscat',
				'addressCountry'  => 'OM',
			);
		}
		if ( is_numeric( $beds ) ) {
			$about['numberOfRooms'] = (int) $beds;
		}
		if ( is_numeric( $baths ) ) {
			$about['numberOfBathroomsTotal'] = (float) $baths;
		}
		if ( is_numeric( $area ) ) {
			$about['floorSize'] = array(
				'@type'    => 'QuantitativeValue',
				'value'    => (float) $area,
				'unitCode' => 'FTK',
			);
		}

		$node = array(
			// A listing page, per schema.org, is a RealEstateListing.
			'@type'      => 'RealEstateListing',
			'@id'        => get_permalink() . '#listing',
			'name'       => aaa_schema_text( get_the_title() ),
			'url'        => get_permalink(),
			'datePosted' => get_the_date( 'c' ),
			'about'      => $about,
			'provider'   => array( '@id' => home_url( '/#organization' ) ),
		);

		if ( is_numeric( $price ) ) {
			$deal = get_the_terms( $id, 'deal_type' );
			$rent = $deal && ! is_wp_error( $deal ) && 'for-rent' === $deal[0]->slug;

			$node['offers'] = array_filter(
				array(
					'@type'         => 'Offer',
					'price'         => (float) $price,
					'priceCurrency' => 'USD',
					'availability'  => 'https://schema.org/InStock',
					'businessFunction' => $rent
						? 'http://purl.org/goodrelations/v1#LeaseOut'
						: 'http://purl.org/goodrelations/v1#Sell',
					'seller'        => array( '@id' => home_url( '/#organization' ) ),
				)
			);
		}

		return $node;
	}

	if ( is_singular( 'attraction' ) ) {
		$id   = get_the_ID();
		$node = array(
			'@type'       => 'TouristAttraction',
			'@id'         => get_permalink() . '#attraction',
			'name'        => aaa_schema_text( get_the_title() ),
			'url'         => get_permalink(),
			'description' => aaa_schema_text( aaa_meta_description() ),
			'touristType' => __( 'Prospective residents and property investors', 'amir-al-afia' ),
			'address'     => array(
				'@type'          => 'PostalAddress',
				'addressCountry' => 'OM',
			),
		);

		$region = (string) get_post_meta( $id, '_aaa_attr_region', true );
		if ( $region ) {
			$node['address']['addressRegion'] = $region;
		}

		$lat = (string) get_post_meta( $id, '_aaa_attr_lat', true );
		$lng = (string) get_post_meta( $id, '_aaa_attr_lng', true );
		if ( is_numeric( $lat ) && is_numeric( $lng ) ) {
			$node['geo'] = array(
				'@type'     => 'GeoCoordinates',
				'latitude'  => (float) $lat,
				'longitude' => (float) $lng,
			);
		}

		return $node;
	}

	return null;
}

/**
 * Emit the whole JSON-LD graph in one script tag, so the nodes can reference
 * each other by @id instead of repeating the organisation on every page.
 */
function aaa_json_ld(): void {
	if ( aaa_seo_handled_elsewhere() ) {
		return;
	}

	$url   = aaa_canonical_url();
	$image = aaa_share_image();

	$graph = array( aaa_schema_organization() );

	$graph[] = array(
		'@type'           => 'WebSite',
		'@id'             => home_url( '/#website' ),
		'url'             => home_url( '/' ),
		'name'            => get_bloginfo( 'name' ),
		'description'     => get_bloginfo( 'description' ),
		'publisher'       => array( '@id' => home_url( '/#organization' ) ),
		'inLanguage'      => get_bloginfo( 'language' ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array(
				'@type'       => 'EntryPoint',
				'urlTemplate' => home_url( '/?s={search_term_string}' ),
			),
			'query-input' => 'required name=search_term_string',
		),
	);

	$graph[] = array_filter(
		array(
			'@type'      => 'WebPage',
			'@id'        => $url . '#webpage',
			'url'        => $url,
			'name'       => aaa_schema_text( wp_get_document_title() ),
			'description' => aaa_schema_text( aaa_meta_description() ),
			'isPartOf'   => array( '@id' => home_url( '/#website' ) ),
			'about'      => is_front_page() ? array( '@id' => home_url( '/#organization' ) ) : null,
			'primaryImageOfPage' => array(
				'@type' => 'ImageObject',
				'url'   => $image['url'],
				'width' => $image['width'],
				'height' => $image['height'],
			),
			'inLanguage' => get_bloginfo( 'language' ),
		)
	);

	$crumbs = aaa_schema_breadcrumbs();
	if ( $crumbs ) {
		$graph[] = $crumbs;
	}

	$entity = aaa_schema_entity();
	if ( $entity ) {
		$graph[] = $entity;
	}

	// The properties archive as a list, so crawlers see the inventory.
	if ( is_post_type_archive( 'property' ) || is_tax( array( 'property_type', 'deal_type', 'property_location' ) ) ) {
		global $wp_query;
		$items = array();
		$i     = 0;

		foreach ( (array) $wp_query->posts as $post ) {
			if ( ! $post instanceof WP_Post ) {
				continue;
			}
			++$i;
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $i,
				'url'      => get_permalink( $post ),
				'name'     => aaa_schema_text( get_the_title( $post ) ),
			);
		}

		if ( $items ) {
			$graph[] = array(
				'@type'           => 'ItemList',
				'@id'             => $url . '#listings',
				'numberOfItems'   => count( $items ),
				'itemListElement' => $items,
			);
		}
	}

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode(
			array(
				'@context' => 'https://schema.org',
				'@graph'   => $graph,
			),
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
		)
	);
}
add_action( 'wp_head', 'aaa_json_ld', 20 );
