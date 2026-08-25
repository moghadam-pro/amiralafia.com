<?php
/**
 * One property card.
 *
 * The whole card is a link to the listing. In the mockup it was a bare div
 * with `cursor: pointer` and no destination.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

$aaa_id    = get_the_ID();
$aaa_delay = (int) get_query_var( 'aaa_delay', 0 );
$aaa_beds  = (string) get_post_meta( $aaa_id, '_aaa_beds', true );
$aaa_baths = (string) get_post_meta( $aaa_id, '_aaa_baths', true );
$aaa_area  = (string) get_post_meta( $aaa_id, '_aaa_area', true );
$aaa_addr  = (string) get_post_meta( $aaa_id, '_aaa_address', true );
$aaa_price = get_post_meta( $aaa_id, '_aaa_price', true );
$aaa_deal  = get_the_terms( $aaa_id, 'deal_type' );
$aaa_deal  = ( $aaa_deal && ! is_wp_error( $aaa_deal ) ) ? $aaa_deal[0] : null;
?>
<article <?php aaa_sr( $aaa_delay, 'prop-card' ); ?>>
	<a class="prop-link" href="<?php the_permalink(); ?>">
		<div class="prop-img-wrap">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php
				the_post_thumbnail(
					'aaa-card',
					array(
						'loading'  => 'lazy',
						'decoding' => 'async',
					)
				);
				?>
			<?php else : ?>
				<img src="<?php echo esc_url( aaa_placeholder_image() ); ?>" alt="" width="640" height="360" loading="lazy" decoding="async">
			<?php endif; ?>

			<?php if ( $aaa_deal ) : ?>
				<span class="prop-badge badge-<?php echo esc_attr( 'for-rent' === $aaa_deal->slug ? 'rent' : 'sale' ); ?>">
					<?php echo esc_html( $aaa_deal->name ); ?>
				</span>
			<?php endif; ?>
		</div>

		<div class="prop-body">
			<p class="prop-price"><?php echo esc_html( aaa_format_price( $aaa_price ) ); ?></p>
			<h3 class="prop-name"><?php the_title(); ?></h3>

			<?php if ( $aaa_addr ) : ?>
				<p class="prop-location">
					<?php aaa_icon( 'pin', 14, '#9CA3AF' ); ?>
					<?php echo esc_html( $aaa_addr ); ?>
				</p>
			<?php endif; ?>

			<?php if ( $aaa_beds || $aaa_baths || $aaa_area ) : ?>
				<p class="prop-meta">
					<?php if ( $aaa_beds ) : ?>
						<span>
							<?php aaa_icon( 'bed', 13, '#9CA3AF' ); ?>
							<?php
							echo esc_html(
								is_numeric( $aaa_beds )
									/* translators: %s: number of bedrooms. */
									? sprintf( _n( '%s BD', '%s BDS', (int) $aaa_beds, 'amir-al-afia' ), $aaa_beds )
									: $aaa_beds
							);
							?>
						</span>
					<?php endif; ?>

					<?php if ( $aaa_baths ) : ?>
						<span>
							<?php aaa_icon( 'bath', 13, '#9CA3AF' ); ?>
							<?php
							/* translators: %s: number of bathrooms. */
							echo esc_html( sprintf( _n( '%s BATH', '%s BATHS', (int) $aaa_baths, 'amir-al-afia' ), $aaa_baths ) );
							?>
						</span>
					<?php endif; ?>

					<?php if ( $aaa_area ) : ?>
						<span>
							<?php aaa_icon( 'area', 13, '#9CA3AF' ); ?>
							<?php
							/* translators: %s: floor area in square feet. */
							echo esc_html( sprintf( __( '%s SQFT', 'amir-al-afia' ), number_format_i18n( (float) $aaa_area ) ) );
							?>
						</span>
					<?php endif; ?>
				</p>
			<?php endif; ?>
		</div>
	</a>
</article>
