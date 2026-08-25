<?php
/**
 * Hero: headline, stats and the nine-cell photo collage.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

$aaa_stats = array(
	array( aaa_option( 'aaa_stat1_value' ), aaa_option( 'aaa_stat1_label' ) ),
	array( aaa_option( 'aaa_stat2_value' ), aaa_option( 'aaa_stat2_label' ) ),
	array( aaa_option( 'aaa_stat3_value' ), aaa_option( 'aaa_stat3_label' ) ),
);
?>
<section class="hero" id="home" aria-labelledby="hero-heading">
	<div class="container">
		<div class="hero-inner">

			<div class="hero-text">
				<p class="hero-location" <?php aaa_sr( 0 ); ?>>
					<?php aaa_icon( 'pin', 16, '#9CA3AF' ); ?>
					<?php aaa_the_option( 'aaa_city' ); ?>
				</p>

				<h1 id="hero-heading" <?php aaa_sr( 80, 'hero-heading' ); ?>>
					<span class="line-cyan"><?php aaa_the_option( 'aaa_hero_line1' ); ?></span>
					<span class="line-dark"><?php aaa_the_option( 'aaa_hero_line2' ); ?></span>
					<span class="line-cyan"><?php aaa_the_option( 'aaa_hero_line3' ); ?></span>
				</h1>

				<p <?php aaa_sr( 160, 'hero-desc' ); ?>><?php aaa_the_option( 'aaa_hero_desc' ); ?></p>

				<div <?php aaa_sr( 230, 'hero-buttons' ); ?>>
					<a href="#properties" class="btn btn-navy">
						<?php aaa_icon( 'home', 16, '#fff' ); ?>
						<span><?php esc_html_e( 'Browse Properties', 'amir-al-afia' ); ?></span>
					</a>
					<a href="#team" class="btn btn-outline"><?php esc_html_e( 'Talk to an Agent', 'amir-al-afia' ); ?></a>
				</div>

				<dl <?php aaa_sr( 310, 'hero-stats' ); ?>>
					<?php foreach ( $aaa_stats as $aaa_i => $aaa_stat ) : ?>
						<?php if ( '' === $aaa_stat[0] && '' === $aaa_stat[1] ) : ?>
							<?php continue; ?>
						<?php endif; ?>
						<?php if ( $aaa_i > 0 ) : ?>
							<div class="hstat-divider" aria-hidden="true"></div>
						<?php endif; ?>
						<div class="hstat">
							<dt class="hstat-val"><?php echo esc_html( $aaa_stat[0] ); ?></dt>
							<dd class="hstat-lbl"><?php echo esc_html( $aaa_stat[1] ); ?></dd>
						</div>
					<?php endforeach; ?>
				</dl>
			</div>

			<div <?php aaa_sr( 100, 'hero-collage sr-right' ); ?> aria-label="<?php esc_attr_e( 'Recent properties', 'amir-al-afia' ); ?>" role="img">
				<?php for ( $aaa_c = 1; $aaa_c <= 8; $aaa_c++ ) : ?>
					<?php $aaa_id = (int) get_theme_mod( 'aaa_collage_' . $aaa_c, 0 ); ?>
					<div class="hcell hcell-<?php echo (int) $aaa_c; ?>">
						<?php
						if ( $aaa_id ) {
							echo wp_get_attachment_image(
								$aaa_id,
								4 === $aaa_c ? 'aaa-wide' : 'aaa-collage',
								false,
								array(
									'alt'      => '',
									'loading'  => $aaa_c <= 4 ? 'eager' : 'lazy',
									'decoding' => 'async',
								)
							);
						} else {
							printf(
								'<img src="%s" alt="" width="600" height="600" decoding="async">',
								esc_url( aaa_placeholder_image() )
							);
						}
						?>
					</div>
				<?php endfor; ?>
			</div>

		</div>
	</div>
</section>
