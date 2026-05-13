<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function easyel_field_select($sub_field, $value){
    echo '<select name="'.esc_attr($sub_field['full_name']).'" class="easyel-settings-field-input">';
    foreach($sub_field['options'] as $val => $label){
        echo '<option value="'.esc_attr($val).'" '.selected($value,$val,false).'>'.esc_html($label).'</option>';
    }
    echo '</select>';
}