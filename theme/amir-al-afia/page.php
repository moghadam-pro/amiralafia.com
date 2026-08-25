<?php
/**
 * Single page.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'section' ); ?>>
		<div class="container container-narrow">
			<h1 class="section-title"><?php the_title(); ?></h1>
			<div class="entry-content">
				<?php
				the_content();
				wp_link_pages();
				?>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
