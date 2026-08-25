<?php
/**
 * One-click starter content.
 *
 * Sideloads the photography used in the mockup into the Media Library, then
 * creates the demo properties, agents and attractions so the landing page has
 * something to render on a fresh install. Everything it creates is ordinary
 * content the office can edit or delete.
 *
 * Images are pulled once and stored locally, so the published site never
 * hotlinks a third-party CDN.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * The photo set: a key used for lookups, plus the source URL and alt text.
 *
 * Property photography comes from Unsplash, whose licence permits commercial
 * use; it is placeholder material to be swapped for real listing photos.
 *
 * The Oman photographs are CC0 / Public Domain Mark from Wikimedia Commons,
 * chosen because the mockup's own picks were wrong — its "Historical House"
 * was the Great Sphinx and its "Desert Adventures" was Monument Valley. Every
 * image below was checked to be the place its caption claims.
 *
 * @return array<string, array<string, string>>
 */
function aaa_demo_images(): array {
	$u = 'https://images.unsplash.com/photo-';
	$q = '?w=1600&q=85&fm=jpg&fit=max';

	// Wikimedia Commons resolves this to the file and scales it server-side.
	$commons = static fn( string $file ): string =>
		'https://commons.wikimedia.org/wiki/Special:FilePath/' . rawurlencode( $file ) . '?width=1600';

	return array(
		'pool-aerial'   => array( 'url' => $u . '1571003123894-1f0594d2b5d9' . $q, 'alt' => 'Aerial view of a villa swimming pool' ),
		'villa-modern'  => array( 'url' => $u . '1600596542815-ffad4c1539a9' . $q, 'alt' => 'Modern villa exterior at dusk' ),
		'villa-pool'    => array( 'url' => $u . '1512917774080-9991f1c4c750' . $q, 'alt' => 'Villa with a lit swimming pool' ),
		'villa-grand'   => array( 'url' => $u . '1613490493576-7fde63acd811' . $q, 'alt' => 'Grand white villa with a landscaped drive' ),
		'villa-contemp' => array( 'url' => $u . '1580587771525-78b9dba3b914' . $q, 'alt' => 'Contemporary villa with floor-to-ceiling glass' ),
		'home-lux'      => array( 'url' => $u . '1570129477492-45c003edd2be' . $q, 'alt' => 'Luxury home exterior with warm lighting' ),
		'arch-modern'   => array( 'url' => $u . '1600607687939-ce8a6c25118c' . $q, 'alt' => 'Modern apartment interior' ),
		'prop-lux'      => array( 'url' => $u . '1600047509807-ba8f99d2cdde' . $q, 'alt' => 'Luxury property with a courtyard pool' ),
		'villa-white'   => array( 'url' => $u . '1600585154340-be6161a56a0c' . $q, 'alt' => 'White villa with a manicured lawn' ),

		// Oman — CC0 / Public Domain Mark, via Wikimedia Commons.
		'om-corniche' => array(
			'url' => $commons( 'Aerial view of the coastline of Muttrah.jpg' ),
			'alt' => 'The Muttrah Corniche curving along Muscat harbour beneath the Al Hajar mountains',
		),
		'om-houses'   => array(
			'url' => $commons( 'Traditional Architecture Muttrah.jpg' ),
			'alt' => 'Carved wooden balconies on traditional whitewashed houses in Muttrah',
		),
		// From the WordPress Photo Directory, which is CC0 throughout.
		'om-mosque'   => array(
			'url' => 'https://pd.w.org/2022/08/506630598190f7316.03658406-2048x1359.jpeg',
			'alt' => 'The arcaded courtyard and minaret of the Sultan Qaboos Grand Mosque, Muscat',
		),
		'om-desert'   => array(
			'url' => $commons( 'Wahiba Sands, Oman (Unsplash).jpg' ),
			'alt' => 'A desert camp under the Milky Way in the Wahiba Sands',
		),
		'om-wadi'     => array(
			'url' => $commons( 'Wadi Shab 11-2025.jpg' ),
			'alt' => 'Turquoise pools between the canyon walls of Wadi Shab',
		),
		'om-mountain' => array(
			'url' => $commons( 'Jebel Shams, Jabal Shams, Oman (Unsplash).jpg' ),
			'alt' => 'Two walkers on a ridge looking across the Jebel Shams range',
		),
	);
}

/**
 * The demo property listings.
 *
 * @return array<int, array<string, mixed>>
 */
