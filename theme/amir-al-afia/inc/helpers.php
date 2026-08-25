<?php
/**
 * Small shared helpers.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Print one of the theme's inline SVG partials (logo, logo mark).
 * These are static files we authored, so the markup is trusted.
 */
function aaa_inline_svg( string $name ): void {
	$path = AAA_DIR . '/inc/' . $name . '-inline.svg';
	if ( ! is_readable( $path ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.EscapeOutput -- static, theme-authored SVG.
	echo file_get_contents( $path );
}

/**
 * The site logo: a Customizer custom logo when set, otherwise the brand SVG.
 */
function aaa_brand_logo(): void {
	if ( has_custom_logo() ) {
		$id  = (int) get_theme_mod( 'custom_logo' );
		$img = wp_get_attachment_image( $id, 'full', false, array(
			'class' => 'brand-logo',
			'alt'   => get_bloginfo( 'name', 'display' ),
		) );
		if ( $img ) {
			echo $img; // phpcs:ignore WordPress.Security.EscapeOutput -- wp_get_attachment_image escapes.
			return;
		}
	}
	aaa_inline_svg( 'logo' );
}

/**
 * Digits only, for tel: and wa.me / t.me links.
 */
function aaa_digits( string $value ): string {
	return preg_replace( '/\D+/', '', $value );
}

/**
 * A WhatsApp deep link for a phone number in any human format.
 */
function aaa_whatsapp_url( string $phone ): string {
	return 'https://wa.me/' . aaa_digits( $phone );
}

/**
 * A Telegram link. Accepts either a @handle or a bare username.
 */
function aaa_telegram_url( string $handle ): string {
	$handle = ltrim( trim( $handle ), '@' );
	if ( '' === $handle ) {
		return '';
	}
	if ( str_starts_with( $handle, 'http' ) ) {
		return esc_url_raw( $handle );
	}
	return 'https://t.me/' . rawurlencode( $handle );
}

/**
 * Format a price the way the design shows it: a currency symbol and thousands
 * separators, no decimals. Falls back to the raw string for "On request" etc.
 */
function aaa_format_price( $price, string $currency = '' ): string {
	$currency = $currency ?: (string) get_theme_mod( 'aaa_currency', '$' );
	if ( '' === (string) $price ) {
		return __( 'Price on request', 'amir-al-afia' );
	}
	if ( ! is_numeric( $price ) ) {
		return (string) $price;
	}
	return $currency . number_format_i18n( (float) $price, 0 );
}

/**
 * Print the attributes for a scroll-reveal element:
 * `class="sr …" data-delay="80"`.
 *
 * @param int    $delay Reveal delay in milliseconds.
 * @param string $extra Additional classes.
 */
function aaa_sr( int $delay = 0, string $extra = '' ): void {
	printf(
		'class="%s" data-delay="%d"',
		esc_attr( trim( 'sr ' . $extra ) ),
		$delay
	);
}

/**
 * A placeholder image URL for property cards with no featured image set.
 */
function aaa_placeholder_image(): string {
	return AAA_URI . '/assets/img/placeholder.svg';
}

/**
 * A photo credit line for an attachment, or '' when none is recorded.
 *
 * The starter-content importer stores the photographer, the licence and the
 * source page on each image it brings in. Creative Commons BY and BY-SA both
 * require that credit to be shown wherever the photo is; CC0 and public-domain
 * images carry it too, because knowing where a photo came from is useful even
 * when it is not legally required.
 *
 * @param int $attachment_id Attachment to describe.
 */
function aaa_media_credit( int $attachment_id ): string {
	$author  = (string) get_post_meta( $attachment_id, '_aaa_credit_author', true );
	$license = (string) get_post_meta( $attachment_id, '_aaa_credit_license', true );
	$source  = (string) get_post_meta( $attachment_id, '_aaa_credit_source', true );

	if ( '' === $author && '' === $license ) {
		return '';
	}

	$name = $author ?: __( 'Unknown', 'amir-al-afia' );
	if ( $source ) {
		$name = sprintf(
			'<a href="%s" target="_blank" rel="noopener nofollow">%s</a>',
			esc_url( $source ),
			esc_html( $name )
		);
	} else {
		$name = esc_html( $name );
	}

	if ( '' === $license ) {
		/* translators: %s: photographer name, possibly linked. */
		return sprintf( __( 'Photo: %s', 'amir-al-afia' ), $name );
	}

	return sprintf(
		/* translators: 1: photographer name, possibly linked. 2: licence name. */
		__( 'Photo: %1$s (%2$s)', 'amir-al-afia' ),
		$name,
		esc_html( $license )
	);
}
