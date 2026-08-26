<?php
/**
 * Closing CTA band and site footer.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

$aaa_phone = aaa_option( 'aaa_phone' );
?>
</main>

<?php if ( is_front_page() ) : ?>
	<section class="cta-band" aria-labelledby="cta-heading">
		<h2 id="cta-heading" <?php aaa_sr( 0 ); ?>><?php aaa_the_option( 'aaa_cta_title' ); ?></h2>
		<p <?php aaa_sr( 80 ); ?>><?php aaa_the_option( 'aaa_cta_sub' ); ?></p>
		<?php if ( $aaa_phone ) : ?>
			<a href="tel:<?php echo esc_attr( aaa_digits( $aaa_phone ) ); ?>" <?php aaa_sr( 160, 'btn btn-white' ); ?>>
				<?php aaa_icon( 'phone', 16, '#016FA6' ); ?>
				<span><?php echo esc_html( $aaa_phone ); ?></span>
			</a>
		<?php endif; ?>
	</section>
<?php endif; ?>

<footer id="footer" class="site-footer">
	<div class="footer-inner">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<?php aaa_brand_logo(); ?>
		</a>

		<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer navigation', 'amir-al-afia' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'footer-nav-links',
					'depth'          => 1,
					'fallback_cb'    => 'aaa_default_footer_menu',
				)
			);
			?>
		</nav>

		<div class="footer-copy">
			<?php
			printf(
				/* translators: 1: current year, 2: site name. */
				esc_html__( '© %1$s %2$s. All rights reserved.', 'amir-al-afia' ),
				esc_html( gmdate( 'Y' ) ),
				esc_html( get_bloginfo( 'name' ) )
			);
			?>
		</div>

	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