function aaa_demo_properties(): array {
	return array(
		array(
			'title'   => 'Al Mouj Marina Apartment',
			'image'   => 'home-lux',
			'price'   => 285000,
			'beds'    => '2',
			'baths'   => '2',
			'area'    => 1200,
			'address' => 'Al Mouj, Muscat',
			'type'    => 'apartment',
			'deal'    => 'for-sale',
			'excerpt' => 'A bright two-bedroom apartment a short walk from the marina boardwalk, with a west-facing balcony over the water.',
		),
		array(
			'title'   => 'Marina View Residence',
			'image'   => 'villa-grand',
			'price'   => 400000,
			'beds'    => '3',
			'baths'   => '2',
			'area'    => 1300,
			'address' => 'Al Mouj, Muscat',
			'type'    => 'apartment',
			'deal'    => 'for-sale',
			'excerpt' => 'Corner residence on an upper floor with dual-aspect glazing, a private lift lobby and two parking bays.',
		),
		array(
			'title'   => 'Luxury Villa — Qurum',
			'image'   => 'villa-contemp',
			'price'   => 1250000,
			'beds'    => '6',
			'baths'   => '6',
			'area'    => 6700,
			'address' => 'Qurum, Muscat',
			'type'    => 'luxury',
			'deal'    => 'for-sale',
			'excerpt' => 'A six-bedroom family villa on a mature plot in Qurum, with a pool, a majlis and staff quarters.',
		),
		array(
			'title'   => 'Studio Apartment — Al Mouj',
			'image'   => 'villa-modern',
			'price'   => 9600,
			'beds'    => 'Studio',
			'baths'   => '1',
			'area'    => 620,
			'address' => 'Al Mouj, Muscat',
			'type'    => 'apartment',
			'deal'    => 'for-rent',
			'excerpt' => 'Furnished studio let annually, service charges included, with access to the pool and gym.',
		),
		array(
			'title'   => 'Garden Villa — Madinat Sultan Qaboos',
			'image'   => 'villa-white',
			'price'   => 32000,
			'beds'    => '4',
			'baths'   => '4',
			'area'    => 3800,
			'address' => 'Madinat Sultan Qaboos, Muscat',
			'type'    => 'villa',
			'deal'    => 'for-rent',
			'excerpt' => 'Four-bedroom villa with a walled garden, annual rent, available for immediate occupation.',
		),
		array(
			'title'   => 'Sea-Facing Penthouse — Shatti Al Qurum',
			'image'   => 'prop-lux',
			'price'   => 890000,
			'beds'    => '4',
			'baths'   => '4',
			'area'    => 3100,
			'address' => 'Shatti Al Qurum, Muscat',
			'type'    => 'luxury',
			'deal'    => 'for-sale',
			'excerpt' => 'Full-floor penthouse with a wraparound terrace looking north over the Gulf of Oman.',
		),
		array(
			'title'   => 'Family Villa — Azaiba',
			'image'   => 'pool-aerial',
			'price'   => 465000,
			'beds'    => '5',
			'baths'   => '5',
			'area'    => 4200,
			'address' => 'Azaiba, Muscat',
			'type'    => 'villa',
			'deal'    => 'for-sale',
			'excerpt' => 'Five-bedroom villa with a pool and covered parking, ten minutes from the airport.',
		),
		array(
			'title'   => 'Two-Bedroom Apartment — Ghubrah',
			'image'   => 'arch-modern',
			'price'   => 7200,
			'beds'    => '2',
			'baths'   => '2',
			'area'    => 1050,
			'address' => 'Ghubrah, Muscat',
			'type'    => 'apartment',
			'deal'    => 'for-rent',
			'excerpt' => 'Well-kept apartment let annually in a quiet building with a lift and allocated parking.',
		),
	);
}

/**
 * Download one image into the Media Library, or return the existing copy.
 *
 * @param string $key Image key from aaa_demo_images().
 * @return int Attachment ID, or 0 on failure.
 */
function aaa_import_image( string $key ): int {
	$images = aaa_demo_images();
	if ( ! isset( $images[ $key ] ) ) {
		return 0;
	}

	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_aaa_demo_key',
			'meta_value'     => $key,
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = download_url( $images[ $key ]['url'], 45 );
	if ( is_wp_error( $tmp ) ) {
		return 0;
	}

	$file = array(
		'name'     => 'aaa-' . $key . '.jpg',
		'tmp_name' => $tmp,
	);

	$id = media_handle_sideload( $file, 0, $images[ $key ]['alt'] );

	if ( is_wp_error( $id ) ) {
		if ( file_exists( $tmp ) ) {
			wp_delete_file( $tmp );
		}
		return 0;
	}

	update_post_meta( $id, '_aaa_demo_key', $key );
	update_post_meta( $id, '_wp_attachment_image_alt', $images[ $key ]['alt'] );

	return (int) $id;
}

/**
 * Import the team photo shipped inside the theme.
 *
 * @return int Attachment ID, or 0 on failure.
 */
