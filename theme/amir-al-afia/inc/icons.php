<?php
/**
 * Inline icon set lifted from the design mockup.
 *
 * Icons are inlined rather than sprited because the page needs fewer than
 * twenty of them and inlining avoids a second request plus a fragment-id
 * dependency inside `use` elements.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return an icon's SVG markup.
 *
 * @param string $name  Icon key.
 * @param int    $size  Square size in px.
 * @param string $color Stroke or fill colour; `currentColor` follows the text.
 */
function aaa_get_icon( string $name, int $size = 16, string $color = 'currentColor' ): string {
	$stroke = sprintf( 'fill="none" stroke="%s" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"', esc_attr( $color ) );
	$fill   = sprintf( 'fill="%s"', esc_attr( $color ) );

	$paths = array(
		'phone'    => '<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10.8 19.79 19.79 0 01.07 2.18 2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z" ' . $stroke . '/>',
		'whatsapp' => '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" ' . $fill . '/>',
		'telegram' => '<path d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" ' . $fill . '/>',
		'email'    => '<rect x="2" y="4" width="20" height="16" rx="2" ' . $stroke . '/><path d="m2 7 10 6 10-6" ' . $stroke . '/>',
		'home'     => '<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" ' . $stroke . '/><polyline points="9 22 9 12 15 12 15 22" ' . $stroke . '/>',
		'bed'      => '<path d="M2 20v-7a2 2 0 012-2h16a2 2 0 012 2v7M2 20h20M12 11V4M8 4h8" ' . $stroke . '/>',
		'bath'     => '<path d="M9 6H4a1 1 0 00-1 1v9h18V7a1 1 0 00-1-1h-5M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2M9 6h6" ' . $stroke . '/>',
		'area'     => '<rect x="3" y="3" width="18" height="18" rx="1" ' . $stroke . '/>',
		'send'     => '<line x1="22" y1="2" x2="11" y2="13" ' . $stroke . '/><polygon points="22 2 15 22 11 13 2 9 22 2" ' . $stroke . '/>',
		'arrow-ne' => '<path d="M7 17L17 7M7 7h10v10" ' . $stroke . '/>',
		'arrow-r'  => '<path d="M5 12h14M13 6l6 6-6 6" ' . $stroke . '/>',
		'tax'      => '<circle cx="9" cy="9" r="3" ' . $stroke . '/><circle cx="15" cy="15" r="3" ' . $stroke . '/><line x1="5" y1="19" x2="19" y2="5" ' . $stroke . '/>',
		'passport' => '<rect x="3" y="4" width="18" height="16" rx="2" ' . $stroke . '/><circle cx="9" cy="11" r="2.5" ' . $stroke . '/><path d="M13 10h4M13 13h4" ' . $stroke . '/><path d="M5 17.5c0-1.657 1.567-3 3.5-3h1c1.933 0 3.5 1.343 3.5 3" ' . $stroke . '/>',
		'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2" ' . $stroke . '/><path d="M3 10h18" ' . $stroke . '/><path d="M8 3v4M16 3v4" ' . $stroke . '/><path d="M7 14h4M13 14h4M7 17h2" ' . $stroke . '/>',
		'growth'   => '<polyline points="22 7 13.5 15.5 8.5 10.5 2 17" ' . $stroke . '/><polyline points="16 7 22 7 22 13" ' . $stroke . '/>',
	);

	// The pin is authored on a 13x16 grid, so it carries its own viewBox.
	if ( 'pin' === $name ) {
		return sprintf(
			'<svg width="%1$d" height="%2$d" viewBox="0 0 13 16" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M6.5 0C2.91 0 0 2.91 0 6.5c0 4.875 6.5 9.75 6.5 9.75S13 11.375 13 6.5C13 2.91 10.09 0 6.5 0zm0 8.85a2.35 2.35 0 110-4.7 2.35 2.35 0 010 4.7z" fill="%3$s"/></svg>',
			(int) round( $size * 13 / 16 ),
			$size,
			esc_attr( $color )
		);
	}

	if ( 'dot' === $name ) {
		return sprintf(
			'<svg width="%1$d" height="%1$d" viewBox="0 0 7 7" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><circle cx="3.5" cy="3.5" r="3.5" fill="%2$s"/></svg>',
			$size,
			esc_attr( $color )
		);
	}

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg width="%1$d" height="%1$d" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">%2$s</svg>',
		$size,
		$paths[ $name ]
	);
}

/**
 * Echo an icon. Markup is theme-authored and already escaped where it matters.
 */
function aaa_icon( string $name, int $size = 16, string $color = 'currentColor' ): void {
	echo aaa_get_icon( $name, $size, $color ); // phpcs:ignore WordPress.Security.EscapeOutput
}

/**
 * The Oman flag used by the phone-number prefix on the lead form.
 */
function aaa_flag_om(): void {
	echo '<svg width="20" height="14" viewBox="0 0 20 14" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">'
		. '<rect width="20" height="14" rx="1" fill="#fff"/>'
		. '<rect width="20" height="4.67" fill="#DB161B"/>'
		. '<rect y="9.33" width="20" height="4.67" fill="#008000"/>'
		. '<rect width="6" height="14" fill="#DB161B"/>'
		. '</svg>';
}
