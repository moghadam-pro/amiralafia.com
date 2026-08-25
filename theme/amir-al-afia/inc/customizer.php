<?php
/**
 * Customizer settings.
 *
 * Everything the office is likely to change - phone numbers, social handles,
 * headline copy, the three hero stats - lives here rather than in a template,
 * so no code edit is ever needed to update the site.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default values, also used as the fallback in aaa_option().
 *
 * @return array<string, string>
 */
function aaa_defaults(): array {
	return array(
		'aaa_phone'          => '+968 98059195',
		'aaa_whatsapp'       => '+968 98059195',
		'aaa_telegram'       => 'amiralafia',
		'aaa_email'          => '',
		'aaa_city'           => 'Muscat, Oman',
		'aaa_currency'       => '$',

		'aaa_hero_line1'     => 'Find Your',
		'aaa_hero_line2'     => 'Dream Property',
		'aaa_hero_line3'     => 'On Your Dream Land',
		'aaa_hero_desc'      => "Premium villas, apartments, and investment properties across Muscat and Oman's finest locations. Buy or rent with confidence.",

		'aaa_stat1_value'    => '+200',
		'aaa_stat1_label'    => 'Properties',
		'aaa_stat2_value'    => '+12',
		'aaa_stat2_label'    => 'Years Active',
		'aaa_stat3_value'    => '98%',
		'aaa_stat3_label'    => 'Satisfaction',

		'aaa_props_tag'      => 'Featured Listings',
		'aaa_props_title'    => 'Properties For You',
		'aaa_props_sub'      => "Handpicked villas and apartments across Oman's most sought-after areas, from Muscat to Al Mouj Marina.",
		'aaa_props_count'    => '4',

		'aaa_inv_tag'        => 'Why Oman',
		'aaa_inv_title'      => 'A Market Built For Investors',
		'aaa_inv_sub'        => 'Oman offers stable economic growth, 0% income tax, and a rapidly expanding real estate sector open to foreign buyers.',

		'aaa_team_tag'       => 'Our Experts',
		'aaa_team_title'     => 'Meet The Team',
		'aaa_team_sub'       => 'Our dedicated agents are available online and in person, ready to guide you through every step of buying or renting in Oman.',

		'aaa_contact_title'  => 'Get In Touch & Updates',
		'aaa_contact_sub'    => 'Fill out the form, get new updates about properties and projects.',
		'aaa_contact_thanks' => "Request sent! We'll contact you within 24 hours.",

		'aaa_attr_title'     => 'Attractions & Nature of Oman',

		'aaa_cta_title'      => 'Ready To Find Your Property?',
		'aaa_cta_sub'        => 'Fill out a quick request and our team will match you with the best available properties within 24 hours.',

		'aaa_notify_email'   => '',

		'aaa_inv1_title'     => 'Tax-Free Returns',
		'aaa_inv1_text'      => 'No capital gains tax or income tax — investors keep 100% of their rental yields and property appreciation.',
		'aaa_inv2_title'     => 'Residency Visa',
		'aaa_inv2_text'      => 'Purchase property above $130,000 and qualify for an Oman residency visa for you and your family.',
		'aaa_inv3_title'     => 'Flexible Payment Plans',
		'aaa_inv3_text'      => 'Easy payment plans from 0% down with attractive installment options stretching up to four years.',
		'aaa_inv4_title'     => 'Stable Growth',
		'aaa_inv4_text'      => "Oman's real estate sector has consistently grown 5–7% annually, driven by tourism and infrastructure investment.",
	);
}

/**
 * The four "Why Oman" cards: icon key plus the Customizer keys behind them.
 *
 * @return array<int, array<string, string>>
 */
function aaa_investor_cards(): array {
	$cards = array();
	$icons = array( 1 => 'tax', 2 => 'passport', 3 => 'calendar', 4 => 'growth' );

	foreach ( $icons as $i => $icon ) {
		$cards[] = array(
			'icon'  => $icon,
			'title' => aaa_option( 'aaa_inv' . $i . '_title' ),
			'text'  => aaa_option( 'aaa_inv' . $i . '_text' ),
		);
	}

	return $cards;
}

