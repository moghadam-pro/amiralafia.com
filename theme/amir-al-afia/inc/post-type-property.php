<?php
/**
 * Property custom post type and its taxonomies.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the Property post type plus the two taxonomies the landing-page
 * filters are built on: property type ("Apartment", "Villa", "Luxury") and
 * deal type ("For Sale", "For Rent").
 */
function aaa_register_property(): void {
	register_post_type( 'property', array(
		'labels'             => array(
			'name'               => __( 'Properties', 'amir-al-afia' ),
			'singular_name'      => __( 'Property', 'amir-al-afia' ),
			'add_new'            => __( 'Add Property', 'amir-al-afia' ),
			'add_new_item'       => __( 'Add New Property', 'amir-al-afia' ),
			'edit_item'          => __( 'Edit Property', 'amir-al-afia' ),
			'new_item'           => __( 'New Property', 'amir-al-afia' ),
			'view_item'          => __( 'View Property', 'amir-al-afia' ),
			'search_items'       => __( 'Search Properties', 'amir-al-afia' ),
			'not_found'          => __( 'No properties found', 'amir-al-afia' ),
			'not_found_in_trash' => __( 'No properties in Trash', 'amir-al-afia' ),
			'all_items'          => __( 'All Properties', 'amir-al-afia' ),
			'menu_name'          => __( 'Properties', 'amir-al-afia' ),
		),
		'public'             => true,
		'has_archive'        => true,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-building',
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'revisions' ),
		'rewrite'            => array( 'slug' => 'properties', 'with_front' => false ),
		'capability_type'    => 'post',
	) );

	register_taxonomy( 'property_type', 'property', array(
		'labels'            => array(
			'name'          => __( 'Property Types', 'amir-al-afia' ),
			'singular_name' => __( 'Property Type', 'amir-al-afia' ),
			'menu_name'     => __( 'Types', 'amir-al-afia' ),
		),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'property-type', 'with_front' => false ),
	) );

	register_taxonomy( 'deal_type', 'property', array(
		'labels'            => array(
			'name'          => __( 'Deal Types', 'amir-al-afia' ),
			'singular_name' => __( 'Deal Type', 'amir-al-afia' ),
			'menu_name'     => __( 'Deal', 'amir-al-afia' ),
		),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'deal', 'with_front' => false ),
	) );

	register_taxonomy( 'property_location', 'property', array(
		'labels'            => array(
			'name'          => __( 'Locations', 'amir-al-afia' ),
			'singular_name' => __( 'Location', 'amir-al-afia' ),
			'menu_name'     => __( 'Locations', 'amir-al-afia' ),
		),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'location', 'with_front' => false ),
	) );
}
add_action( 'init', 'aaa_register_property' );

/**
 * Seed the fixed taxonomy terms the landing-page filter bar expects. Editors
 * can add more terms; these four just guarantee the filters are never empty.
 */
function aaa_seed_terms(): void {
	$seeds = array(
		'property_type' => array(
			'apartment' => __( 'Apartment', 'amir-al-afia' ),
			'villa'     => __( 'Villas', 'amir-al-afia' ),
			'luxury'    => __( 'Luxuries', 'amir-al-afia' ),
		),
		'deal_type'     => array(
			'for-sale' => __( 'For Sale', 'amir-al-afia' ),
			'for-rent' => __( 'For Rent', 'amir-al-afia' ),
		),
	);

	foreach ( $seeds as $taxonomy => $terms ) {
		foreach ( $terms as $slug => $name ) {
			if ( ! term_exists( $slug, $taxonomy ) ) {
				wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );
			}
		}
	}
}

/**
 * Flush rewrites once after the post type exists, so /properties/ resolves
 * without a manual visit to the Permalinks screen.
 */
function aaa_maybe_flush_rewrites(): void {
	if ( get_option( 'aaa_rewrites_flushed' ) === AAA_VERSION ) {
		return;
	}
	aaa_seed_terms();
	flush_rewrite_rules();
	update_option( 'aaa_rewrites_flushed', AAA_VERSION );
}
add_action( 'init', 'aaa_maybe_flush_rewrites', 20 );

/**
 * Price and deal columns on the Properties list table, so the office can scan
 * inventory without opening each post.
 */
function aaa_property_columns( array $columns ): array {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['aaa_price'] = __( 'Price', 'amir-al-afia' );
			$new['aaa_specs'] = __( 'Beds / Baths / Area', 'amir-al-afia' );
		}
	}
	return $new;
}
add_filter( 'manage_property_posts_columns', 'aaa_property_columns' );

/**
 * Render the custom Properties list-table columns.
 */
function aaa_property_column_content( string $column, int $post_id ): void {
	if ( 'aaa_price' === $column ) {
		echo esc_html( aaa_format_price( get_post_meta( $post_id, '_aaa_price', true ) ) );
	}
	if ( 'aaa_specs' === $column ) {
		$beds  = get_post_meta( $post_id, '_aaa_beds', true );
		$baths = get_post_meta( $post_id, '_aaa_baths', true );
		$area  = get_post_meta( $post_id, '_aaa_area', true );
		echo esc_html( sprintf(
			'%s / %s / %s',
			'' === $beds ? '—' : $beds,
			'' === $baths ? '—' : $baths,
			'' === $area ? '—' : number_format_i18n( (float) $area ) . ' sqft'
		) );
	}
}
add_action( 'manage_property_posts_custom_column', 'aaa_property_column_content', 10, 2 );

/**
 * Sort the Properties list by price.
 */
function aaa_property_sortable_columns( array $columns ): array {
	$columns['aaa_price'] = 'aaa_price';
	return $columns;
}
add_filter( 'manage_edit-property_sortable_columns', 'aaa_property_sortable_columns' );

/**
 * Apply the price sort as a numeric meta ordering.
 */
function aaa_property_orderby( WP_Query $query ): void {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( 'aaa_price' === $query->get( 'orderby' ) ) {
		$query->set( 'meta_key', '_aaa_price' );
		$query->set( 'orderby', 'meta_value_num' );
	}
}
add_action( 'pre_get_posts', 'aaa_property_orderby' );
