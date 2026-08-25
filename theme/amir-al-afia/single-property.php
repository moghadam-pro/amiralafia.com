<?php
/**
 * A single property listing.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$aaa_id      = get_the_ID();
	$aaa_price   = get_post_meta( $aaa_id, '_aaa_price', true );
	$aaa_beds    = (string) get_post_meta( $aaa_id, '_aaa_beds', true );
	$aaa_baths   = (string) get_post_meta( $aaa_id, '_aaa_baths', true );
	$aaa_area    = (string) get_post_meta( $aaa_id, '_aaa_area', true );
	$aaa_addr    = (string) get_post_meta( $aaa_id, '_aaa_address', true );
	$aaa_map     = (string) get_post_meta( $aaa_id, '_aaa_map', true );
	$aaa_gallery = aaa_property_gallery_ids( $aaa_id );
	$aaa_deal    = get_the_terms( $aaa_id, 'deal_type' );
	$aaa_deal    = ( $aaa_deal && ! is_wp_error( $aaa_deal ) ) ? $aaa_deal[0] : null;
	$aaa_phone   = aaa_option( 'aaa_phone' );
	$aaa_wa      = aaa_option( 'aaa_whatsapp' );
	?>
	<article <?php post_class( 'section single-property' ); ?>>
		<div class="container">

			<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'amir-al-afia' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'amir-al-afia' ); ?></a>
				<span aria-hidden="true">/</span>
				<a href="<?php echo esc_url( (string) get_post_type_archive_link( 'property' ) ); ?>"><?php esc_html_e( 'Properties', 'amir-al-afia' ); ?></a>
			</nav>

			<div class="sp-head">
				<div>
					<?php if ( $aaa_deal ) : ?>
						<p class="section-tag">
							<?php aaa_icon( 'dot', 7, '#0B2461' ); ?>
							<?php echo esc_html( $aaa_deal->name ); ?>
						</p>
					<?php endif; ?>

					<h1 class="section-title"><?php the_title(); ?></h1>

					<?php if ( $aaa_addr ) : ?>
						<p class="prop-location">
							<?php aaa_icon( 'pin', 14, '#9CA3AF' ); ?>
							<?php if ( $aaa_map ) : ?>
								<a href="<?php echo esc_url( $aaa_map ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $aaa_addr ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $aaa_addr ); ?>
							<?php endif; ?>
						</p>
					<?php endif; ?>
				</div>

				<p class="sp-price"><?php echo esc_html( aaa_format_price( $aaa_price ) ); ?></p>
			</div>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="sp-hero">
					<?php the_post_thumbnail( 'aaa-wide', array( 'decoding' => 'async' ) ); ?>
				</div>
			<?php endif; ?>

			<div class="sp-body">
				<div class="sp-content">
					<?php if ( $aaa_beds || $aaa_baths || $aaa_area ) : ?>
						<ul class="sp-specs">
							<?php if ( $aaa_beds ) : ?>
								<li>
									<?php aaa_icon( 'bed', 18, '#0B2461' ); ?>
									<strong><?php echo esc_html( $aaa_beds ); ?></strong>
									<span><?php esc_html_e( 'Bedrooms', 'amir-al-afia' ); ?></span>
								</li>
							<?php endif; ?>
							<?php if ( $aaa_baths ) : ?>
								<li>
									<?php aaa_icon( 'bath', 18, '#0B2461' ); ?>
									<strong><?php echo esc_html( $aaa_baths ); ?></strong>
									<span><?php esc_html_e( 'Bathrooms', 'amir-al-afia' ); ?></span>
								</li>
							<?php endif; ?>
							<?php if ( $aaa_area ) : ?>
								<li>
									<?php aaa_icon( 'area', 18, '#0B2461' ); ?>
									<strong><?php echo esc_html( number_format_i18n( (float) $aaa_area ) ); ?></strong>
									<span><?php esc_html_e( 'Sq ft', 'amir-al-afia' ); ?></span>
								</li>
							<?php endif; ?>
						</ul>
					<?php endif; ?>

					<div class="entry-content">
						<?php the_content(); ?>
					</div>

					<?php if ( $aaa_gallery ) : ?>
						<h2 class="sp-subhead"><?php esc_html_e( 'Gallery', 'amir-al-afia' ); ?></h2>
						<div class="sp-gallery">
							<?php foreach ( $aaa_gallery as $aaa_att ) : ?>
								<figure>
									<?php
									echo wp_get_attachment_image(
										$aaa_att,
										'aaa-card',
										false,
										array(
											'loading'  => 'lazy',
											'decoding' => 'async',
										)
									);
									?>
								</figure>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<aside class="sp-aside">
					<div class="sp-cta">
						<h2><?php esc_html_e( 'Arrange a viewing', 'amir-al-afia' ); ?></h2>
						<p><?php esc_html_e( 'Call or message us and we will arrange a visit, usually within 24 hours.', 'amir-al-afia' ); ?></p>

						<?php if ( $aaa_phone ) : ?>
							<a class="btn btn-navy" href="tel:<?php echo esc_attr( aaa_digits( $aaa_phone ) ); ?>">
								<?php aaa_icon( 'phone', 16, '#fff' ); ?>
								<span><?php echo esc_html( $aaa_phone ); ?></span>
							</a>
						<?php endif; ?>

						<?php if ( $aaa_wa ) : ?>
							<a
								class="btn btn-wa"
								target="_blank"
								rel="noopener"
								href="<?php
									echo esc_url(
										add_query_arg(
											'text',
											rawurlencode(
												sprintf(
													/* translators: 1: property title, 2: property URL. */
													__( 'Hello, I am interested in %1$s — %2$s', 'amir-al-afia' ),
													get_the_title(),
													get_permalink()
												)
											),
											aaa_whatsapp_url( $aaa_wa )
										)
									);
								?>">
								<?php aaa_icon( 'whatsapp', 16, '#fff' ); ?>
								<span><?php esc_html_e( 'Message on WhatsApp', 'amir-al-afia' ); ?></span>
							</a>
						<?php endif; ?>
					</div>
				</aside>
			</div>

		</div>
	</article>

	<?php
	$aaa_related_args                  = aaa_property_query_args( 'all', $aaa_deal ? $aaa_deal->slug : 'all', 4 );
	$aaa_related_args['post__not_in']  = array( $aaa_id );
	$aaa_related                       = new WP_Query( $aaa_related_args );
	if ( $aaa_related->have_posts() ) :
		?>
		<section class="section section-related" aria-labelledby="related-heading">
			<div class="container">
				<h2 id="related-heading" class="section-title"><?php esc_html_e( 'Similar Properties', 'amir-al-afia' ); ?></h2>
				<div class="props-grid">
					<?php
					$aaa_i = 0;
					while ( $aaa_related->have_posts() ) :
						$aaa_related->the_post();
						set_query_var( 'aaa_delay', $aaa_i * 60 );
						get_template_part( 'template-parts/property/card' );
						++$aaa_i;
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
		<?php
	endif;

endwhile;

get_footer();
