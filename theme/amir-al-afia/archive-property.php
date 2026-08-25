<?php
/**
 * Properties archive, and the property_type / deal_type / location taxonomies.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

get_header();

$aaa_groups  = aaa_filter_groups();
$aaa_archive = get_post_type_archive_link( 'property' );
$aaa_term    = is_tax() ? get_queried_object() : null;
?>
<section class="section" id="properties" aria-labelledby="archive-heading">
	<div class="container">

		<div class="props-header">
			<p <?php aaa_sr( 0, 'section-tag' ); ?>>
				<?php aaa_icon( 'dot', 7, '#0B2461' ); ?>
				<?php aaa_the_option( 'aaa_props_tag' ); ?>
			</p>

			<h1 id="archive-heading" <?php aaa_sr( 80, 'section-title' ); ?>>
				<?php echo esc_html( $aaa_term instanceof WP_Term ? $aaa_term->name : aaa_option( 'aaa_props_title' ) ); ?>
			</h1>

			<p <?php aaa_sr( 140, 'section-sub' ); ?>>
				<?php
				$aaa_desc = $aaa_term instanceof WP_Term ? wp_strip_all_tags( (string) term_description( $aaa_term ) ) : '';
				echo esc_html( $aaa_desc ?: aaa_option( 'aaa_props_sub' ) );
				?>
			</p>

			<div <?php aaa_sr( 200, 'filter-bar' ); ?> role="group" aria-label="<?php esc_attr_e( 'Filter properties', 'amir-al-afia' ); ?>">
				<?php foreach ( $aaa_groups as $aaa_group => $aaa_data ) : ?>
					<?php if ( 'deal' === $aaa_group ) : ?>
						<span class="filter-gap" aria-hidden="true"></span>
					<?php endif; ?>

					<?php
					$aaa_group_active = ! ( $aaa_term instanceof WP_Term ) || $aaa_term->taxonomy !== $aaa_data['taxonomy'];
					?>
					<a
						href="<?php echo esc_url( (string) $aaa_archive ); ?>"
						class="filter-btn<?php echo $aaa_group_active ? ' is-active' : ''; ?>"
						data-group="<?php echo esc_attr( $aaa_group ); ?>"
						data-val="all"
						aria-pressed="<?php echo $aaa_group_active ? 'true' : 'false'; ?>"><?php esc_html_e( 'All', 'amir-al-afia' ); ?></a>

					<?php foreach ( $aaa_data['terms'] as $aaa_t ) : ?>
						<?php $aaa_on = $aaa_term instanceof WP_Term && $aaa_term->term_id === $aaa_t->term_id; ?>
						<a
							href="<?php echo esc_url( (string) get_term_link( $aaa_t ) ); ?>"
							class="filter-btn<?php echo $aaa_on ? ' is-active' : ''; ?>"
							data-group="<?php echo esc_attr( $aaa_group ); ?>"
							data-val="<?php echo esc_attr( $aaa_t->slug ); ?>"
							aria-pressed="<?php echo $aaa_on ? 'true' : 'false'; ?>"><?php echo esc_html( $aaa_t->name ); ?></a>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="props-grid props-grid-archive">
				<?php
				$aaa_i = 0;
				while ( have_posts() ) :
					the_post();
					set_query_var( 'aaa_delay', ( $aaa_i % 4 ) * 60 );
					get_template_part( 'template-parts/property/card' );
					++$aaa_i;
				endwhile;
				?>
			</div>

			<?php
			the_posts_pagination(
				array(
					'class'     => 'aaa-pagination',
					'mid_size'  => 1,
					'prev_text' => esc_html__( 'Previous', 'amir-al-afia' ),
					'next_text' => esc_html__( 'Next', 'amir-al-afia' ),
				)
			);
			?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/property/empty' ); ?>
		<?php endif; ?>

	</div>
</section>
<?php
get_footer();
