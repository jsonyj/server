<?php

function smarty_function_error_label($params, &$smarty) {
    $result = '';

    $class = $params['class'];
    $errors = $params['errors'];
    $tmp = preg_split('/,/',$params['name'],-1, PREG_SPLIT_NO_EMPTY);
    $tag = $params['tag'] ? $params['tag'] : 'span';

    if (empty($errors) || !$tmp) {
        return $result;
    }

    $result = '';
    foreach($tmp as $key) {
        if($errors[$key]) {
            $result .= $errors[$key];
        }
    }

    if($result) {
      $result = '<' . $tag . ' class="alert alert-danger alert-label-danger">' . $result . '</' . $tag . '>';
    }

    return $result;
}

?>
