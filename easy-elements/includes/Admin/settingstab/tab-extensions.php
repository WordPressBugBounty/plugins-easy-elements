<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$easyel_tab_slug = 'extensions';
$easyel_extensions_settings = get_option('easy_element_' . $easyel_tab_slug, []);
$easyel_defaults = array_fill_keys(array_keys(easyel_get_extension_fields()), 0);
$easyel_extensions_settings = wp_parse_args($easyel_extensions_settings, $easyel_defaults);

$easyel_fields = easyel_get_extension_fields();

// Group fields
$easyel_grouped_fields = [];
foreach ($easyel_fields as $easyel_key => $easyel_data) {
    $easyel_group_name = $easyel_data['group'] ?? 'General Extensions';
    $easyel_grouped_fields[$easyel_group_name][$easyel_key] = $easyel_data;
}
?>

<div class=" easyel-extension-main-wrapper">
    <div id="easyel-message-box"></div>
    <div class="form-table easyel-extension">
        <h1 class="easyel-dashboard-heading"><?php esc_html_e('Extensions','easy-elements');?></h1>
        <?php foreach($easyel_grouped_fields as $easyel_group_name => $easyel_group_fields) : 
            $easyel_group_slug = str_replace(' ', '-', strtolower($easyel_group_name));
            $easyel_group_enabled = get_option('easy_element_group_' . $easyel_group_slug, 0);
            ?>
            <div class="easyel-extension-heading-group easyel-dflex easyel-justify-between easyel-align-center">
                <h2 class="easyel-extension-group-title"><?php echo esc_html( $easyel_group_name ); ?></h2>
                
                <label class="easyel-toggle-switch-extension">
                    <input type="checkbox" 
                           class="easyel-group-toggle" 
                           name ="easyel-toggle-switch-extension"
                           data-group="<?php echo esc_attr($easyel_group_slug); ?>" 
                           <?php checked(1, $easyel_group_enabled); ?> /> 
                    <span class="easyel-enable-all">Enable All</span>
                    <span class="slider"></span>
                </label>
                <input type="hidden" name="easy_element_group_<?php echo esc_attr($easyel_group_slug); ?>" 
                       class="easyel-group-hidden" 
                       value="<?php echo esc_attr($easyel_group_enabled); ?>" />
            </div>

            <div class="easyel-extension-wrapper" data-group="<?php echo esc_attr($easyel_group_slug); ?>">
                <?php foreach($easyel_group_fields as $easyel_key => $easyel_data) : 

                    $easyel_is_pro_enable = $easyel_data['is_pro'];
                    $easyel_is_upcoming_enable = isset( $easyel_data['upcoming'] ) ? $easyel_data['upcoming'] : '';
                    $easyel_is_settings_enable = isset( $easyel_data['setting'] ) ? $easyel_data['setting'] : '';
                    $easyel_is_pro        = $easyel_is_pro_enable && ! class_exists('Easy_Elements_Pro');
                    $easyel_pro_class     = $easyel_is_pro ? ' easyel-pro-enable' : '';
                    $easyel_pro_widget    = $easyel_is_pro_enable ? ' easyel-pro-widget' : '';
                ?>
                    <div class="easyel-extension-item easyel-widget-card easyel-dflex easyel-justify-between easyel-align-center <?php echo esc_attr( $easyel_pro_class . $easyel_pro_widget ); ?>" style="padding-right:20px;">
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
                                
                                <img src="<?php echo esc_url( $easyel_data['icon'] ); ?>" alt="">
                            </div>
                            <div class="easyel-widget-text">
                                <div class="widget-header">
                                    <strong><?php echo esc_html($easyel_data['label']); ?></strong>
                                </div>
                                <div class="easyel-demo-link easyel-dflex easyel-align-center">
                                    <a href="<?php echo esc_url($easyel_data['demo_url']); ?>" target="_blank" rel="noopener noreferrer">
                                        <i class="easyelIcon-monitor"></i>
                                        <?php esc_html_e('Demo', 'easy-elements'); ?>
                                    </a>
                                    <a href="<?php echo esc_url($easyel_data['docs_url']); ?>" target="_blank" rel="noopener noreferrer">
                                        <i class="easyelIcon-document"></i>
                                        <?php esc_html_e('Doc', 'easy-elements'); ?>
                                    </a>
                                    <?php
                                    $easyel_settings_map = [
                                        'enable_smooth_scroller'        => 'easyel-scroll-smoother-popup-setting',
                                        'enable_reading_progress_bar'   => 'easyel-readingprogress-bar-popup-setting',
                                        'enable_quick_view'             => 'easyel-quick-view-setting',
                                        'enable_scroll_top'             => 'easyel-scroll-top-popup-setting',
                                        'enable_easyel_compare'             => 'easyel-compare-view-setting',
                                        'enable_easyel_wishlist'             => 'easyel-wishlist-view-setting',
                                        'enable_preloader'              => 'easyel-preloader-popup-setting',
                                    ];

                                    if ( $easyel_is_settings_enable && isset( $easyel_settings_map[ $easyel_key ] ) ) {
                                        $easyel_class = $easyel_settings_map[ $easyel_key ];
                                        ?>
                                        <span class="<?php echo esc_attr( $easyel_class ); ?> dashicons dashicons-admin-generic"></span>
                                        <?php
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                        <div class="widget-toggle easyel-widget-card-switcher">
                            <label class="easy-toggle-switch">
                                <?php 
                                     if ( ! empty( $easyel_data['is_pro'] ) && ! class_exists('Easy_Elements_Pro') ) { ?>
                                      <input type="checkbox" 
                                    class="easyel-extension-toggle" 
                                    data-key="<?php echo esc_attr($easyel_key); ?>" 
                                    data-tab="<?php echo esc_attr($easyel_tab_slug); ?>" 
                                    value="1"
                                    <?php disabled($easyel_is_pro, true); ?> />
                                <?php } else { 
                                ?>
                                <input type="checkbox" 
                                    class="easyel-extension-toggle" 
                                    data-key="<?php echo esc_attr($easyel_key); ?>" 
                                    data-tab="<?php echo esc_attr($easyel_tab_slug); ?>" 
                                    value="1"
                                    <?php checked(1, $easyel_extensions_settings[$easyel_key]); ?>
                                    <?php disabled($easyel_is_pro, true); ?> />
                                <?php } ?>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php 
do_action('easyel_smooth_scroller_popup');
do_action('easyel_reading_progress_bar_popup');
do_action('easyel_quickview_popup');
do_action('easyel_compare_popup');
do_action('easyel_wishlist_popup');
do_action('easyel_scroll_top_popup');
do_action('easyel_preloader_popup');
?>