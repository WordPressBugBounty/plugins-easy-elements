<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="grid-item">
    <div class="ee--team-img">
        <div class="eel-team-left">        
            <?php if ( $action_type === 'link' && $link ) : ?>
                <a href="<?php echo esc_url( $link ); ?>"
                <?php if ( $target ) : ?>target="<?php echo esc_attr( $target ); ?>"<?php endif; ?>
                <?php if ( $nofollow ) : ?>rel="<?php echo esc_attr( $nofollow ); ?>"<?php endif; ?>>
            <?php elseif ( $action_type === 'popup' ) : ?>
                <a href="#<?php echo esc_attr($unique_id); ?>" class="eel-popup-trigger" data-popup-id="<?php echo esc_attr($unique_id); ?>">
            <?php endif; ?>   
            <?php if ( $image_data ) : ?>
                <div class="eel-team-img-area">
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
                     </div>
                </div>
            <?php endif; ?>
            <?php if ( ($action_type === 'link' && $link) || $action_type === 'popup' ) : ?>
                </a>
            <?php endif; ?>
        </div>
        <div class="eel-team-right">
            <div class="eel-name-deg-wrap">
                <?php if ( ! empty( $name ) ) :
                    echo wp_kses_post( $name );
                endif; ?>
                <?php if ( ! empty( $designation ) ) : ?>
                    <div class="eel-designation"><?php echo esc_html( $designation ); ?></div>
                <?php endif; ?>
                <?php if ( ! empty( $details ) ) : ?>
                    <div class="eel-team-description"><?php echo nl2br( esc_html( $details ) ); ?></div>
                <?php endif; ?>
            </div>
            <?php if ( $settings['social_links'] && $settings['show_social_icon'] == 'yes' ): ?>
                <div class="eel-team-grid-social <?php echo esc_attr( $settings['social_icon_position'].' '.$settings['social_icon_show']) ?>">
                    <?php if( $settings['social_icon_show'] =='hover_show' && !empty( $settings['social_hover_icon']['value']) ): ?>
                        <div class="eel-team-social-hover">
                            <a href="#">
                                <?php \Elementor\Icons_Manager::render_icon( $settings['social_hover_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            </a>
                        </div>
                    <?php endif;?>
                    <ul>
                        <?php foreach (  $settings['social_links'] as $easyel_item ):?>
                            <li>
                                <a href="<?php echo esc_attr( $easyel_item['link_url']['url'] )?>">
                                    <?php \Elementor\Icons_Manager::render_icon( $easyel_item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>