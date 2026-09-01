<?php
/**
 * "Why Oman" — the investor value-proposition cards.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

$aaa_cards = aaa_investor_cards();
?>
<section class="investors-section" id="investors" aria-labelledby="investors-heading">
	<div class="container">

		<?php if ( aaa_option( 'aaa_inv_tag' ) ) : ?>
			<p <?php aaa_sr( 0, 'section-tag' ); ?>>
				<?php aaa_icon( 'dot', 7, '#38B6FF' ); ?>
				<?php aaa_the_option( 'aaa_inv_tag' ); ?>
			</p>
		<?php endif; ?>

		<h2 id="investors-heading" <?php aaa_sr( 80, 'section-title' ); ?>><?php aaa_the_option( 'aaa_inv_title' ); ?></h2>
		<p <?php aaa_sr( 140, 'section-sub' ); ?>><?php aaa_the_option( 'aaa_inv_sub' ); ?></p>

		<div class="inv-grid">
			<?php foreach ( $aaa_cards as $aaa_i => $aaa_card ) : ?>
				<?php if ( '' === $aaa_card['title'] && '' === $aaa_card['text'] ) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<div <?php aaa_sr( $aaa_i * 80, 'inv-card' ); ?>>
					<div class="inv-card-icon" aria-hidden="true">
						<?php aaa_icon( $aaa_card['icon'], 24, '#38B6FF' ); ?>
					</div>
					<h3><?php echo esc_html( $aaa_card['title'] ); ?></h3>
					<p><?php echo esc_html( $aaa_card['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>


	</div>
</section>
