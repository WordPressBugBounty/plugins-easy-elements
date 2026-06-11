<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$easyel_email        = $settings['team_email'] ?? '';
$easyel_phone        = $settings['team_phone'] ?? '';
$easyel_show_contact = ! empty( $settings['show_contact_info'] ) && 'yes' === $settings['show_contact_info'] && ( $easyel_email || $easyel_phone );
?>
<div class="grid-item">
	<div class="ee--team-img skin5">
		<?php if ( $image_data ) : ?>
			<div class="eel-team-img-area">
				<?php if ( $action_type === 'link' && $link ) : ?>
					<a href="<?php echo esc_url( $link ); ?>"
					<?php if ( $target ) : ?>target="<?php echo esc_attr( $target ); ?>"<?php endif; ?>
					<?php if ( $nofollow ) : ?>rel="<?php echo esc_attr( $nofollow ); ?>"<?php endif; ?>>
				<?php elseif ( $action_type === 'popup' ) : ?>
					<a href="#<?php echo esc_attr( $unique_id ); ?>" class="eel-popup-trigger" data-popup-id="<?php echo esc_attr( $unique_id ); ?>">
				<?php endif; ?>
				<div class="eel-team-img-box">
					<img class="eel-team-img"
					src="<?php echo esc_url( $image_data[0] ); ?>"
					width="<?php echo esc_attr( $image_data[1] ); ?>"
					height="<?php echo esc_attr( $image_data[2] ); ?>"
					alt="<?php echo esc_attr( $alt ); ?>"
					title="<?php echo esc_attr( $title ); ?>"
					loading="lazy"
					decoding="async" fetchpriority="<?php echo esc_attr( $fetchpriority ); ?>">
					<div class="eel-image-below-bg"></div>
					<div class="eel-image-overlay"></div>
					<div class="eel-image-content <?php echo $details ? 'has-description' : ''; ?>">
						<?php if ( ! empty( $details ) ) : ?>
							<div class="eel-description"><?php echo nl2br( esc_html( $details ) ); ?></div>
						<?php endif; ?>
					</div>
				</div>
				<?php if ( ( $action_type === 'link' && $link ) || $action_type === 'popup' ) : ?>
					</a>
				<?php endif; ?>

				<div class="eel-name-deg-wrap">
					<div class="eel-author-content">
						<?php if ( ! empty( $name ) ) :
							echo wp_kses_post( $name );
						endif; ?>
						<?php if ( ! empty( $designation ) ) : ?>
							<div class="eel-designation"><?php echo esc_html( $designation ); ?></div>
						<?php endif; ?>
					</div>
					<?php if ( $easyel_show_contact ) : ?>
						<div class="eel-author-contact">
							<div class="eel-contact-inner">
								<?php if ( $easyel_email ) : ?>
									<div class="eel-team-email eel-contact-item">
										<div class="eel-contact-icon">
											<?php
											if ( ! empty( $settings['team_email_icon']['value'] ) ) {
												\Elementor\Icons_Manager::render_icon(
													$settings['team_email_icon'],
													[ 'aria-hidden' => 'true' ]
												);
											}
											?>
										</div>
										<?php echo esc_html( $easyel_email ); ?>
									</div>
								<?php endif; ?>

								<?php if ( $easyel_phone ) : ?>
									<div class="eel-team-phone eel-contact-item">
										<div class="eel-contact-icon">
											<?php
											if ( ! empty( $settings['team_phone_icon']['value'] ) ) {
												\Elementor\Icons_Manager::render_icon(
													$settings['team_phone_icon'],
													[ 'aria-hidden' => 'true' ]
												);
											}
											?>
										</div>
										<?php echo esc_html( $easyel_phone ); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
