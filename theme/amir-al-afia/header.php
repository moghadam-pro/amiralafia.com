<?php
/**
 * Document head and site header.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

$aaa_phone    = aaa_option( 'aaa_phone' );
$aaa_whatsapp = aaa_option( 'aaa_whatsapp' );
$aaa_telegram = aaa_telegram_url( aaa_option( 'aaa_telegram' ) );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'amir-al-afia' ); ?></a>

<header class="site-header">
	<nav id="navbar" aria-label="<?php esc_attr_e( 'Main navigation', 'amir-al-afia' ); ?>">
		<div class="nav-inner">

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<?php aaa_brand_logo(); ?>
			</a>

			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-links',
					'depth'          => 1,
					'fallback_cb'    => 'aaa_default_primary_menu',
				)
			);
			?>

			<div class="nav-ctas">
				<?php if ( $aaa_phone ) : ?>
					<a href="tel:<?php echo esc_attr( aaa_digits( $aaa_phone ) ); ?>" class="nav-cta nav-cta-phone">
						<?php aaa_icon( 'phone', 14, '#fff' ); ?>
						<span><?php echo esc_html( $aaa_phone ); ?></span>
					</a>
				<?php endif; ?>

				<?php if ( $aaa_whatsapp ) : ?>
					<a href="<?php echo esc_url( aaa_whatsapp_url( $aaa_whatsapp ) ); ?>" target="_blank" rel="noopener" class="nav-cta nav-cta-wa">
						<?php aaa_icon( 'whatsapp', 14, '#fff' ); ?>
						<span><?php esc_html_e( 'WhatsApp', 'amir-al-afia' ); ?></span>
					</a>
				<?php endif; ?>

				<?php if ( $aaa_telegram ) : ?>
					<a href="<?php echo esc_url( $aaa_telegram ); ?>" target="_blank" rel="noopener" class="nav-cta nav-cta-tg">
						<?php aaa_icon( 'telegram', 14, '#fff' ); ?>
						<span><?php esc_html_e( 'Telegram', 'amir-al-afia' ); ?></span>
					</a>
				<?php endif; ?>
			</div>

			<button class="nav-ham" id="nav-ham" aria-label="<?php esc_attr_e( 'Toggle menu', 'amir-al-afia' ); ?>" aria-expanded="false" aria-controls="nav-mobile">
				<span></span><span></span><span></span>
			</button>
		</div>

		<div class="nav-mobile" id="nav-mobile" hidden>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-mobile-links',
					'depth'          => 1,
					'fallback_cb'    => 'aaa_default_primary_menu',
				)
			);
			?>
			<div class="nav-mobile-ctas">
				<?php if ( $aaa_phone ) : ?>
					<a href="tel:<?php echo esc_attr( aaa_digits( $aaa_phone ) ); ?>" class="nav-cta nav-cta-phone">
						<?php aaa_icon( 'phone', 13, '#fff' ); ?>
						<span><?php esc_html_e( 'Call us', 'amir-al-afia' ); ?></span>
					</a>
				<?php endif; ?>
				<?php if ( $aaa_whatsapp ) : ?>
					<a href="<?php echo esc_url( aaa_whatsapp_url( $aaa_whatsapp ) ); ?>" target="_blank" rel="noopener" class="nav-cta nav-cta-wa">
						<?php aaa_icon( 'whatsapp', 13, '#fff' ); ?>
						<span><?php esc_html_e( 'WhatsApp', 'amir-al-afia' ); ?></span>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</nav>
</header>

<main id="main">
