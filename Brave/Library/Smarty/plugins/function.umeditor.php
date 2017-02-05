<?php

function smarty_function_umeditor($params, &$smarty)
{
    $out = '';
    isset($params['libpath']) or $params['libpath'] = './umeditor/';
    
   if(!isset($params['name']) || empty($params['name']))
   {
      return $out;
   }

   $id = str_replace(array('[', ']'), '_', $params['name']);

   $out.= '<textarea class="umeditor" id="umeditor_' . $id . '" name="' . $params['name'] . '" style="width:' . $params['width'] . ';height:' . $params['height'] . ';">' . htmlspecialchars($params['value']) . '</textarea>';
   $out.= '<script type="text/javascript">
var um = UM.getEditor("umeditor_' . $id . '");
</script>';

   return $out;
}

?> 