<?php
/**
 * One attraction tile.
 *
 * Expects `aaa_attraction` (WP_Post) and optionally `aaa_delay` as query vars,
 * so the same tile serves the home page, the archive and the related strip.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

$aaa_item = get_query_var( 'aaa_attraction' );

if ( ! $aaa_item instanceof WP_Post ) {
	return;
}

$aaa_delay = (int) get_query_var( 'aaa_delay', 0 );
?>
<a <?php aaa_sr( $aaa_delay, 'attr-card' ); ?> href="<?php echo esc_url( (string) get_permalink( $aaa_item ) ); ?>">
	<?php
	if ( has_post_thumbnail( $aaa_item ) ) {
		echo get_the_post_thumbnail(
			$aaa_item,
			'aaa-card',
			array(
				'loading'  => 'lazy',
				'decoding' => 'async',
				'alt'      => '',
			)
		);
	}
	?>
	<span class="attr-overlay" aria-hidden="true"></span>
	<span class="attr-label"><?php echo esc_html( get_the_title( $aaa_item ) ); ?></span>
	<span class="attr-arrow" aria-hidden="true"><?php aaa_icon( 'arrow-ne', 11, '#fff' ); ?></span>
</a>
