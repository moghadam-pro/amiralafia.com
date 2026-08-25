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

$aaa_archive = get_post_type_archive_link( 'attraction' );
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

			<?php if ( $aaa_archive ) : ?>
				<a class="attr-header-link" href="<?php echo esc_url( $aaa_archive ); ?>">
					<?php esc_html_e( 'Explore Oman', 'amir-al-afia' ); ?>
					<?php aaa_icon( 'arrow-r', 14 ); ?>
				</a>
			<?php endif; ?>
		</div>

		<div class="attr-grid">
			<?php
			foreach ( $aaa_attractions as $aaa_i => $aaa_item ) {
				set_query_var( 'aaa_attraction', $aaa_item );
				set_query_var( 'aaa_delay', $aaa_i * 60 );
				get_template_part( 'template-parts/attraction/tile' );
			}
			?>
		</div>

	</div>
</section>
