<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function easyel_field_text($sub_field, $value){
    $class = $sub_field['class'] ?? '';
    $placeholder = $sub_field['placeholder'] ?? $sub_field['name'];
    echo '<input type="text" class="easyel-settings-field-input '.esc_attr($class).'" name="'.esc_attr($sub_field['full_name']).'" value="'.esc_attr($value).'" placeholder="'.esc_attr($placeholder).'" />';
}