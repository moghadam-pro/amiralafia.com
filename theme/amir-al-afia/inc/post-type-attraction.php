<?php
/**
 * Attraction post type - the "Attractions & Nature of Oman" tiles, each of
 * which is now a page in its own right.
 *
 * These exist for people deciding *where* in Oman to live, so each page pairs
 * the place itself with the residential areas near it.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the Attraction post type.
 */
function aaa_register_attraction(): void {
	register_post_type(
		'attraction',
		array(
			'labels'        => array(
				'name'          => __( 'Attractions', 'amir-al-afia' ),
				'singular_name' => __( 'Attraction', 'amir-al-afia' ),
				'add_new_item'  => __( 'Add New Attraction', 'amir-al-afia' ),
				'edit_item'     => __( 'Edit Attraction', 'amir-al-afia' ),
				'all_items'     => __( 'All Attractions', 'amir-al-afia' ),
				'menu_name'     => __( 'Attractions', 'amir-al-afia' ),
			),
			'public'        => true,
			'has_archive'   => true,
			'show_in_menu'  => true,
			'show_in_rest'  => true,
			'menu_position' => 8,
			'menu_icon'     => 'dashicons-palmtree',
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'revisions' ),
			'rewrite'       => array(
				'slug'       => 'oman',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'aaa_register_attraction' );

/**
 * Attraction fact fields.
 *
 * @return array<string, array<string, string>>
 */
function aaa_attraction_fields(): array {
	return array(
		'_aaa_attr_region'   => array(
			'label' => __( 'Region / governorate', 'amir-al-afia' ),
			'type'  => 'text',
		),
		'_aaa_attr_drive'    => array(
			'label' => __( 'Drive time from Muscat', 'amir-al-afia' ),
			'type'  => 'text',
			'desc'  => __( 'For example "15 minutes" or "2 hours".', 'amir-al-afia' ),
		),
		'_aaa_attr_best'     => array(
			'label' => __( 'Best time to visit', 'amir-al-afia' ),
			'type'  => 'text',
		),
		'_aaa_attr_areas'    => array(
			'label' => __( 'Residential areas nearby', 'amir-al-afia' ),
			'type'  => 'text',
			'desc'  => __( 'Comma separated. Shown as the "live near here" line.', 'amir-al-afia' ),
		),
		'_aaa_attr_lat'      => array(
			'label' => __( 'Latitude', 'amir-al-afia' ),
			'type'  => 'text',
		),
		'_aaa_attr_lng'      => array(
			'label' => __( 'Longitude', 'amir-al-afia' ),
			'type'  => 'text',
		),
		'_aaa_attraction_url' => array(
			'label' => __( 'External link (optional)', 'amir-al-afia' ),
			'type'  => 'url',
			'desc'  => __( 'An official page about the place, if there is one.', 'amir-al-afia' ),
		),
	);
}

/**
 * Add the Attraction Facts meta box.
 */
function aaa_attraction_meta_box(): void {
	add_meta_box(
		'aaa_attraction_facts',
		__( 'Attraction Facts', 'amir-al-afia' ),
		'aaa_render_attraction_meta_box',
		'attraction',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'aaa_attraction_meta_box' );

/**
 * Render the Attraction Facts meta box.
 *
 * @param WP_Post $post Post being edited.
 */
function aaa_render_attraction_meta_box( $post ): void {
	wp_nonce_field( 'aaa_save_attraction', 'aaa_attraction_nonce' );
	echo '<div class="aaa-fields">';
	foreach ( aaa_attraction_fields() as $key => $field ) {
		$value = (string) get_post_meta( $post->ID, $key, true );
		printf(
			'<div class="aaa-field"><label for="%1$s">%2$s</label><input type="%3$s" id="%1$s" name="%1$s" value="%4$s">%5$s</div>',
			esc_attr( $key ),
			esc_html( $field['label'] ),
			esc_attr( $field['type'] ),
			esc_attr( $value ),
			empty( $field['desc'] ) ? '' : '<p class="description">' . esc_html( $field['desc'] ) . '</p>'
		);
	}
	echo '</div>';
}

/**
 * Persist the Attraction Facts fields.
 *
 * @param int $post_id Post being saved.
 */
function aaa_save_attraction_meta( int $post_id ): void {
	if ( ! isset( $_POST['aaa_attraction_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['aaa_attraction_nonce'] ) ), 'aaa_save_attraction' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( aaa_attraction_fields() as $key => $field ) {
		$raw   = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
		$value = 'url' === $field['type'] ? esc_url_raw( $raw ) : sanitize_text_field( $raw );

		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post_attraction', 'aaa_save_attraction_meta' );

/**
 * The attraction tiles, in menu order.
 *
 * @param int $limit How many tiles.
 * @return WP_Post[]
 */
function aaa_get_attractions( int $limit = 6 ): array {
	return get_posts(
		array(
			'post_type'        => 'attraction',
			'posts_per_page'   => $limit,
			'orderby'          => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
			'suppress_filters' => false,
		)
	);
}

/**
 * The fact rows to print on a single attraction page, skipping empties.
 *
 * @param int $post_id Attraction ID.
 * @return array<int, array<string, string>>
 */
function aaa_attraction_facts( int $post_id ): array {
	$map = array(
		'_aaa_attr_region' => array( __( 'Region', 'amir-al-afia' ), 'pin' ),
		'_aaa_attr_drive'  => array( __( 'From Muscat', 'amir-al-afia' ), 'arrow-r' ),
		'_aaa_attr_best'   => array( __( 'Best season', 'amir-al-afia' ), 'calendar' ),
		'_aaa_attr_areas'  => array( __( 'Live nearby', 'amir-al-afia' ), 'home' ),
	);

	$facts = array();
	foreach ( $map as $key => $meta ) {
		$value = (string) get_post_meta( $post_id, $key, true );
		if ( '' === $value ) {
			continue;
		}
		$facts[] = array(
			'label' => $meta[0],
			'icon'  => $meta[1],
			'value' => $value,
		);
	}

	return $facts;
}
