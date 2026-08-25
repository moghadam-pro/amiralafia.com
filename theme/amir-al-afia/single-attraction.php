<?php
/**
 * A single place in Oman.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$aaa_id      = get_the_ID();
	$aaa_facts   = aaa_attraction_facts( $aaa_id );
	$aaa_link    = (string) get_post_meta( $aaa_id, '_aaa_attraction_url', true );
	$aaa_lat     = (string) get_post_meta( $aaa_id, '_aaa_attr_lat', true );
	$aaa_lng     = (string) get_post_meta( $aaa_id, '_aaa_attr_lng', true );
	$aaa_credit  = has_post_thumbnail() ? aaa_media_credit( (int) get_post_thumbnail_id() ) : '';
	$aaa_archive = get_post_type_archive_link( 'attraction' );
	?>
	<article <?php post_class( 'single-attraction' ); ?>>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="attr-hero">
				<?php the_post_thumbnail( 'aaa-wide', array( 'decoding' => 'async', 'fetchpriority' => 'high' ) ); ?>
				<div class="attr-hero-shade" aria-hidden="true"></div>
				<div class="container attr-hero-inner">
					<nav class="breadcrumb breadcrumb-light" aria-label="<?php esc_attr_e( 'Breadcrumb', 'amir-al-afia' ); ?>">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'amir-al-afia' ); ?></a>
						<span aria-hidden="true">/</span>
						<a href="<?php echo esc_url( (string) $aaa_archive ); ?>"><?php aaa_the_option( 'aaa_attr_title' ); ?></a>
					</nav>
					<h1 class="section-title"><?php the_title(); ?></h1>
					<?php if ( has_excerpt() ) : ?>
						<p class="attr-hero-sub"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
				</div>
				<?php if ( $aaa_credit ) : ?>
					<p class="attr-hero-credit"><?php echo wp_kses_post( $aaa_credit ); ?></p>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<div class="container section">
				<h1 class="section-title"><?php the_title(); ?></h1>
			</div>
		<?php endif; ?>

		<div class="container section">
			<div class="attr-body">

				<div class="attr-content">
					<?php if ( $aaa_facts ) : ?>
						<ul class="attr-facts">
							<?php foreach ( $aaa_facts as $aaa_fact ) : ?>
								<li>
									<span class="attr-fact-icon" aria-hidden="true"><?php aaa_icon( $aaa_fact['icon'], 16, '#0B2461' ); ?></span>
									<span class="attr-fact-label"><?php echo esc_html( $aaa_fact['label'] ); ?></span>
									<strong><?php echo esc_html( $aaa_fact['value'] ); ?></strong>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<div class="entry-content">
						<?php the_content(); ?>
					</div>

					<?php if ( $aaa_link ) : ?>
						<p class="attr-outlink">
							<a href="<?php echo esc_url( $aaa_link ); ?>" target="_blank" rel="noopener">
								<?php esc_html_e( 'More about this place', 'amir-al-afia' ); ?>
								<?php aaa_icon( 'arrow-ne', 13 ); ?>
							</a>
						</p>
					<?php endif; ?>
				</div>

				<aside class="attr-aside">
					<div class="sp-cta">
						<h2><?php esc_html_e( 'Thinking of living here?', 'amir-al-afia' ); ?></h2>
						<p><?php esc_html_e( 'Tell us the area you have in mind and we will send what is currently available, usually the same day.', 'amir-al-afia' ); ?></p>
						<a class="btn btn-navy" href="<?php echo esc_url( (string) get_post_type_archive_link( 'property' ) ); ?>">
							<?php aaa_icon( 'home', 16, '#fff' ); ?>
							<span><?php esc_html_e( 'Browse Properties', 'amir-al-afia' ); ?></span>
						</a>
						<?php $aaa_wa = aaa_option( 'aaa_whatsapp' ); ?>
						<?php if ( $aaa_wa ) : ?>
							<a class="btn btn-wa" target="_blank" rel="noopener"
								href="<?php
									echo esc_url(
										add_query_arg(
											'text',
											rawurlencode(
												sprintf(
													/* translators: %s: place name. */
													__( 'Hello, I am interested in property near %s', 'amir-al-afia' ),
													get_the_title()
												)
											),
											aaa_whatsapp_url( $aaa_wa )
										)
									);
								?>">
								<?php aaa_icon( 'whatsapp', 16, '#fff' ); ?>
								<span><?php esc_html_e( 'Ask on WhatsApp', 'amir-al-afia' ); ?></span>
							</a>
						<?php endif; ?>
					</div>

					<?php if ( is_numeric( $aaa_lat ) && is_numeric( $aaa_lng ) ) : ?>
						<p class="attr-map-link">
							<a href="<?php echo esc_url( sprintf( 'https://www.openstreetmap.org/?mlat=%s&mlon=%s#map=12/%s/%s', $aaa_lat, $aaa_lng, $aaa_lat, $aaa_lng ) ); ?>"
								target="_blank" rel="noopener">
								<?php aaa_icon( 'pin', 14 ); ?>
								<?php esc_html_e( 'See it on the map', 'amir-al-afia' ); ?>
							</a>
						</p>
					<?php endif; ?>
				</aside>

			</div>
		</div>
	</article>

	<?php
	$aaa_others = get_posts(
		array(
			'post_type'      => 'attraction',
			'posts_per_page' => 5,
			'post__not_in'   => array( $aaa_id ),
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		)
	);

	if ( $aaa_others ) :
		?>
		<section class="section section-related" aria-labelledby="more-oman">
			<div class="container">
				<h2 id="more-oman" class="section-title"><?php esc_html_e( 'More of Oman', 'amir-al-afia' ); ?></h2>
				<div class="attr-grid attr-grid-5">
					<?php
					foreach ( $aaa_others as $aaa_i => $aaa_item ) {
						set_query_var( 'aaa_attraction', $aaa_item );
						set_query_var( 'aaa_delay', $aaa_i * 60 );
						get_template_part( 'template-parts/attraction/tile' );
					}
					?>
				</div>
			</div>
		</section>
		<?php
	endif;

endwhile;

get_footer();
