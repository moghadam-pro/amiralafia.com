<?php
/**
 * The Oman guide index.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="section" aria-labelledby="oman-archive-heading">
	<div class="container">

		<div class="props-header">
			<p <?php aaa_sr( 0, 'hero-location' ); ?>>
				<?php aaa_icon( 'pin', 16, '#9CA3AF' ); ?>
				<?php aaa_the_option( 'aaa_city' ); ?>
			</p>
			<h1 id="oman-archive-heading" <?php aaa_sr( 60, 'section-title' ); ?>><?php aaa_the_option( 'aaa_attr_title' ); ?></h1>
			<p <?php aaa_sr( 120, 'section-sub' ); ?>>
				<?php esc_html_e( 'Beaches, mountains, wadis and old quarters worth knowing before you choose where in Oman to live.', 'amir-al-afia' ); ?>
			</p>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="attr-grid attr-grid-archive">
				<?php
				$aaa_i = 0;
				while ( have_posts() ) :
					the_post();
					set_query_var( 'aaa_attraction', get_post() );
					set_query_var( 'aaa_delay', ( $aaa_i % 6 ) * 60 );
					get_template_part( 'template-parts/attraction/tile' );
					++$aaa_i;
				endwhile;
				?>
			</div>
			<?php the_posts_pagination( array( 'class' => 'aaa-pagination' ) ); ?>
		<?php else : ?>
			<p class="section-sub"><?php esc_html_e( 'Nothing here yet.', 'amir-al-afia' ); ?></p>
		<?php endif; ?>

	</div>
</section>
<?php
get_footer();
