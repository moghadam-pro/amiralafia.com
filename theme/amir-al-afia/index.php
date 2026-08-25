<?php
/**
 * Fallback template.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<h1 class="section-title"><?php echo esc_html( wp_get_document_title() ); ?></h1>
			<div class="post-list">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'post-item' ); ?>>
						<h2 class="prop-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="section-sub"><?php echo esc_html( get_the_excerpt() ); ?></p>
					</article>
					<?php
				endwhile;
				?>
			</div>
			<?php the_posts_pagination( array( 'class' => 'aaa-pagination' ) ); ?>
		<?php else : ?>
			<h1 class="section-title"><?php esc_html_e( 'Nothing here yet', 'amir-al-afia' ); ?></h1>
			<p class="section-sub"><?php esc_html_e( 'There is no content on this page.', 'amir-al-afia' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
