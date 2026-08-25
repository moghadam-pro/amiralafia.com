<?php
/**
 * Property gallery.
 *
 * With a single photo this is just that photo. With more than one it becomes a
 * slider: a scroll-snapping track plus thumbnails, arrows and dots that
 * main.js wires up. The track scrolls natively, so swiping and keyboard
 * scrolling work before any JavaScript runs, and the arrows and dots are only
 * revealed once the script takes over.
 *
 * Expects `aaa_gallery_ids` (int[]) as a query var.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

$aaa_ids = (array) get_query_var( 'aaa_gallery_ids', array() );
$aaa_ids = array_values( array_filter( array_map( 'absint', $aaa_ids ) ) );

if ( ! $aaa_ids ) {
	return;
}

$aaa_total = count( $aaa_ids );

// One image needs no slider machinery.
if ( 1 === $aaa_total ) :
	?>
	<figure class="sp-hero">
		<?php
		echo wp_get_attachment_image(
			$aaa_ids[0],
			'aaa-wide',
			false,
			array(
				'decoding'      => 'async',
				'fetchpriority' => 'high',
			)
		);
		?>
	</figure>
	<?php
	return;
endif;
?>
<div class="sp-slider" id="sp-slider" data-total="<?php echo (int) $aaa_total; ?>">

	<div
		class="sp-slider-track"
		id="sp-slider-track"
		tabindex="0"
		role="region"
		aria-roledescription="carousel"
		aria-label="<?php esc_attr_e( 'Property photos', 'amir-al-afia' ); ?>">
		<?php foreach ( $aaa_ids as $aaa_i => $aaa_att ) : ?>
			<figure
				class="sp-slide"
				id="sp-slide-<?php echo (int) $aaa_i; ?>"
				role="group"
				aria-roledescription="slide"
				aria-label="<?php
					printf(
						/* translators: 1: current photo number, 2: total photos. */
						esc_attr__( 'Photo %1$d of %2$d', 'amir-al-afia' ),
						(int) $aaa_i + 1,
						(int) $aaa_total
					);
				?>">
				<?php
				echo wp_get_attachment_image(
					$aaa_att,
					'aaa-wide',
					false,
					array(
						'decoding'      => 'async',
						'loading'       => 0 === $aaa_i ? 'eager' : 'lazy',
						'fetchpriority' => 0 === $aaa_i ? 'high' : 'auto',
					)
				);
				?>
			</figure>
		<?php endforeach; ?>
	</div>

	<button class="sp-slider-btn sp-prev" type="button" hidden aria-label="<?php esc_attr_e( 'Previous photo', 'amir-al-afia' ); ?>">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
	</button>
	<button class="sp-slider-btn sp-next" type="button" hidden aria-label="<?php esc_attr_e( 'Next photo', 'amir-al-afia' ); ?>">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
	</button>

	<p class="sp-slider-count" aria-hidden="true">
		<span class="sp-slider-current">1</span> / <?php echo (int) $aaa_total; ?>
	</p>
</div>

<div class="sp-thumbs" id="sp-thumbs" role="tablist" aria-label="<?php esc_attr_e( 'Choose a photo', 'amir-al-afia' ); ?>">
	<?php foreach ( $aaa_ids as $aaa_i => $aaa_att ) : ?>
		<button
			class="sp-thumb<?php echo 0 === $aaa_i ? ' is-active' : ''; ?>"
			type="button"
			role="tab"
			aria-controls="sp-slide-<?php echo (int) $aaa_i; ?>"
			aria-selected="<?php echo 0 === $aaa_i ? 'true' : 'false'; ?>"
			data-index="<?php echo (int) $aaa_i; ?>">
			<?php
			echo wp_get_attachment_image(
				$aaa_att,
				'thumbnail',
				false,
				array(
					'alt'      => '',
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			);
			?>
		</button>
	<?php endforeach; ?>
</div>
