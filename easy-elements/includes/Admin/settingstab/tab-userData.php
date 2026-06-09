<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$easyel_tab_slug = isset($current_tab) ? $current_tab : 'user_data';

include EASYELEMENTS_DIR_PATH . "includes/Admin/settings-framework/init.php";
$easyel_fields_dir = EASYELEMENTS_DIR_PATH . 'includes/Admin/settings-framework/fields/';

$easyel_fm = new Easyel_FieldManager($easyel_fields_dir);


$easyel_settings = get_option('easy_element_user_data', []);
$easyel_settings_sections = [
    [
        'id' => 'social_feeds',
        'label' => 'Social Feeds',
    ],
];
$easyel_settings_fields = [
    [   
        'section_id' => 'social_feeds',
        'id' => 'fb_config',
        'name' => 'fb_config',
        'label' => 'Facebook',
        'type' => 'fields_group',
        'options' => [
            [ 'id' => 'page_id', 'name' => 'page_id', 'type' => 'text', 'default' => '', 'placeholder' => 'Page ID' ],
            [ 'id' => 'page_access_token', 'name' => 'page_access_token', 'type' => 'text', 'default' => '', 'class' => 'w-100', 'placeholder' => 'Page Access Token' ],
        ],
        'value' => !empty($easyel_settings['fb_config']) ? $easyel_settings['fb_config'] : [],
        'default' => '',
    ],
    [   
        'section_id' => 'social_feeds',
        'id' => 'insta_config',
        'name' => 'insta_config',
        'label' => 'Instagram',
        'type' => 'fields_group',
        'options' => [
            [ 'id' => 'username', 'name' => 'username', 'type' => 'text', 'default' => '', 'placeholder' => 'easy_elements' ],
            [ 'id' => 'page_access_token', 'name' => 'page_access_token', 'type' => 'text', 'default' => '', 'class' => 'w-100', 'placeholder' => 'Access Token' ],
        ],
        'value' => !empty($easyel_settings['insta_config']) ? $easyel_settings['insta_config'] : [],
        'default' => '',
    ],
];
?>
<div class="easyel-settings-wrapper">
    <div class="easyel-settings-topbar">
        <h3 class="easyel-settings-title"><?php echo esc_html__( 'Settings', 'easy-elements' )?></h3>
        <div class="easyel-settings-actions">
            <button type="button" id="easyel-settings-save">Save</button>
        </div>
    </div>
   <form id="easyel-settings-form" class="easyel-settings-wrapper">
        <div class="easyel-settings-fields">
            <?php foreach ( $easyel_settings_sections as $easyel_section ) : ?>
                <div class="easyel-settings-section" id="section-<?php echo esc_attr($easyel_section['id']); ?>">
                    <h3 class="easyel-section-title"><?php echo esc_html( $easyel_section['label'] ); ?></h3>

                    <?php foreach ( $easyel_settings_fields as $easyel_field ) : 
                        if($easyel_field['section_id'] !== $easyel_section['id']) continue;
                    ?>
                        
                        <?php
                        // Fields Group
                        if ($easyel_field['type'] === 'fields_group') {
                        ?>
                            <div class="easyel-settings-field">
                                <div class="easyel-settings-field-label"><?php echo esc_html($easyel_field['label']); ?></div>
                                <div class="easyel-settings-field-inputs">
                                    <div class="easyel-settings-field-input-wrapper">
                                        <?php $easyel_fm->render_fields_group($easyel_field, $easyel_settings); ?>
                                    </div>
                                </div>
                            </div>
                        <?php 
                        } else {
                            
                            $easyel_name = $easyel_field['name'];
                            $easyel_value = $easyel_settings[$easyel_name] ?? ($easyel_field['default'] ?? '');
                            $easyel_field['full_name'] = $easyel_name;
                        ?>
                            <div class="easyel-settings-field">
                                <div class="easyel-settings-field-label"><?php echo esc_html($easyel_field['label']); ?></div>
                                <div class="easyel-settings-field-inputs">
                                    <div class="easyel-settings-field-input-wrapper">
                                        <?php $easyel_fm->render_field($easyel_field, $easyel_value, ['name'=>$easyel_name, 'settings'=>$easyel_settings]); ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    <?php endforeach; ?>

                </div>
            <?php endforeach; ?>
        </div>
    </form>
</div>