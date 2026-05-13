<?php
if ( ! defined( 'ABSPATH' ) ) exit;
function easyel_field_group($field, $settings){
    echo '<div class="easyel-settings-field-group">';
    foreach($field['options'] as $sub){
        $name = $field['name'].'['.$sub['name'].']';
        $sub['full_name'] = $name;
        $value = $settings[$field['name']][$sub['name']] ?? ($sub['default'] ?? '');
        $type = $sub['type'];
        $file = __DIR__.'/'.$type.'.php';
        if(file_exists($file)) include $file;
        $fn = 'easyel_field_'.$type;
        if(function_exists($fn)) $fn($sub,$value);
    }
    echo '</div>';
}