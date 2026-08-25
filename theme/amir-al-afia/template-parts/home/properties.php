<?php
/**
 * Featured properties, with a working filter bar.
 *
 * The filter buttons are links to the properties archive so they work with
 * JavaScript off; main.js intercepts them and swaps the grid via AJAX.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

$aaa_per_page = max( 1, (int) aaa_option( 'aaa_props_count' ) );
$aaa_query    = aaa_get_home_properties( 'all', 'all', $aaa_per_page );
$aaa_groups   = aaa_filter_groups();
$aaa_archive  = get_post_type_archive_link( 'property' );
?>
<section class="section" id="properties" aria-labelledby="properties-heading">
	<div class="container">

		<div class="props-header">
			<?php if ( aaa_option( 'aaa_props_tag' ) ) : ?>
				<p <?php aaa_sr( 0, 'section-tag' ); ?>>
					<?php aaa_icon( 'dot', 7, '#0B2461' ); ?>
					<?php aaa_the_option( 'aaa_props_tag' ); ?>
				</p>
			<?php endif; ?>

			<h2 id="properties-heading" <?php aaa_sr( 80, 'section-title' ); ?>><?php aaa_the_option( 'aaa_props_title' ); ?></h2>
			<p <?php aaa_sr( 140, 'section-sub' ); ?>><?php aaa_the_option( 'aaa_props_sub' ); ?></p>

			<div <?php aaa_sr( 200, 'filter-bar' ); ?> role="group" aria-label="<?php esc_attr_e( 'Filter properties', 'amir-al-afia' ); ?>">
				<?php foreach ( $aaa_groups as $aaa_group => $aaa_data ) : ?>
					<?php if ( 'deal' === $aaa_group ) : ?>
						<span class="filter-gap" aria-hidden="true"></span>
					<?php endif; ?>

					<a
						href="<?php echo esc_url( (string) $aaa_archive ); ?>"
						class="filter-btn is-active"
						data-group="<?php echo esc_attr( $aaa_group ); ?>"
						data-val="all"
						aria-pressed="true"><?php esc_html_e( 'All', 'amir-al-afia' ); ?></a>

					<?php foreach ( $aaa_data['terms'] as $aaa_term ) : ?>
						<a
							href="<?php echo esc_url( (string) get_term_link( $aaa_term ) ); ?>"
							class="filter-btn"
							data-group="<?php echo esc_attr( $aaa_group ); ?>"
							data-val="<?php echo esc_attr( $aaa_term->slug ); ?>"
							aria-pressed="false"><?php echo esc_html( $aaa_term->name ); ?></a>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</div>
		</div>

		<div
			class="props-grid"
			id="props-grid"
			data-per-page="<?php echo (int) $aaa_per_page; ?>"
			aria-live="polite"
			aria-busy="false">
			<?php if ( $aaa_query->have_posts() ) : ?>
				<?php
				$aaa_i = 0;
				while ( $aaa_query->have_posts() ) :
					$aaa_query->the_post();
					set_query_var( 'aaa_delay', $aaa_i * 60 );
					get_template_part( 'template-parts/property/card' );
					++$aaa_i;
				endwhile;
				wp_reset_postdata();
				?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/property/empty' ); ?>
			<?php endif; ?>
		</div>

		<?php if ( $aaa_archive ) : ?>
			<p <?php aaa_sr( 0, 'props-viewall' ); ?>>
				<a href="<?php echo esc_url( $aaa_archive ); ?>" class="btn btn-outline">
					<?php esc_html_e( 'View All Properties', 'amir-al-afia' ); ?>
				</a>
			</p>
		<?php endif; ?>

	</div>
</section>
