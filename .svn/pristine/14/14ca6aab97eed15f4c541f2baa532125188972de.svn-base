<?php

function smarty_function_ace_switch($params, &$smarty) {
  $result = '';

  $id = $params['id'];
  $class = trim($params['class']);

  $checked = $params['value'] ? 'checked="checked"' : '';

  $curid = $id ? "{$id}_{$params['code']}" : "{$params['name']}_{$params['code']}";
  $curclass = $class ? ' ' . $class : ' ace ace-switch ace-switch-7';

  $chexkbox = '<input type="checkbox" checked="checked" id="' . $curid . '" class="checkbox' . $curclass . '" name="' . $params['name'] . '" value="' . $params['code'] . '" ' . $checked . ' /> ';
  $chexkbox .= '<span data-lbl="否&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;是" class="lbl"></span>';

  if (isset($params['tag'])) {
      $result.= "<{$params['tag']}>{$chexkbox}</{$params['tag']}>{$sep}";
  }
  else {
      $result.= $chexkbox . $sep;
  }

  return $result;
}

?>