function aaa_import_team_photo(): int {
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_aaa_demo_key',
			'meta_value'     => 'team-photo',
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}

	$source = AAA_DIR . '/assets/img/team-photo.png';
	if ( ! is_readable( $source ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( 'aaa-team-photo.png' );
	if ( ! $tmp || ! copy( $source, $tmp ) ) {
		return 0;
	}

	$id = media_handle_sideload(
		array(
			'name'     => 'aaa-team-photo.png',
			'tmp_name' => $tmp,
		),
		0,
		__( 'The Amir Al Afia Real Estate team', 'amir-al-afia' )
	);

	if ( is_wp_error( $id ) ) {
		if ( file_exists( $tmp ) ) {
			wp_delete_file( $tmp );
		}
		return 0;
	}

	update_post_meta( $id, '_aaa_demo_key', 'team-photo' );

	return (int) $id;
}

/**
 * Create the demo content. Safe to re-run: anything already imported is reused.
 *
 * @return array<string, int> Counts, for the admin notice.
 */
function aaa_run_demo_import(): array {
	$result = array(
		'images'      => 0,
		'properties'  => 0,
		'agents'      => 0,
		'attractions' => 0,
	);

	// --- Media ---------------------------------------------------------
	$ids = array();
	foreach ( array_keys( aaa_demo_images() ) as $key ) {
		$id = aaa_import_image( $key );
		if ( $id ) {
			$ids[ $key ] = $id;
			++$result['images'];
		}
	}

	$team_photo = aaa_import_team_photo();
	if ( $team_photo ) {
		set_theme_mod( 'aaa_team_photo', $team_photo );
	}

	// --- Hero collage --------------------------------------------------
	$collage = array( 'pool-aerial', 'villa-modern', 'villa-pool', 'villa-grand', 'villa-contemp', 'home-lux', 'arch-modern', 'prop-lux' );
	foreach ( $collage as $i => $key ) {
		if ( isset( $ids[ $key ] ) ) {
			set_theme_mod( 'aaa_collage_' . ( $i + 1 ), $ids[ $key ] );
		}
	}

	// --- Properties ----------------------------------------------------
	foreach ( aaa_demo_properties() as $index => $item ) {
		if ( get_page_by_path( sanitize_title( $item['title'] ), OBJECT, 'property' ) ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'property',
				'post_status'  => 'publish',
				'post_title'   => $item['title'],
				'post_excerpt' => $item['excerpt'],
				'post_content' => $item['excerpt'],
				'menu_order'   => $index,
			)
		);

		if ( ! $post_id || is_wp_error( $post_id ) ) {
			continue;
		}

		update_post_meta( $post_id, '_aaa_price', (string) $item['price'] );
		update_post_meta( $post_id, '_aaa_beds', (string) $item['beds'] );
		update_post_meta( $post_id, '_aaa_baths', (string) $item['baths'] );
		update_post_meta( $post_id, '_aaa_area', (string) $item['area'] );
		update_post_meta( $post_id, '_aaa_address', $item['address'] );

		// The first four are what the home page teaser shows.
		if ( $index < 4 ) {
			update_post_meta( $post_id, '_aaa_featured', '1' );
		}

		wp_set_object_terms( $post_id, $item['type'], 'property_type' );
		wp_set_object_terms( $post_id, $item['deal'], 'deal_type' );

		if ( isset( $ids[ $item['image'] ] ) ) {
			set_post_thumbnail( $post_id, $ids[ $item['image'] ] );
		}

		++$result['properties'];
	}

	// --- Agents --------------------------------------------------------
	$agents = array(
		array(
			'name'     => 'Sara Al Harthi',
			'role'     => 'Seller',
			'title'    => 'Sales Consultant',
			'phone'    => '+968 9800 2323',
			'telegram' => 'amiralafia',
		),
		array(
			'name'     => 'Khalid Al Mandhari',
			'role'     => 'Support',
			'title'    => 'Investment Advisor',
			'phone'    => '+968 9800 2121',
			'telegram' => 'amiralafia',
		),
	);

	foreach ( $agents as $index => $agent ) {
		if ( get_page_by_path( sanitize_title( $agent['name'] ), OBJECT, 'agent' ) ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'   => 'agent',
				'post_status' => 'publish',
				'post_title'  => $agent['name'],
				'menu_order'  => $index,
			)
		);

		if ( ! $post_id || is_wp_error( $post_id ) ) {
			continue;
		}

		update_post_meta( $post_id, '_aaa_agent_role', $agent['role'] );
		update_post_meta( $post_id, '_aaa_agent_title', $agent['title'] );
		update_post_meta( $post_id, '_aaa_agent_phone', $agent['phone'] );
		update_post_meta( $post_id, '_aaa_agent_telegram', $agent['telegram'] );

		++$result['agents'];
	}

	// --- Attractions ---------------------------------------------------
	$attractions = array(
		'Muttrah Corniche'           => 'om-corniche',
		'Traditional Houses'         => 'om-houses',
		'Sultan Qaboos Grand Mosque' => 'om-mosque',
		'Desert Adventures'          => 'om-desert',
		'Wadi Shab'                  => 'om-wadi',
		'Jebel Shams'                => 'om-mountain',
	);

	$index = 0;
	foreach ( $attractions as $title => $image_key ) {
		$existing = get_page_by_path( sanitize_title( $title ), OBJECT, 'attraction' );

		if ( $existing ) {
			// Re-running should repair a tile whose photo was replaced.
			if ( isset( $ids[ $image_key ] ) ) {
				set_post_thumbnail( $existing->ID, $ids[ $image_key ] );
			}
			++$index;
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'   => 'attraction',
				'post_status' => 'publish',
				'post_title'  => $title,
				'menu_order'  => $index,
			)
		);

		if ( $post_id && ! is_wp_error( $post_id ) ) {
			if ( isset( $ids[ $image_key ] ) ) {
				set_post_thumbnail( $post_id, $ids[ $image_key ] );
			}
			++$result['attractions'];
		}

		++$index;
	}

	aaa_prune_stale_demo_media();

	update_option( 'aaa_demo_imported', gmdate( 'c' ) );

	return $result;
}

