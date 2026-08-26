<?php
/**
 * 404.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="section">
	<div class="container container-narrow error-404">
		<h1 class="section-title"><?php esc_html_e( 'Page Not Found', 'amir-al-afia' ); ?></h1>
		<p class="section-sub">
			<?php esc_html_e( 'That page has moved or never existed. Browse the current listings instead, or call us and we will point you the right way.', 'amir-al-afia' ); ?>
		</p>
		<p class="error-404-actions">
			<a class="btn btn-primary" href="<?php echo esc_url( (string) get_post_type_archive_link( 'property' ) ); ?>">
				<?php esc_html_e( 'Browse Properties', 'amir-al-afia' ); ?>
			</a>
			<a class="btn btn-outline" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php esc_html_e( 'Back Home', 'amir-al-afia' ); ?>
			</a>
		</p>
	</div>
</section>
<?php
get_footer();