/**
 * Read a Customizer value, falling back to the shipped default.
 *
 * @param string $key Setting key.
 * @return string
 */
function aaa_option( string $key ): string {
	$defaults = aaa_defaults();
	return (string) get_theme_mod( $key, $defaults[ $key ] ?? '' );
}

/**
 * Echo a Customizer value, escaped for HTML text.
 *
 * @param string $key Setting key.
 */
function aaa_the_option( string $key ): void {
	echo esc_html( aaa_option( $key ) );
}

/**
 * Register the theme's Customizer panels, sections and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function aaa_customize_register( $wp_customize ): void {
	$defaults = aaa_defaults();

	$wp_customize->add_panel(
		'aaa_panel',
		array(
			'title'       => __( 'Amir Al Afia', 'amir-al-afia' ),
			'description' => __( 'Contact details and the copy used across the landing page.', 'amir-al-afia' ),
			'priority'    => 20,
		)
	);

	/**
	 * Add one text control and its setting in a single call.
	 *
	 * @param string $section Section id.
	 * @param string $key     Setting key.
	 * @param string $label   Control label.
	 * @param string $type    Control type: text, textarea, email, url.
	 */
	$add = static function ( string $section, string $key, string $label, string $type = 'text' ) use ( $wp_customize, $defaults ): void {
		$sanitize = 'textarea' === $type ? 'sanitize_textarea_field' : 'sanitize_text_field';
		if ( 'email' === $type ) {
			$sanitize = 'sanitize_email';
		}
		if ( 'url' === $type ) {
			$sanitize = 'esc_url_raw';
		}

		$wp_customize->add_setting(
			$key,
			array(
				'default'           => $defaults[ $key ] ?? '',
				'sanitize_callback' => $sanitize,
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'   => $label,
				'section' => $section,
				'type'    => $type,
			)
		);
	};

	// --- Contact -------------------------------------------------------
	$wp_customize->add_section(
		'aaa_contact',
		array(
			'title' => __( 'Contact details', 'amir-al-afia' ),
			'panel' => 'aaa_panel',
		)
	);
	$add( 'aaa_contact', 'aaa_phone', __( 'Phone number', 'amir-al-afia' ) );
	$add( 'aaa_contact', 'aaa_whatsapp', __( 'WhatsApp number', 'amir-al-afia' ) );
	$add( 'aaa_contact', 'aaa_telegram', __( 'Telegram username', 'amir-al-afia' ) );
	$add( 'aaa_contact', 'aaa_email', __( 'Public email address', 'amir-al-afia' ), 'email' );
	$add( 'aaa_contact', 'aaa_notify_email', __( 'Send new leads to', 'amir-al-afia' ), 'email' );
	$add( 'aaa_contact', 'aaa_city', __( 'City label', 'amir-al-afia' ) );
	$add( 'aaa_contact', 'aaa_currency', __( 'Price currency symbol', 'amir-al-afia' ) );

	// --- Hero ----------------------------------------------------------
	$wp_customize->add_section(
		'aaa_hero',
		array(
			'title' => __( 'Hero', 'amir-al-afia' ),
			'panel' => 'aaa_panel',
		)
	);
	$add( 'aaa_hero', 'aaa_hero_line1', __( 'Heading line 1 (cyan)', 'amir-al-afia' ) );
	$add( 'aaa_hero', 'aaa_hero_line2', __( 'Heading line 2 (dark)', 'amir-al-afia' ) );
	$add( 'aaa_hero', 'aaa_hero_line3', __( 'Heading line 3 (cyan)', 'amir-al-afia' ) );
	$add( 'aaa_hero', 'aaa_hero_desc', __( 'Intro paragraph', 'amir-al-afia' ), 'textarea' );
	$add( 'aaa_hero', 'aaa_stat1_value', __( 'Stat 1 value', 'amir-al-afia' ) );
	$add( 'aaa_hero', 'aaa_stat1_label', __( 'Stat 1 label', 'amir-al-afia' ) );
	$add( 'aaa_hero', 'aaa_stat2_value', __( 'Stat 2 value', 'amir-al-afia' ) );
	$add( 'aaa_hero', 'aaa_stat2_label', __( 'Stat 2 label', 'amir-al-afia' ) );
	$add( 'aaa_hero', 'aaa_stat3_value', __( 'Stat 3 value', 'amir-al-afia' ) );
	$add( 'aaa_hero', 'aaa_stat3_label', __( 'Stat 3 label', 'amir-al-afia' ) );

	// --- Section copy --------------------------------------------------
	$wp_customize->add_section(
		'aaa_sections',
		array(
			'title' => __( 'Section headings', 'amir-al-afia' ),
			'panel' => 'aaa_panel',
		)
	);
	$add( 'aaa_sections', 'aaa_props_tag', __( 'Properties: badge', 'amir-al-afia' ) );
	$add( 'aaa_sections', 'aaa_props_title', __( 'Properties: heading', 'amir-al-afia' ) );
	$add( 'aaa_sections', 'aaa_props_sub', __( 'Properties: intro', 'amir-al-afia' ), 'textarea' );
	$add( 'aaa_sections', 'aaa_props_count', __( 'Properties: how many cards', 'amir-al-afia' ) );
	$add( 'aaa_sections', 'aaa_inv_tag', __( 'Why Oman: badge', 'amir-al-afia' ) );
	$add( 'aaa_sections', 'aaa_inv_title', __( 'Why Oman: heading', 'amir-al-afia' ) );
	$add( 'aaa_sections', 'aaa_inv_sub', __( 'Why Oman: intro', 'amir-al-afia' ), 'textarea' );
	for ( $i = 1; $i <= 4; $i++ ) {
		/* translators: %d: card number. */
		$add( 'aaa_sections', 'aaa_inv' . $i . '_title', sprintf( __( 'Why Oman card %d: title', 'amir-al-afia' ), $i ) );
		/* translators: %d: card number. */
		$add( 'aaa_sections', 'aaa_inv' . $i . '_text', sprintf( __( 'Why Oman card %d: text', 'amir-al-afia' ), $i ), 'textarea' );
	}
	$add( 'aaa_sections', 'aaa_team_tag', __( 'Team: badge', 'amir-al-afia' ) );
	$add( 'aaa_sections', 'aaa_team_title', __( 'Team: heading', 'amir-al-afia' ) );
	$add( 'aaa_sections', 'aaa_team_sub', __( 'Team: intro', 'amir-al-afia' ), 'textarea' );
	$add( 'aaa_sections', 'aaa_contact_title', __( 'Contact: heading', 'amir-al-afia' ) );
	$add( 'aaa_sections', 'aaa_contact_sub', __( 'Contact: intro', 'amir-al-afia' ), 'textarea' );
	$add( 'aaa_sections', 'aaa_contact_thanks', __( 'Contact: success message', 'amir-al-afia' ), 'textarea' );
	$add( 'aaa_sections', 'aaa_attr_title', __( 'Attractions: heading', 'amir-al-afia' ) );
	$add( 'aaa_sections', 'aaa_cta_title', __( 'Closing band: heading', 'amir-al-afia' ) );
	$add( 'aaa_sections', 'aaa_cta_sub', __( 'Closing band: intro', 'amir-al-afia' ), 'textarea' );

	// --- Team photo ----------------------------------------------------
	$wp_customize->add_section(
		'aaa_media',
		array(
			'title' => __( 'Images', 'amir-al-afia' ),
			'panel' => 'aaa_panel',
		)
	);
	$wp_customize->add_setting(
		'aaa_team_photo',
		array(
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'aaa_team_photo',
			array(
				'label'     => __( 'Team photo', 'amir-al-afia' ),
				'section'   => 'aaa_media',
				'mime_type' => 'image',
			)
		)
	);

	for ( $i = 1; $i <= 8; $i++ ) {
		$wp_customize->add_setting(
			'aaa_collage_' . $i,
			array(
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				'aaa_collage_' . $i,
				array(
					/* translators: %d: collage cell number. */
					'label'     => sprintf( __( 'Hero collage image %d', 'amir-al-afia' ), $i ),
					'section'   => 'aaa_media',
					'mime_type' => 'image',
				)
			)
		);
	}
}
add_action( 'customize_register', 'aaa_customize_register' );