/**
 * Delete demo attachments this theme imported under a key it no longer uses.
 *
 * Scoped deliberately narrowly: it only ever touches attachments carrying a
 * `_aaa_demo_key` this importer wrote, and only when that key has dropped out
 * of aaa_demo_images(). Media the office uploaded is never considered.
 *
 * @return int How many were removed.
 */
function aaa_prune_stale_demo_media(): int {
	$current = array_keys( aaa_demo_images() );
	$current[] = 'team-photo';

	$attachments = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_key'       => '_aaa_demo_key',
		)
	);

	$removed = 0;
	foreach ( $attachments as $id ) {
		$key = (string) get_post_meta( $id, '_aaa_demo_key', true );
		if ( in_array( $key, $current, true ) ) {
			continue;
		}
		if ( wp_delete_attachment( (int) $id, true ) ) {
			++$removed;
		}
	}

	return $removed;
}

/**
 * Add the importer under Appearance.
 */
function aaa_demo_menu(): void {
	add_theme_page(
		__( 'Starter Content', 'amir-al-afia' ),
		__( 'Starter Content', 'amir-al-afia' ),
		'edit_theme_options',
		'aaa-starter-content',
		'aaa_render_demo_page'
	);
}
add_action( 'admin_menu', 'aaa_demo_menu' );

/**
 * Render the Starter Content screen.
 */
function aaa_render_demo_page(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$done = null;

	if ( isset( $_POST['aaa_import_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['aaa_import_nonce'] ) ), 'aaa_import_demo' ) ) {
		set_time_limit( 300 );
		$done = aaa_run_demo_import();
	}

	$imported = get_option( 'aaa_demo_imported' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Starter Content', 'amir-al-afia' ); ?></h1>

		<?php if ( $done ) : ?>
			<div class="notice notice-success">
				<p>
					<?php
					printf(
						/* translators: 1: image count, 2: property count, 3: agent count, 4: attraction count. */
						esc_html__( 'Imported %1$d images, %2$d properties, %3$d agents and %4$d attractions.', 'amir-al-afia' ),
						(int) $done['images'],
						(int) $done['properties'],
						(int) $done['agents'],
						(int) $done['attractions']
					);
					?>
				</p>
			</div>
		<?php endif; ?>

		<p>
			<?php esc_html_e( 'Downloads the photography used in the design into your Media Library, then creates the demo properties, agents and attractions so the home page has content to show. Everything it creates is normal content you can edit or delete.', 'amir-al-afia' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'Running it twice is safe — anything already imported is reused rather than duplicated. Replace the demo photos with real listing photography before launch.', 'amir-al-afia' ); ?>
		</p>

		<?php if ( $imported ) : ?>
			<p>
				<em>
					<?php
					printf(
						/* translators: %s: date the import last ran. */
						esc_html__( 'Last run: %s', 'amir-al-afia' ),
						esc_html( (string) $imported )
					);
					?>
				</em>
			</p>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'aaa_import_demo', 'aaa_import_nonce' ); ?>
			<p>
				<button type="submit" class="button button-primary">
					<?php esc_html_e( 'Import starter content', 'amir-al-afia' ); ?>
				</button>
			</p>
		</form>
	</div>
	<?php
}
