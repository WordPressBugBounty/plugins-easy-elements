<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class Easyel_FieldManager {
    protected $fields_path;
    protected $registered_types = [];

    public function __construct($fields_path) {
        $this->fields_path = rtrim($fields_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->load_core_types();
        add_action('wp_ajax_easyel_save_settings', array( $this, 'easyel_save_settings_callback') );
    }
    

    protected function load_core_types(){
        $types = ['text','number','checkbox','radio','select','group'];
        foreach($types as $t){
            $file = $this->fields_path . $t . '.php';
            if(file_exists($file)){
                include_once $file;
                $this->registered_types[$t] = true;
            }
        }
    }

    public function register_type($type, $callable_file){
        if(file_exists($callable_file)){
            include_once $callable_file;
            $this->registered_types[$type] = true;
            return true;
        }
        return false;
    }

    public function render_field($sub_field, $value, $field_context = []){
        $type = $sub_field['type'] ?? 'text';
        // allow group to receive parent field context
        if($type === 'group'){
            if(function_exists('easyel_field_group')){
                easyel_field_group($field_context, $field_context['settings'] ?? []);
            }
            return;
        }

        $sub_field['full_name'] = $sub_field['full_name'] ?? ($field_context['name'] . '[' . $sub_field['name'] . ']');

        $fn = 'easyel_field_' . $type;
        if(function_exists($fn)){
            call_user_func($fn, $sub_field, $value);
            return true;
        }

        // fallback simple input
        $class = $sub_field['class'] ?? '';
        $placeholder = $sub_field['placeholder'] ?? $sub_field['name'];
        printf('<input type="text" class="easyel-settings-field-input %s" name="%s" value="%s" placeholder="%s" />', esc_attr($class), esc_attr($sub_field['full_name']), esc_attr($value), esc_attr($placeholder));
        return false;
    }

    public function render_fields_group($field, $settings){
        echo '<div class="easyel-settings-field-group">';
        foreach($field['options'] as $sub){
            $name = $field['name'].'['.$sub['name'].']';
            $sub['full_name'] = $name;
            $value = $settings[$field['name']][$sub['name']] ?? ($sub['default'] ?? '');
            $this->render_field($sub, $value, ['name'=>$field['name'],'settings'=>$settings,'field'=>$field]);
        }
        echo '</div>';
    }

    public function easyel_save_settings_callback() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( ['msg' => 'Unauthorized'], 403 );
        }

        check_ajax_referer( 'easy_elements_settings_nonce', 'nonce' );

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $posted = $_POST['settings'] ?? [];
        $settings = map_deep( wp_unslash( $posted ), 'sanitize_text_field' );

        update_option( 'easy_element_settings', $settings );

        wp_send_json_success(['message' => 'Settings saved successfully!']);
    }
}