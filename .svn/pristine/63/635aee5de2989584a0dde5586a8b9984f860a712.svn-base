<?php

function smarty_function_brave_error($params, &$smarty) {
    $error = '';
    $tag = $params['tag'] ? $params['tag'] : 'p';
    $data = $params['data'];
    
    if (isset($data[0])) {
        foreach ((array)$data as $v) {
            $error.= '<' . $tag . ' class="alert alert-danger"' . '>' . $v . '</' . $tag . '>';
        }
    }
    
    return $error;
}  

?>
