<?php
/**
 * Lead post type - every contact-form submission is stored here so nothing is
 * lost if an email notification bounces.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the Lead post type. Private: it exists for the office, never the web.
 */
function aaa_register_lead(): void {
	register_post_type(
		'aaa_lead',
		array(
			'labels'          => array(
				'name'          => __( 'Leads', 'amir-al-afia' ),
				'singular_name' => __( 'Lead', 'amir-al-afia' ),
				'all_items'     => __( 'All Leads', 'amir-al-afia' ),
				'edit_item'     => __( 'Lead', 'amir-al-afia' ),
				'menu_name'     => __( 'Leads', 'amir-al-afia' ),
				'not_found'     => __( 'No leads yet.', 'amir-al-afia' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => false,
			'menu_position'   => 7,
			'menu_icon'       => 'dashicons-email-alt',
			'supports'        => array( 'title' ),
			'capability_type' => 'post',
			'capabilities'    => array(
				'create_posts' => 'do_not_allow',
			),
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'aaa_register_lead' );

/**
 * Show the submitted phone number and source page in the Leads list table.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function aaa_lead_columns( array $columns ): array {
	return array(
		'cb'         => $columns['cb'] ?? '',
		'title'      => __( 'Name', 'amir-al-afia' ),
		'aaa_phone'  => __( 'Phone', 'amir-al-afia' ),
		'aaa_source' => __( 'Sent from', 'amir-al-afia' ),
		'date'       => __( 'Received', 'amir-al-afia' ),
	);
}
add_filter( 'manage_aaa_lead_posts_columns', 'aaa_lead_columns' );

/**
 * Render the Leads list-table columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Lead ID.
 */
function aaa_lead_column_content( string $column, int $post_id ): void {
	if ( 'aaa_phone' === $column ) {
		$phone = (string) get_post_meta( $post_id, '_aaa_lead_phone', true );
		if ( '' === $phone ) {
			echo '&mdash;';
			return;
		}
		printf(
			'<a href="tel:%1$s">%2$s</a> &middot; <a href="%3$s" target="_blank" rel="noopener">%4$s</a>',
			esc_attr( aaa_digits( $phone ) ),
			esc_html( $phone ),
			esc_url( aaa_whatsapp_url( $phone ) ),
			esc_html__( 'WhatsApp', 'amir-al-afia' )
		);
	}

	if ( 'aaa_source' === $column ) {
		$source = (string) get_post_meta( $post_id, '_aaa_lead_source', true );
		if ( '' === $source ) {
			echo '&mdash;';
			return;
		}
		printf(
			'<a href="%1$s" target="_blank" rel="noopener">%2$s</a>',
			esc_url( $source ),
			esc_html( str_replace( home_url(), '', $source ) ?: '/' )
		);
	}
}
add_action( 'manage_aaa_lead_posts_custom_column', 'aaa_lead_column_content', 10, 2 );

/**
 * A count bubble on the Leads menu item for leads not yet opened.
 */
function aaa_lead_menu_bubble(): void {
	global $menu;

	$new = (int) get_option( 'aaa_unread_leads', 0 );
	if ( $new < 1 ) {
		return;
	}

	foreach ( $menu as $index => $item ) {
		if ( isset( $item[2] ) && 'edit.php?post_type=aaa_lead' === $item[2] ) {
			$menu[ $index ][0] .= sprintf(
				' <span class="awaiting-mod"><span class="pending-count">%d</span></span>',
				$new
			);
			break;
		}
	}
}
add_action( 'admin_menu', 'aaa_lead_menu_bubble', 999 );

/**
 * Clear the unread bubble once someone opens the Leads screen.
 */
function aaa_lead_mark_seen(): void {
	$screen = get_current_screen();
	if ( $screen && 'edit-aaa_lead' === $screen->id ) {
		update_option( 'aaa_unread_leads', 0 );
	}
}
add_action( 'current_screen', 'aaa_lead_mark_seen' );
