<?php
/**
 * Attraction post type - the "Attractions & Nature of Oman" tiles.
 *
 * Each tile is a photo plus a vertical label, and optionally a link out to a
 * page about the place, so these are content rather than markup.
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
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'show_in_rest'  => true,
			'menu_position' => 8,
			'menu_icon'     => 'dashicons-palmtree',
			'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
			'has_archive'   => false,
		)
	);
}
add_action( 'init', 'aaa_register_attraction' );

/**
 * Optional outbound link for an attraction tile.
 */
function aaa_attraction_meta_box(): void {
	add_meta_box(
		'aaa_attraction_link',
		__( 'Attraction Link', 'amir-al-afia' ),
		static function ( $post ): void {
			wp_nonce_field( 'aaa_save_attraction', 'aaa_attraction_nonce' );
			$url = (string) get_post_meta( $post->ID, '_aaa_attraction_url', true );
			echo '<div class="aaa-fields"><div class="aaa-field">';
			echo '<label for="_aaa_attraction_url">' . esc_html__( 'Link (optional)', 'amir-al-afia' ) . '</label>';
			printf( '<input type="url" id="_aaa_attraction_url" name="_aaa_attraction_url" value="%s">', esc_attr( $url ) );
			echo '<p class="description">' . esc_html__( 'Leave empty to render the tile without a link.', 'amir-al-afia' ) . '</p>';
			echo '</div></div>';
		},
		'attraction',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'aaa_attraction_meta_box' );

/**
 * Persist the attraction link.
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
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$url = isset( $_POST['_aaa_attraction_url'] ) ? esc_url_raw( wp_unslash( $_POST['_aaa_attraction_url'] ) ) : '';
	if ( '' === $url ) {
		delete_post_meta( $post_id, '_aaa_attraction_url' );
	} else {
		update_post_meta( $post_id, '_aaa_attraction_url', $url );
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
