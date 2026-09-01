<?php
/**
 * "Meet The Team" — agent cards and the team photo.
 *
 * The mockup used <button> elements with no action for WhatsApp and Telegram.
 * These are real links.
 *
 * @package AmirAlAfia
 */

defined( 'ABSPATH' ) || exit;

$aaa_agents     = aaa_get_agents( 4 );
$aaa_team_photo = (int) get_theme_mod( 'aaa_team_photo', 0 );
?>
<section class="section" id="team" aria-labelledby="team-heading">
	<div class="container">
		<div class="team-inner">

			<div class="team-copy">
				<?php if ( aaa_option( 'aaa_team_tag' ) ) : ?>
					<p <?php aaa_sr( 0, 'section-tag' ); ?>>
						<?php aaa_icon( 'dot', 7, '#016FA6' ); ?>
						<?php aaa_the_option( 'aaa_team_tag' ); ?>
					</p>
				<?php endif; ?>

				<h2 id="team-heading" <?php aaa_sr( 80, 'section-title' ); ?>><?php aaa_the_option( 'aaa_team_title' ); ?></h2>
				<p <?php aaa_sr( 140, 'section-sub' ); ?>><?php aaa_the_option( 'aaa_team_sub' ); ?></p>

				<?php if ( $aaa_agents ) : ?>
					<div class="team-cards">
						<?php foreach ( $aaa_agents as $aaa_i => $aaa_agent ) : ?>
							<?php
							$aaa_role  = (string) get_post_meta( $aaa_agent->ID, '_aaa_agent_role', true );
							$aaa_title = (string) get_post_meta( $aaa_agent->ID, '_aaa_agent_title', true );
							$aaa_phone = (string) get_post_meta( $aaa_agent->ID, '_aaa_agent_phone', true );
							$aaa_wa    = (string) get_post_meta( $aaa_agent->ID, '_aaa_agent_whatsapp', true );
							$aaa_tg    = (string) get_post_meta( $aaa_agent->ID, '_aaa_agent_telegram', true );
							$aaa_wa    = $aaa_wa ?: $aaa_phone;
							?>
							<div <?php aaa_sr( 200 + $aaa_i * 80, 'team-card' ); ?>>
								<div class="team-card-info">
									<?php if ( $aaa_role ) : ?>
										<p class="team-card-role"><?php echo esc_html( $aaa_role ); ?></p>
									<?php endif; ?>
									<h3 class="team-card-name"><?php echo esc_html( get_the_title( $aaa_agent ) ); ?></h3>
									<?php if ( $aaa_title ) : ?>
										<p class="team-card-title"><?php echo esc_html( $aaa_title ); ?></p>
									<?php endif; ?>
								</div>

								<div class="team-card-right">
									<?php if ( $aaa_phone ) : ?>
										<a class="team-card-phone" href="tel:<?php echo esc_attr( aaa_digits( $aaa_phone ) ); ?>">
											<?php echo esc_html( $aaa_phone ); ?>
										</a>
									<?php endif; ?>

									<div class="team-card-btns">
										<?php if ( $aaa_wa ) : ?>
											<a
												class="team-icon-btn tib-wa"
												href="<?php echo esc_url( aaa_whatsapp_url( $aaa_wa ) ); ?>"
												target="_blank"
												rel="noopener"
												aria-label="<?php
													/* translators: %s: agent name. */
													echo esc_attr( sprintf( __( 'WhatsApp %s', 'amir-al-afia' ), get_the_title( $aaa_agent ) ) );
												?>">
												<?php aaa_icon( 'whatsapp', 16, '#fff' ); ?>
											</a>
										<?php endif; ?>

										<?php if ( $aaa_tg ) : ?>
											<a
												class="team-icon-btn tib-tg"
												href="<?php echo esc_url( aaa_telegram_url( $aaa_tg ) ); ?>"
												target="_blank"
												rel="noopener"
												aria-label="<?php
													/* translators: %s: agent name. */
													echo esc_attr( sprintf( __( 'Telegram %s', 'amir-al-afia' ), get_the_title( $aaa_agent ) ) );
												?>">
												<?php aaa_icon( 'telegram', 16, '#fff' ); ?>
											</a>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $aaa_team_photo ) : ?>
				<div <?php aaa_sr( 120, 'team-photo sr-right' ); ?>>
					<?php
					echo wp_get_attachment_image(
						$aaa_team_photo,
						'aaa-portrait',
						false,
						array(
							'loading'  => 'lazy',
							'decoding' => 'async',
						)
					);
					?>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>
