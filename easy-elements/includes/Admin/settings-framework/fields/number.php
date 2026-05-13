<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function easyel_field_number($sub_field, $value){
    $class = $sub_field['class'] ?? '';
    echo '<input type="number" class="easyel-settings-field-input '.esc_attr($class).'" name="'.esc_attr($sub_field['full_name']).'" value="'.esc_attr($value).'" />';
}