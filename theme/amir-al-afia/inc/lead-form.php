<?php
/**
 * Contact form handling.
 *
 * The mockup's form was decorative - it only toggled a success message. This
 * turns it into a real submission: nonce-checked, honeypot- and rate-limited,
 * stored as a Lead, and emailed to the office. It posts through admin-post so
 * it keeps working with JavaScript disabled; main.js upgrades it to fetch().
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

const AAA_LEAD_ACTION = 'aaa_submit_lead';

/**
 * Validate and store one submission.
 *
 * @param array<string, string> $input Raw $_POST slice.
 * @return array{ok:bool, message:string, lead_id?:int}
 */
function aaa_process_lead( array $input ): array {
	// Honeypot: a hidden field only a bot fills in. Answer as if it worked.
	if ( ! empty( $input['aaa_website'] ) ) {
		return array(
			'ok'      => true,
			'message' => aaa_option( 'aaa_contact_thanks' ),
		);
	}

	$name  = sanitize_text_field( $input['aaa_name'] ?? '' );
	$phone = sanitize_text_field( $input['aaa_phone_number'] ?? '' );

	if ( '' === $name ) {
		return array(
			'ok'      => false,
			'message' => __( 'Please enter your name.', 'amir-al-afia' ),
		);
	}

	$digits = aaa_digits( $phone );
	if ( strlen( $digits ) < 7 ) {
		return array(
			'ok'      => false,
			'message' => __( 'Please enter a valid phone number.', 'amir-al-afia' ),
		);
	}

	// One submission per IP per minute is plenty for a two-field form.
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key = 'aaa_lead_' . md5( $ip );
	if ( $ip && get_transient( $key ) ) {
		return array(
			'ok'      => false,
			'message' => __( 'You just sent a request - our team will call you shortly.', 'amir-al-afia' ),
		);
	}

	$source  = sanitize_text_field( $input['aaa_source'] ?? '' );
	$lead_id = wp_insert_post(
		array(
			'post_type'   => 'aaa_lead',
			'post_status' => 'publish',
			'post_title'  => $name,
		),
		true
	);

	if ( is_wp_error( $lead_id ) ) {
		return array(
			'ok'      => false,
			'message' => __( 'Something went wrong. Please call us instead.', 'amir-al-afia' ),
		);
	}

	update_post_meta( $lead_id, '_aaa_lead_phone', $phone );
	update_post_meta( $lead_id, '_aaa_lead_source', $source );
	update_post_meta( $lead_id, '_aaa_lead_ip', $ip );

	if ( $ip ) {
		set_transient( $key, 1, MINUTE_IN_SECONDS );
	}
	update_option( 'aaa_unread_leads', (int) get_option( 'aaa_unread_leads', 0 ) + 1 );

	aaa_notify_lead( $name, $phone, $source );

	return array(
		'ok'      => true,
		'message' => aaa_option( 'aaa_contact_thanks' ),
		'lead_id' => (int) $lead_id,
	);
}

/**
 * Email the office about a new lead.
 *
 * @param string $name   Submitted name.
 * @param string $phone  Submitted phone.
 * @param string $source Page the form was sent from.
 */
function aaa_notify_lead( string $name, string $phone, string $source ): void {
	$to = aaa_option( 'aaa_notify_email' ) ?: get_option( 'admin_email' );
	if ( ! $to || ! is_email( $to ) ) {
		return;
	}

	$subject = sprintf(
		/* translators: %s: name submitted through the contact form. */
		__( 'New property request from %s', 'amir-al-afia' ),
		$name
	);

	$body = implode(
		"\n",
		array(
			sprintf( __( 'Name: %s', 'amir-al-afia' ), $name ),
			sprintf( __( 'Phone: %s', 'amir-al-afia' ), $phone ),
			sprintf( __( 'WhatsApp: %s', 'amir-al-afia' ), aaa_whatsapp_url( $phone ) ),
			sprintf( __( 'Sent from: %s', 'amir-al-afia' ), $source ),
			'',
			sprintf(
				__( 'All leads: %s', 'amir-al-afia' ),
				admin_url( 'edit.php?post_type=aaa_lead' )
			),
		)
	);

	wp_mail( $to, $subject, $body );
}

/**
 * Handle a no-JavaScript form post and redirect back to the form.
 */
function aaa_handle_lead_post(): void {
	check_admin_referer( AAA_LEAD_ACTION, 'aaa_lead_nonce' );

	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified above.
	$result = aaa_process_lead( wp_unslash( $_POST ) );

	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified above.
	$back = isset( $_POST['_wp_http_referer'] ) ? esc_url_raw( wp_unslash( $_POST['_wp_http_referer'] ) ) : home_url( '/' );

	wp_safe_redirect(
		add_query_arg(
			array(
				'lead' => $result['ok'] ? 'ok' : 'error',
				'msg'  => rawurlencode( $result['message'] ),
			),
			remove_query_arg( array( 'lead', 'msg' ), $back )
		) . '#contact'
	);
	exit;
}
add_action( 'admin_post_nopriv_' . AAA_LEAD_ACTION, 'aaa_handle_lead_post' );
add_action( 'admin_post_' . AAA_LEAD_ACTION, 'aaa_handle_lead_post' );

/**
 * Handle the fetch() submission made by main.js.
 */
function aaa_handle_lead_ajax(): void {
	if ( ! check_ajax_referer( AAA_LEAD_ACTION, 'aaa_lead_nonce', false ) ) {
		wp_send_json(
			array(
				'ok'      => false,
				'message' => __( 'Your session expired. Please reload the page.', 'amir-al-afia' ),
			)
		);
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified above.
	wp_send_json( aaa_process_lead( wp_unslash( $_POST ) ) );
}
add_action( 'wp_ajax_nopriv_' . AAA_LEAD_ACTION, 'aaa_handle_lead_ajax' );
add_action( 'wp_ajax_' . AAA_LEAD_ACTION, 'aaa_handle_lead_ajax' );

/**
 * Show the phone number on the Lead edit screen, since the post has no editor.
 */
function aaa_lead_detail_box(): void {
	add_meta_box(
		'aaa_lead_detail',
		__( 'Lead', 'amir-al-afia' ),
		static function ( $post ): void {
			$phone  = (string) get_post_meta( $post->ID, '_aaa_lead_phone', true );
			$source = (string) get_post_meta( $post->ID, '_aaa_lead_source', true );
			echo '<p><strong>' . esc_html__( 'Phone', 'amir-al-afia' ) . ':</strong> ';
			printf( '<a href="tel:%1$s">%2$s</a>', esc_attr( aaa_digits( $phone ) ), esc_html( $phone ) );
			echo '</p>';
			printf(
				'<p><a class="button button-primary" href="%1$s" target="_blank" rel="noopener">%2$s</a></p>',
				esc_url( aaa_whatsapp_url( $phone ) ),
				esc_html__( 'Open in WhatsApp', 'amir-al-afia' )
			);
			echo '<p><strong>' . esc_html__( 'Sent from', 'amir-al-afia' ) . ':</strong> ' . esc_html( $source ?: '—' ) . '</p>';
		},
		'aaa_lead',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'aaa_lead_detail_box' );
