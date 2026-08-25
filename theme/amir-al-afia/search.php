<?php
/**
 * Search results.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="section">
	<div class="container">
		<h1 class="section-title">
			<?php
			printf(
				/* translators: %s: search term. */
				esc_html__( 'Results for “%s”', 'amir-al-afia' ),
				esc_html( get_search_query() )
			);
			?>
		</h1>

		<?php if ( have_posts() ) : ?>
			<div class="props-grid">
				<?php
				$aaa_i = 0;
				while ( have_posts() ) :
					the_post();
					if ( 'property' === get_post_type() ) {
						set_query_var( 'aaa_delay', $aaa_i * 40 );
						get_template_part( 'template-parts/property/card' );
					} else {
						?>
						<article <?php post_class( 'post-item' ); ?>>
							<h2 class="prop-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p class="section-sub"><?php echo esc_html( get_the_excerpt() ); ?></p>
						</article>
						<?php
					}
					++$aaa_i;
				endwhile;
				?>
			</div>
			<?php the_posts_pagination( array( 'class' => 'aaa-pagination' ) ); ?>
		<?php else : ?>
			<p class="section-sub"><?php esc_html_e( 'Nothing matched that search.', 'amir-al-afia' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
