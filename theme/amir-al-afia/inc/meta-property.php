<?php
/**
 * Property detail fields - price, beds, baths, area, address, gallery.
 *
 * Implemented with a plain meta box rather than a field plugin so the theme
 * stays dependency-free.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field definitions, shared by the meta box, the save handler and the
 * register_post_meta() calls that expose them to the REST API.
 *
 * @return array<string, array<string, string>>
 */
function aaa_property_fields(): array {
	return array(
		'_aaa_price'    => array(
			'label' => __( 'Price', 'amir-al-afia' ),
			'type'  => 'number',
			'desc'  => __( 'Numbers only. Leave empty to show "Price on request".', 'amir-al-afia' ),
		),
		'_aaa_beds'     => array(
			'label' => __( 'Bedrooms', 'amir-al-afia' ),
			'type'  => 'text',
			'desc'  => __( 'A number, or a word such as "Studio".', 'amir-al-afia' ),
		),
		'_aaa_baths'    => array(
			'label' => __( 'Bathrooms', 'amir-al-afia' ),
			'type'  => 'number',
		),
		'_aaa_area'     => array(
			'label' => __( 'Area (sqft)', 'amir-al-afia' ),
			'type'  => 'number',
		),
		'_aaa_address'  => array(
			'label' => __( 'Address line', 'amir-al-afia' ),
			'type'  => 'text',
			'desc'  => __( 'Shown under the title, e.g. "Al Mouj, Muscat".', 'amir-al-afia' ),
		),
		'_aaa_map'      => array(
			'label' => __( 'Google Maps link', 'amir-al-afia' ),
			'type'  => 'url',
		),
		'_aaa_featured' => array(
			'label' => __( 'Feature on the home page', 'amir-al-afia' ),
			'type'  => 'checkbox',
		),
	);
}

/**
 * Expose the fields to the REST API so the block editor and any future
 * headless client can read them.
 */
function aaa_register_property_meta(): void {
	foreach ( aaa_property_fields() as $key => $field ) {
		register_post_meta(
			'property',
			$key,
			array(
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => 'checkbox' === $field['type'] ? 'boolean' : 'string',
				'sanitize_callback' => 'checkbox' === $field['type'] ? 'rest_sanitize_boolean' : 'sanitize_text_field',
				'auth_callback'     => 'aaa_meta_auth',
			)
		);
	}

	register_post_meta(
		'property',
		'_aaa_gallery',
		array(
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => 'aaa_meta_auth',
		)
	);
}
add_action( 'init', 'aaa_register_property_meta' );

/**
 * Who may read and write the protected property meta over REST.
 */
function aaa_meta_auth(): bool {
	return current_user_can( 'edit_posts' );
}

/**
 * Add the Property Details meta box.
 */
function aaa_property_meta_box(): void {
	add_meta_box(
		'aaa_property_details',
		__( 'Property Details', 'amir-al-afia' ),
		'aaa_render_property_meta_box',
		'property',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'aaa_property_meta_box' );

/**
 * Render the Property Details meta box.
 *
 * @param WP_Post $post Post being edited.
 */
function aaa_render_property_meta_box( $post ): void {
	wp_nonce_field( 'aaa_save_property', 'aaa_property_nonce' );
	$gallery = (string) get_post_meta( $post->ID, '_aaa_gallery', true );
	?>
	<div class="aaa-fields">
		<?php foreach ( aaa_property_fields() as $key => $field ) : ?>
			<?php $value = get_post_meta( $post->ID, $key, true ); ?>
			<div class="aaa-field">
				<?php if ( 'checkbox' === $field['type'] ) : ?>
					<label for="<?php echo esc_attr( $key ); ?>">
						<input type="checkbox" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="1" <?php checked( $value, '1' ); ?>>
						<?php echo esc_html( $field['label'] ); ?>
					</label>
				<?php else : ?>
					<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
					<input
						type="<?php echo esc_attr( $field['type'] ); ?>"
						id="<?php echo esc_attr( $key ); ?>"
						name="<?php echo esc_attr( $key ); ?>"
						value="<?php echo esc_attr( (string) $value ); ?>"
						<?php echo 'number' === $field['type'] ? 'step="any" min="0"' : ''; ?>>
				<?php endif; ?>
				<?php if ( ! empty( $field['desc'] ) ) : ?>
					<p class="description"><?php echo esc_html( $field['desc'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="aaa-gallery">
		<strong><?php esc_html_e( 'Gallery', 'amir-al-afia' ); ?></strong>
		<p class="description"><?php esc_html_e( 'Extra photos for the single-property page. The featured image is always shown first.', 'amir-al-afia' ); ?></p>
		<div class="aaa-gallery-preview" id="aaa-gallery-preview"></div>
		<input type="hidden" id="aaa_gallery" name="_aaa_gallery" value="<?php echo esc_attr( $gallery ); ?>">
		<button type="button" class="button" id="aaa-gallery-pick"><?php esc_html_e( 'Select images', 'amir-al-afia' ); ?></button>
		<button type="button" class="button" id="aaa-gallery-clear"><?php esc_html_e( 'Clear', 'amir-al-afia' ); ?></button>
	</div>
	<?php
}

/**
 * Persist the Property Details fields.
 *
 * @param int $post_id Post being saved.
 */
function aaa_save_property_meta( int $post_id ): void {
	if ( ! isset( $_POST['aaa_property_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['aaa_property_nonce'] ) ), 'aaa_save_property' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( aaa_property_fields() as $key => $field ) {
		if ( 'checkbox' === $field['type'] ) {
			if ( empty( $_POST[ $key ] ) ) {
				delete_post_meta( $post_id, $key );
			} else {
				update_post_meta( $post_id, $key, '1' );
			}
			continue;
		}

		$raw   = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
		$value = 'url' === $field['type'] ? esc_url_raw( $raw ) : sanitize_text_field( $raw );

		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}

	// Gallery is stored as a comma-separated list of attachment IDs.
	$gallery = isset( $_POST['_aaa_gallery'] ) ? sanitize_text_field( wp_unslash( $_POST['_aaa_gallery'] ) ) : '';
	$ids     = array_filter( array_map( 'absint', explode( ',', $gallery ) ) );

	if ( $ids ) {
		update_post_meta( $post_id, '_aaa_gallery', implode( ',', $ids ) );
	} else {
		delete_post_meta( $post_id, '_aaa_gallery' );
	}
}
add_action( 'save_post_property', 'aaa_save_property_meta' );

/**
 * Media picker and styles for the Property Details box.
 *
 * @param string $hook Current admin screen.
 */
function aaa_property_admin_assets( string $hook ): void {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	if ( 'property' !== get_post_type() ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style( 'aaa-admin', AAA_URI . '/assets/css/admin.css', array(), AAA_VERSION );
	wp_enqueue_script( 'aaa-admin', AAA_URI . '/assets/js/admin.js', array( 'jquery' ), AAA_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'aaa_property_admin_assets' );

/**
 * Read a property's gallery attachment IDs.
 *
 * @param int $post_id Property ID.
 * @return int[]
 */
function aaa_property_gallery_ids( int $post_id ): array {
	$raw = (string) get_post_meta( $post_id, '_aaa_gallery', true );
	return array_values( array_filter( array_map( 'absint', explode( ',', $raw ) ) ) );
}
