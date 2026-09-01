<?php
/**
 * Property listing queries and the AJAX endpoint behind the filter bar.
 *
 * In the mockup the filter buttons only moved a highlight around. Here they
 * re-query the database. The buttons are rendered as links to the properties
 * archive, so they still filter without JavaScript; main.js intercepts the
 * click and swaps the grid in place.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build the WP_Query arguments for a filtered property list.
 *
 * @param string $type     property_type term slug, or 'all'.
 * @param string $deal     deal_type term slug, or 'all'.
 * @param int    $per_page How many to return.
 * @param bool   $featured Limit to properties flagged for the home page.
 * @return array<string, mixed>
 */
function aaa_property_query_args( string $type = 'all', string $deal = 'all', int $per_page = 8, bool $featured = false ): array {
	$args = array(
		'post_type'           => 'property',
		'post_status'         => 'publish',
		'posts_per_page'      => $per_page,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	$tax_query = array();

	if ( 'all' !== $type && '' !== $type ) {
		$tax_query[] = array(
			'taxonomy' => 'property_type',
			'field'    => 'slug',
			'terms'    => $type,
		);
	}

	if ( 'all' !== $deal && '' !== $deal ) {
		$tax_query[] = array(
			'taxonomy' => 'deal_type',
			'field'    => 'slug',
			'terms'    => $deal,
		);
	}

	if ( count( $tax_query ) > 1 ) {
		$tax_query['relation'] = 'AND';
	}

	if ( $tax_query ) {
		$args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
	}

	if ( $featured ) {
		$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'   => '_aaa_featured',
				'value' => '1',
			),
		);
	}

	return $args;
}

/**
 * Run the home-page property query.
 *
 * Featured listings come first, then the newest listings top the grid up to the
 * requested count. That top-up matters: the teaser is a fixed two-row grid, and
 * without it an office that has flagged three properties gets three cards and a
 * conspicuously empty second row. Flagging nothing at all is a perfectly
 * reasonable state, and it should still look deliberate.
 *
 * Once a filter is active, featured stops applying entirely - someone asking
 * for rentals wants rentals, not the intersection of rentals and whatever the
 * office happened to flag.
 *
 * @param string $type     property_type slug or 'all'.
 * @param string $deal     deal_type slug or 'all'.
 * @param int    $per_page How many cards.
 * @return WP_Query
 */
function aaa_get_home_properties( string $type = 'all', string $deal = 'all', int $per_page = 8 ): WP_Query {
	$filtered = ( 'all' !== $type && '' !== $type ) || ( 'all' !== $deal && '' !== $deal );

	if ( $filtered ) {
		return new WP_Query( aaa_property_query_args( $type, $deal, $per_page, false ) );
	}

	$featured = get_posts(
		array_merge(
			aaa_property_query_args( $type, $deal, $per_page, true ),
			array( 'fields' => 'ids' )
		)
	);

	$ids = array_map( 'intval', (array) $featured );

	if ( count( $ids ) < $per_page ) {
		$fill = get_posts(
			array_merge(
				aaa_property_query_args( $type, $deal, $per_page - count( $ids ), false ),
				array(
					'fields'       => 'ids',
					'post__not_in' => $ids ?: array( 0 ),
				)
			)
		);
		$ids = array_merge( $ids, array_map( 'intval', (array) $fill ) );
	}

	if ( ! $ids ) {
		// Nothing published yet; let the caller render its empty state.
		return new WP_Query( aaa_property_query_args( $type, $deal, $per_page, false ) );
	}

	return new WP_Query(
		array(
			'post_type'           => 'property',
			'post_status'         => 'publish',
			'post__in'            => $ids,
			// post__in returns rows in an arbitrary order otherwise, which
			// would scatter the featured ones through the grid.
			'orderby'             => 'post__in',
			'posts_per_page'      => $per_page,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
}

/**
 * Return the filter groups the bar renders, each with its terms.
 *
 * @return array<string, array<string, mixed>>
 */
function aaa_filter_groups(): array {
	$groups = array();

	foreach ( array( 'type' => 'property_type', 'deal' => 'deal_type' ) as $group => $taxonomy ) {
		// Ordered by term_id, i.e. the order they were seeded, so the bar reads
		// Apartment / Villas / Luxuries and For Sale / For Rent as designed,
		// rather than alphabetically.
		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'orderby'    => 'term_id',
				'order'      => 'ASC',
			)
		);

		$groups[ $group ] = array(
			'taxonomy' => $taxonomy,
			'terms'    => is_wp_error( $terms ) ? array() : $terms,
		);
	}

	return $groups;
}

/**
 * AJAX: return the rendered property cards for a filter combination.
 */
function aaa_ajax_filter_properties(): void {
	check_ajax_referer( 'aaa_filter_properties', 'nonce' );

	$type     = isset( $_POST['type'] ) ? sanitize_title( wp_unslash( $_POST['type'] ) ) : 'all';
	$deal     = isset( $_POST['deal'] ) ? sanitize_title( wp_unslash( $_POST['deal'] ) ) : 'all';
	$per_page = isset( $_POST['per_page'] ) ? absint( wp_unslash( $_POST['per_page'] ) ) : 8;
	$per_page = min( max( $per_page, 1 ), 24 );

	$query = aaa_get_home_properties( $type, $deal, $per_page );

	ob_start();
	if ( $query->have_posts() ) {
		$index = 0;
		while ( $query->have_posts() ) {
			$query->the_post();
			set_query_var( 'aaa_delay', $index * 60 );
			get_template_part( 'template-parts/property/card' );
			++$index;
		}
		wp_reset_postdata();
	} else {
		get_template_part( 'template-parts/property/empty' );
	}

	wp_send_json_success(
		array(
			'html'  => ob_get_clean(),
			'count' => (int) $query->post_count,
		)
	);
}
add_action( 'wp_ajax_aaa_filter_properties', 'aaa_ajax_filter_properties' );
add_action( 'wp_ajax_nopriv_aaa_filter_properties', 'aaa_ajax_filter_properties' );

/**
 * Show a fuller grid on the properties archive than the home teaser.
 *
 * @param WP_Query $query Query being prepared.
 */
function aaa_archive_posts_per_page( $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_post_type_archive( 'property' ) || $query->is_tax( array( 'property_type', 'deal_type', 'property_location' ) ) ) {
		$query->set( 'posts_per_page', 12 );
	}
}
add_action( 'pre_get_posts', 'aaa_archive_posts_per_page' );
