<?php
/**
 * Hero: headline, stats and the scrolling photo columns.
 *
 * The collage is positioned over the right half of the hero rather than being
 * a grid cell, so it can bleed to the viewport edge. The markup here is three
 * columns; each renders its own images twice, because the CSS loops by
 * translating exactly half the track and needs the second copy to land on.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

$aaa_stats = array(
	array( aaa_option( 'aaa_stat1_value' ), aaa_option( 'aaa_stat1_label' ) ),
	array( aaa_option( 'aaa_stat2_value' ), aaa_option( 'aaa_stat2_label' ) ),
	array( aaa_option( 'aaa_stat3_value' ), aaa_option( 'aaa_stat3_label' ) ),
);

$aaa_columns = aaa_hero_columns();
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
					<a href="#properties" class="btn btn-primary">
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

		</div>
	</div>

	<?php if ( $aaa_columns ) : ?>
		<?php
		// Decoration. It carries no information the page does not already state
		// in words, so it is hidden from assistive technology entirely rather
		// than narrated as a stack of unlabelled photographs.
		?>
		<div class="hero-collage" aria-hidden="true">
			<?php foreach ( $aaa_columns as $aaa_index => $aaa_column ) : ?>
				<div
					class="hc-col"
					style="--hc-duration: <?php echo esc_attr( $aaa_column['duration'] ); ?>; --hc-offset: <?php echo esc_attr( $aaa_column['offset'] ); ?>;">
					<div class="hc-track">
						<?php // Twice through: the second pass is what the loop lands on. ?>
						<?php for ( $aaa_pass = 0; $aaa_pass < 2; $aaa_pass++ ) : ?>
							<?php foreach ( $aaa_column['images'] as $aaa_position => $aaa_id ) : ?>
								<?php
								echo wp_get_attachment_image(
									$aaa_id,
									'aaa-hero-tile',
									false,
									array(
										'alt'      => '',
										// Lazy without exception, including the tiles that
										// start on screen. A browser loads an in-viewport
										// lazy image immediately anyway, so desktop is
										// unaffected - but on a phone, where the collage is
										// display:none, lazy is the difference between
										// fetching nothing and fetching the ones marked
										// eager, which are requested regardless of whether
										// their container is visible.
										'loading'  => 'lazy',
										'decoding' => 'async',
										// Without this the browser reads the 100% width off
										// the column and picks a far larger srcset entry
										// than these tiles ever render at.
										'sizes'    => '(max-width: 960px) 30vw, 17vw',
									)
								);
								?>
							<?php endforeach; ?>
						<?php endfor; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section>
