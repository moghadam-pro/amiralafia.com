<?php
/**
 * Lead-capture form.
 *
 * A real <form> posting to admin-post.php, so it submits with JavaScript off.
 * main.js intercepts the submit and posts it through fetch() instead.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

// Feedback from the no-JavaScript round trip.
// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only display of our own redirect args.
$aaa_state   = isset( $_GET['lead'] ) ? sanitize_key( wp_unslash( $_GET['lead'] ) ) : '';
$aaa_message = isset( $_GET['msg'] ) ? sanitize_text_field( rawurldecode( wp_unslash( $_GET['msg'] ) ) ) : '';
// phpcs:enable WordPress.Security.NonceVerification.Recommended
?>
<section class="contact-section" id="contact" aria-labelledby="contact-heading">
	<div class="container">
		<div class="contact-inner">

			<h2 id="contact-heading" <?php aaa_sr( 0, 'section-title' ); ?>><?php aaa_the_option( 'aaa_contact_title' ); ?></h2>
			<p <?php aaa_sr( 80, 'section-sub' ); ?>><?php aaa_the_option( 'aaa_contact_sub' ); ?></p>

			<form
				class="contact-form <?php echo esc_attr( 'sr' ); ?>"
				data-delay="160"
				id="aaa-lead-form"
				method="post"
				action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

				<input type="hidden" name="action" value="<?php echo esc_attr( AAA_LEAD_ACTION ); ?>">
				<input type="hidden" name="aaa_source" value="<?php echo esc_attr( get_the_title() ?: get_bloginfo( 'name' ) ); ?>">
				<?php wp_nonce_field( AAA_LEAD_ACTION, 'aaa_lead_nonce' ); ?>

				<label class="screen-reader-text" for="cf-name"><?php esc_html_e( 'Your name', 'amir-al-afia' ); ?></label>
				<input
					class="cf-input"
					id="cf-name"
					name="aaa_name"
					type="text"
					required
					autocomplete="name"
					placeholder="<?php esc_attr_e( 'Your name', 'amir-al-afia' ); ?>">

				<div class="cf-phone">
					<span class="cf-phone-prefix">
						<?php aaa_flag_om(); ?>
						<span>+968</span>
					</span>
					<label class="screen-reader-text" for="cf-phone"><?php esc_html_e( 'Your phone number', 'amir-al-afia' ); ?></label>
					<input
						type="tel"
						id="cf-phone"
						name="aaa_phone_number"
						required
						inputmode="tel"
						autocomplete="tel"
						placeholder="<?php esc_attr_e( 'Your phone number', 'amir-al-afia' ); ?>">
				</div>

				<?php // Honeypot: hidden from people, tempting to bots. ?>
				<div class="cf-hp" aria-hidden="true">
					<label for="cf-website"><?php esc_html_e( 'Leave this field empty', 'amir-al-afia' ); ?></label>
					<input type="text" id="cf-website" name="aaa_website" tabindex="-1" autocomplete="off">
				</div>

				<button class="cf-submit" id="cf-submit" type="submit">
					<?php aaa_icon( 'send', 16, '#fff' ); ?>
					<span><?php esc_html_e( 'Send Request', 'amir-al-afia' ); ?></span>
				</button>
			</form>

			<p
				class="cf-feedback<?php echo $aaa_message ? ' show' : ''; ?><?php echo 'error' === $aaa_state ? ' is-error' : ''; ?>"
				id="cf-feedback"
				role="status"
				aria-live="polite"><?php echo esc_html( $aaa_message ); ?></p>

		</div>
	</div>
</section>
