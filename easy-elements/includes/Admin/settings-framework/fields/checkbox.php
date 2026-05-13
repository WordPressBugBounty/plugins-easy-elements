<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function easyel_field_checkbox($sub_field, $value){
    echo '<label><input type="checkbox" name="'.esc_attr($sub_field['full_name']).'" '.checked($value, 'on', false).' /> '.esc_html($sub_field['label']).'</label>';
}