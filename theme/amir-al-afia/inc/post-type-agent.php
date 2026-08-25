<?php
/**
 * Agent post type - the people shown in the "Meet The Team" section.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the Agent post type.
 */
function aaa_register_agent(): void {
	register_post_type(
		'agent',
		array(
			'labels'        => array(
				'name'          => __( 'Agents', 'amir-al-afia' ),
				'singular_name' => __( 'Agent', 'amir-al-afia' ),
				'add_new_item'  => __( 'Add New Agent', 'amir-al-afia' ),
				'edit_item'     => __( 'Edit Agent', 'amir-al-afia' ),
				'all_items'     => __( 'All Agents', 'amir-al-afia' ),
				'menu_name'     => __( 'Agents', 'amir-al-afia' ),
			),
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'show_in_rest'  => true,
			'menu_position' => 6,
			'menu_icon'     => 'dashicons-groups',
			'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
			'has_archive'   => false,
		)
	);
}
add_action( 'init', 'aaa_register_agent' );

/**
 * Agent contact fields.
 *
 * @return array<string, string>
 */
function aaa_agent_fields(): array {
	return array(
		'_aaa_agent_role'     => __( 'Badge (e.g. Seller, Support)', 'amir-al-afia' ),
		'_aaa_agent_title'    => __( 'Job title', 'amir-al-afia' ),
		'_aaa_agent_phone'    => __( 'Phone number', 'amir-al-afia' ),
		'_aaa_agent_whatsapp' => __( 'WhatsApp number (blank = use phone)', 'amir-al-afia' ),
		'_aaa_agent_telegram' => __( 'Telegram username', 'amir-al-afia' ),
	);
}

/**
 * Add the Agent Contact meta box.
 */
function aaa_agent_meta_box(): void {
	add_meta_box(
		'aaa_agent_details',
		__( 'Agent Contact', 'amir-al-afia' ),
		'aaa_render_agent_meta_box',
		'agent',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'aaa_agent_meta_box' );

/**
 * Render the Agent Contact meta box.
 *
 * @param WP_Post $post Post being edited.
 */
function aaa_render_agent_meta_box( $post ): void {
	wp_nonce_field( 'aaa_save_agent', 'aaa_agent_nonce' );
	echo '<div class="aaa-fields">';
	foreach ( aaa_agent_fields() as $key => $label ) {
		$value = (string) get_post_meta( $post->ID, $key, true );
		printf(
			'<div class="aaa-field"><label for="%1$s">%2$s</label><input type="text" id="%1$s" name="%1$s" value="%3$s"></div>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_attr( $value )
		);
	}
	echo '</div>';
}

/**
 * Persist the Agent Contact fields.
 *
 * @param int $post_id Post being saved.
 */
function aaa_save_agent_meta( int $post_id ): void {
	if ( ! isset( $_POST['aaa_agent_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['aaa_agent_nonce'] ) ), 'aaa_save_agent' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( array_keys( aaa_agent_fields() ) as $key ) {
		$value = isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post_agent', 'aaa_save_agent_meta' );

/**
 * The agents shown on the home page, in menu order.
 *
 * @param int $limit How many to return.
 * @return WP_Post[]
 */
function aaa_get_agents( int $limit = 2 ): array {
	return get_posts(
		array(
			'post_type'        => 'agent',
			'posts_per_page'   => $limit,
			'orderby'          => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
			'suppress_filters' => false,
		)
	);
}
