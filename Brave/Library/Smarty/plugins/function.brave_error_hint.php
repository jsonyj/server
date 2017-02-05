<?php

function smarty_function_brave_error_hint($params, &$smarty) {
    $result = '';

    $class = $params['class'];
    $errors = $params['errors'];
    $tmp = preg_split('/,/',$params['name'],-1,PREG_SPLIT_NO_EMPTY);
    $tag = $params['tag'] ? $params['tag'] : 'p';

    if (empty($errors) || !$tmp) {
        return $result;
    }

    $result = '';
    foreach($tmp as $key) {
        if($errors[$key]) {
            $result .= $errors[$key] . '<br/>';
        }
    }

    if($result) {
      $result = '<' . $tag . ' class="alert alert-danger error">' . $result . '</' . $tag . '>';
    }

    return $result;
}  

?>
