<?php

function smarty_function_brave_upload_image($params, &$smarty) {

    $image = isset($params['image']) ? $params['image'] : array();
    $type = isset($params['type']) ? $params['type'] : '';
    $name = isset($params['name']) ? $params['name'] : '';
    $base_url = isset($params['base_url']) ? $params['base_url'] : '';
    $file_path = isset($params['key']) ? $image[$params['key']] : $image['file_path'];

    $html = '';

    if(empty($file_path)) {
        $html = '<div class="image_add">' .
                '<a href="?c=upload&a=image&width=480&height=150&mode=true&_type=' . $type . '" class="thickbox" title="增加"><div>+</div></a>' .
                '<p style="text-align: center; font-weight: bolder; margin-top:9px;"><a href="?c=upload&a=image&width=480&height=150&mode=true&_type=' . $type . '" class="thickbox" title="增加">［增加］</a></p>' .
                '</div>';
    } else {
        $html = '<div class="single_image">';

        if($image['id'] == 0) {
            $html .= '<input type="hidden" name="' . $name . '" value="' . $file_path . '">' .
                    '<div><img src="' . $base_url . $file_path . '" /></div>';
        } else {
            $html .=  '<div><img src="' . $base_url . $file_path . '" /><input type="hidden" name="' . $name . '" value="' . $file_path . '"></div>';
        }

        $html .= '<p style="text-align: center; font-weight: bolder;"><a href="javascript:void(0);" onclick="deleteImage(this, ' . $image['id'] . ', \'' . $type . '\')">［删除］</a></p></div>';
    }

    return $html;
}

?>
