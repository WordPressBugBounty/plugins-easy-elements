<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function easyel_field_radio($sub_field, $value){
    foreach($sub_field['options'] as $val => $label){
        echo '<label><input type="radio" name="'.esc_attr($sub_field['full_name']).'" value="'.esc_attr($val).'" '.checked($value,$val,false).' /> '.esc_html($label).'</label><br>';
    }
}