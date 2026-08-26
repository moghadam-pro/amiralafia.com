<?php
/**
 * Inline icon set.
 *
 * Heroicons v2 (MIT, https://heroicons.com), the 24px outline set: a 24x24
 * grid, 1.5 stroke, round caps and joins, painted with currentColor. The
 * wrapper below sets those attributes once rather than repeating them on every
 * path, so the stored markup is just geometry.
 *
 * Two gaps, both deliberate:
 *
 *   - Heroicons has no brand marks, so WhatsApp and Telegram keep their own
 *     glyphs. Those are how people recognise the service; a generic chat
 *     bubble would be worse, not more consistent.
 *   - Heroicons has no bed or bath icon - it carries no furniture or fixtures
 *     at all. Both are drawn here to the same spec (24x24, 1.5 stroke, round
 *     caps) so they sit in the row with the others without looking borrowed.
 *
 * Icons are inlined rather than sprited because the page uses fewer than
 * twenty and inlining avoids both a request and a fragment-id dependency.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Heroicons outline geometry, keyed by the name the theme calls it.
 *
 * @return array<string, string>
 */
function aaa_icon_paths(): array {
	return array(
		'phone'      => '<path d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>',
		'email'      => '<path d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>',
		'home'       => '<path d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>',
		'area'       => '<path d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/>',
		'send'       => '<path d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/>',
		'arrow-ne'   => '<path d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25"/>',
		'arrow-r'    => '<path d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>',
		'tax'        => '<path d="m9 14.25 6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0c1.1.128 1.907 1.077 1.907 2.185ZM9.75 9h.008v.008H9.75V9Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm4.125 4.5h.008v.008h-.008V13.5Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>',
		'passport'   => '<path d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z"/>',
		'calendar'   => '<path d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/>',
		'growth'     => '<path d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/>',
		'pin'        => '<path d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/> <path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>',
		// Not in Heroicons; drawn to the same 24x24 / 1.5-stroke spec.
		'bed'      => '<path d="M2.25 18.75V9a2.25 2.25 0 0 1 2.25-2.25h15A2.25 2.25 0 0 1 21.75 9v9.75M2.25 15.75h19.5M2.25 18.75h19.5M6 12.75h3.75a1.5 1.5 0 0 1 1.5 1.5v1.5H4.5v-1.5a1.5 1.5 0 0 1 1.5-1.5Z"/>',
		'bath'     => '<path d="M3 12.75h18v1.5a5.25 5.25 0 0 1-5.25 5.25h-7.5A5.25 5.25 0 0 1 3 14.25v-1.5ZM4.5 12.75V6a1.875 1.875 0 0 1 3.75 0M6.75 8.25h3M6.75 19.5 5.25 21.75M17.25 19.5l1.5 2.25"/>',
	);
}

/**
 * Return an icon's SVG markup.
 *
 * @param string $name  Icon key.
 * @param int    $size  Square size in px.
 * @param string $color Stroke colour; `currentColor` follows the text.
 */
function aaa_get_icon( string $name, int $size = 16, string $color = 'currentColor' ): string {
	// The brand marks are solid glyphs on their own geometry, so they are
	// rendered separately from the stroked Heroicons.
	$brand = array(
		'whatsapp' => '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>',
		'telegram' => '<path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>',
	);

	if ( isset( $brand[ $name ] ) ) {
		return sprintf(
			'<svg width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="%2$s" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">%3$s</svg>',
			$size,
			esc_attr( $color ),
			$brand[ $name ]
		);
	}

	// A plain dot, used as a bullet in the section eyebrows.
	if ( 'dot' === $name ) {
		return sprintf(
			'<svg width="%1$d" height="%1$d" viewBox="0 0 8 8" fill="%2$s" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><circle cx="4" cy="4" r="4"/></svg>',
			$size,
			esc_attr( $color )
		);
	}

	$paths = aaa_icon_paths();

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="%2$s" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">%3$s</svg>',
		$size,
		esc_attr( $color ),
		$paths[ $name ]
	);
}

/**
 * Echo an icon. Markup is theme-authored and already escaped where it matters.
 *
 * @param string $name  Icon key.
 * @param int    $size  Square size in px.
 * @param string $color Stroke colour.
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
