<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$easyel_tab_slug = isset($current_tab) ? $current_tab : 'widget';
$easyel_grouped_widgets = [];

// Group widgets
foreach ( $available_elements as $easyel_key => $easyel_widget ) {
    $easyel_group = isset($easyel_widget['group']) ? $easyel_widget['group'] : 'General Widgets';
    $easyel_grouped_widgets[$easyel_group][$easyel_key] = $easyel_widget;
}
?>

<div class="easy-widgets-wrapper">
    <?php foreach ( $easyel_grouped_widgets as $easyel_group_name => $easyel_widgets ) : 

        $easyel_group_slug = str_replace(' ', '-', strtolower($easyel_group_name));
        $easyel_group_enabled = get_option('easy_element_group_' . $easyel_group_slug, 0);
        ?>
        <div class="easyel-widget-heading-group easyel-dflex easyel-justify-between easyel-align-center">
            <h2 class="easyel-widget-group-title"><?php echo esc_html($easyel_group_name); ?></h2>
            <label class="easyel-toggle-switch-widget">
                <input type="checkbox" 
                        class="easyel-group-toggle-widget" 
                        name ="easyel-toggle-switch-widget"
                        data-group="<?php echo esc_attr($easyel_group_slug); ?>" 
                        <?php checked(1, $easyel_group_enabled); ?> /> 
                <span class="easyel-enable-all-widget"><?php echo esc_html__("Enable All", "easy-elements");?></span>
                <span class="slider"></span>
            </label>
            <input type="hidden" name="easy_element_group_<?php echo esc_attr($easyel_group_slug); ?>" 
                class="easyel-group-hidden" 
                value="<?php echo esc_attr($easyel_group_enabled); ?>" />
        </div>

        <div class="easyel-widgets-grid <?php echo esc_attr( $easyel_group_slug ); ?>" data-group="<?php echo esc_attr($easyel_group_slug); ?>">
            <?php foreach ( $easyel_widgets as $easyel_key => $easyel_widget ) : 
                $easyel_option_name = 'easy_element_' . $easyel_tab_slug . '_' . $easyel_key;
                $easyel_enabled = get_option($easyel_option_name, '1');

                $easyel_is_pro_enable = isset($easyel_widget['is_pro']) && $easyel_widget['is_pro'];
                $easyel_is_pro = $easyel_is_pro_enable && ! class_exists('Easy_Elements_Pro');
                $easyel_pro_attr_class = $easyel_is_pro ? ' easyel-pro-enable' : '';
                $easyel_pro_widget = $easyel_is_pro_enable ? 'easyel-pro-widget' : '';
                $easyel_is_upcoming_enable = isset( $easyel_widget['upcoming'] ) ? $easyel_widget['upcoming'] : '';

            ?>
                <div class="easy-widget-item easyel-widget-card easyel-dflex easyel-justify-between easyel-align-center <?php echo esc_attr($easyel_pro_attr_class . ' ' . $easyel_pro_widget); ?>" data-widget-key="<?php echo esc_attr($easyel_key); ?>">
                    <div class="easyel-widget-card-content easyel-dflex easyel-align-center">
                        <div class="easyel-widget-icon">
                             <?php 
                                if( $easyel_is_upcoming_enable ) { ?>
                                    <div class="easyel-pro-badge">
                                        <i class="easyelIcon-crown"></i>
                                        <?php esc_html_e( 'Upcoming', 'easy-elements' )?>
                                    </div>
                                   
                                    <?php } elseif( $easyel_is_pro_enable ) { ?>
                                    <div class="easyel-pro-badge">
                                        <i class="easyelIcon-crown"></i>
                                        <?php esc_html_e( 'Pro', 'easy-elements' )?>
                                    </div>
                                <?php } ?>
                            <i class="<?php echo esc_attr($easyel_widget['icon']); ?>"></i>
                        </div>
                        <div class="easyel-widget-text">
                            <div class="widget-header">
                                <strong><?php echo esc_html($easyel_widget['title']); ?></strong>

                            </div>
                            <div class="easyel-demo-link easyel-dflex easyel-align-center">
                                <?php if ( ! empty( $easyel_widget['demo_url'] ) ) : ?>
                                    <a href="<?php echo esc_url($easyel_widget['demo_url']); ?>" target="_blank" rel="noopener noreferrer">
                                        <i class="easyelIcon-monitor"></i>
                                        <?php esc_html_e('Demo', 'easy-elements'); ?>
                                    </a>
                                <?php endif; ?>
                                <?php if ( ! empty( $easyel_widget['docx_url'] ) ) : ?>
                                <a href="<?php echo esc_url($easyel_widget['docx_url']); ?>" target="_blank" rel="noopener noreferrer">
                                    <i class="easyelIcon-document"></i>
                                    <?php esc_html_e('Docs', 'easy-elements'); ?>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="widget-toggle easyel-widget-card-switcher <?php echo esc_attr( $easyel_pro_attr_class ); ?>">
                        <label class="easy-toggle-switch">
                            <?php 
                            if ( ! empty( $easyel_widget['is_pro'] ) && ! class_exists('Easy_Elements_Pro') ) { ?>
                                
                                <input type="checkbox" 
                                    class="widget-toggle-checkbox" 
                                    data-widget-key="<?php echo esc_attr($easyel_key); ?>"
                                    data-tab="<?php echo esc_attr($easyel_tab_slug); ?>"
                                    value="1"
                                    name="easy-toggle-switch"
                                    disabled />

                            <?php } else { ?>

                                <input type="checkbox" 
                                    class="widget-toggle-checkbox" 
                                    data-widget-key="<?php echo esc_attr($easyel_key); ?>"
                                    data-tab="<?php echo esc_attr($easyel_tab_slug); ?>"
                                    value="1"
                                    name="easy-toggle-switch"
                                    <?php checked( $easyel_enabled, '1' ); ?>
                                    <?php disabled( $easyel_is_pro ); ?> />

                            <?php } ?>

                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>