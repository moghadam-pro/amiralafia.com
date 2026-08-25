<?php
/**
 * "Attractions & Nature of Oman" tiles.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

$aaa_attractions = aaa_get_attractions( 6 );

if ( ! $aaa_attractions ) {
	return;
}
?>
<section class="section" id="attractions" aria-labelledby="attractions-heading">
	<div class="container">

		<div class="attr-header">
			<div>
				<p <?php aaa_sr( 0, 'hero-location' ); ?>>
					<?php aaa_icon( 'pin', 16, '#9CA3AF' ); ?>
					<?php aaa_the_option( 'aaa_city' ); ?>
				</p>
				<h2 id="attractions-heading" <?php aaa_sr( 60, 'section-title' ); ?>><?php aaa_the_option( 'aaa_attr_title' ); ?></h2>
			</div>
		</div>

		<div class="attr-grid">
			<?php foreach ( $aaa_attractions as $aaa_i => $aaa_item ) : ?>
				<?php
				$aaa_url = (string) get_post_meta( $aaa_item->ID, '_aaa_attraction_url', true );
				$aaa_tag = $aaa_url ? 'a' : 'div';
				?>
				<<?php echo esc_html( $aaa_tag ); ?>
					<?php aaa_sr( $aaa_i * 60, 'attr-card' ); ?>
					<?php if ( $aaa_url ) : ?>
						href="<?php echo esc_url( $aaa_url ); ?>" target="_blank" rel="noopener"
					<?php endif; ?>>

					<?php
					if ( has_post_thumbnail( $aaa_item ) ) {
						echo get_the_post_thumbnail(
							$aaa_item,
							'aaa-card',
							array(
								'loading'  => 'lazy',
								'decoding' => 'async',
								'alt'      => '',
							)
						);
					}
					?>
					<span class="attr-overlay" aria-hidden="true"></span>
					<span class="attr-label"><?php echo esc_html( get_the_title( $aaa_item ) ); ?></span>
					<span class="attr-arrow" aria-hidden="true"><?php aaa_icon( 'arrow-ne', 11, '#fff' ); ?></span>
				</<?php echo esc_html( $aaa_tag ); ?>>
			<?php endforeach; ?>
		</div>

	</div>
</section>
